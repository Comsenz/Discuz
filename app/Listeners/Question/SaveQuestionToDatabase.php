<?php

/**
 * Copyright (C) 2020 Tencent Cloud.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

namespace App\Listeners\Question;

use App\Events\Post\Saved;
use App\Models\Order;
use App\Models\Question;
use App\Models\Thread;
use App\Models\UserWalletLog;
use App\Validators\QuestionValidator;
use Carbon\Carbon;
use Discuz\Contracts\Setting\SettingsRepository;
use Discuz\Foundation\EventsDispatchTrait;
use Exception;
use Illuminate\Contracts\Bus\Dispatcher as BusDispatcher;
use Illuminate\Contracts\Events\Dispatcher as EventDispatcher;
use Illuminate\Database\ConnectionInterface;
use Illuminate\Support\Arr;
use Illuminate\Validation\ValidationException;

class SaveQuestionToDatabase
{
    use EventsDispatchTrait;

    /**
     * @var QuestionValidator
     */
    protected $questionValidator;

    /**
     * @var ConnectionInterface
     */
    protected $connection;

    /**
     * @var SettingsRepository
     */
    protected $settings;

    /**
     * @var BusDispatcher
     */
    protected $bus;

    public function __construct(
        EventDispatcher $eventDispatcher,
        QuestionValidator $questionValidator,
        ConnectionInterface $connection,
        SettingsRepository $settings,
        BusDispatcher $bus
    ) {
        $this->events = $eventDispatcher;
        $this->questionValidator = $questionValidator;
        $this->connection = $connection;
        $this->settings = $settings;
        $this->bus = $bus;
    }

    /**
     * @param Saved $event
     * @throws ValidationException
     * @throws Exception
     */
    public function handle(Saved $event)
    {
        $post = $event->post;
        $actor = $event->actor;
        $data = $event->data;

        if ($post->thread->type == Thread::TYPE_OF_QUESTION) {
            // 判断是否是创建
            if ($post->is_first && $post->wasRecentlyCreated) {
                $questionData = Arr::get($data, 'relationships.question.data');
                if (empty($questionData)) {
                    throw new Exception(trans('post.post_question_missing_parameter')); // 问答缺失参数
                }

                /**
                 * Validator
                 *
                 * @see QuestionValidator
                 */
                $questionData['actor'] = $actor;
                $this->questionValidator->valid($questionData);
                $price = Arr::get($questionData, 'price');
                $isOnlooker = Arr::get($questionData, 'is_onlooker', true); // 获取帖子是否允许围观

                // get unit price
                $siteOnlookerPrice = (float) $this->settings->get('site_onlooker_price', 'default', 0);
                if ($siteOnlookerPrice > 0 && $isOnlooker) {
                    $onlookerUnitPrice = $siteOnlookerPrice;
                }

                // Start Transaction
                $this->connection->beginTransaction();
                try {
                    /**
                     * Create Question
                     *
                     * @var Question $question
                     */
                    $build = [
                        'thread_id' => $post->thread_id,
                        'user_id' => $actor->id,
                        'be_user_id' => Arr::get($questionData, 'be_user_id'),
                        'price' => $price,
                        'onlooker_unit_price' => $onlookerUnitPrice ?? 0,
                        'is_onlooker' => $actor->can('canBeOnlooker') ? $isOnlooker : false,
                        'expired_at' => Carbon::today()->addDays(Question::EXPIRED_DAY),
                    ];
                    $question = Question::build($build);
                    $question->save();

                    // 判断如果没有传 order_id 说明是0元提问，就不需要冻结钱包
                    if (! empty($orderSn = Arr::get($questionData, 'order_id', null))) {
                        /**
                         * Update Order relation thread_id
                         *
                         * @var Order $order
                         */
                        $order = Order::query()->where('order_sn', $orderSn)->firstOrFail();
                        $order->thread_id = $post->thread_id;
                        $order->save();

                        /**
                         * Update WalletLog relation question_id
                         *
                         * @var Order $order
                         * @var UserWalletLog $walletLog
                         */
                        if ($order->payment_type == Order::PAYMENT_TYPE_WALLET) {
                            $walletLog = UserWalletLog::query()->where([
                                'user_id' => $actor->id,
                                'order_id' => $order->id,
                                'change_type' => UserWalletLog::TYPE_QUESTION_FREEZE,
                            ])->first();

                            $walletLog->question_id = $question->id;
                            $walletLog->save();
                        }
                    }

                    $this->connection->commit();
                } catch (Exception $e) {
                    $this->connection->rollback();

                    throw $e;
                }

                // 延迟执行事件
                $this->dispatchEventsFor($question, $actor);
            }
        }
    }
}
