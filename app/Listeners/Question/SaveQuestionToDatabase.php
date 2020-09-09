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
use App\Models\Question;
use App\Models\Thread;
use App\Models\UserWalletLog;
use App\Validators\QuestionValidator;
use Carbon\Carbon;
use Exception;
use Illuminate\Contracts\Events\Dispatcher as EventDispatcher;
use Illuminate\Database\ConnectionInterface;
use Illuminate\Support\Arr;

class SaveQuestionToDatabase
{
    /**
     * @var EventDispatcher
     */
    protected $eventDispatcher;

    /**
     * @var QuestionValidator
     */
    protected $questionValidator;

    /**
     * @var ConnectionInterface
     */
    protected $connection;

    public function __construct(EventDispatcher $eventDispatcher, QuestionValidator $questionValidator, ConnectionInterface $connection)
    {
        $this->eventDispatcher = $eventDispatcher;
        $this->questionValidator = $questionValidator;
        $this->connection = $connection;
    }

    /**
     * @param Saved $event
     * @throws \Illuminate\Validation\ValidationException
     * @throws Exception
     */
    public function handle(Saved $event)
    {
        $post = $event->post;
        $actor = $event->actor;
        $data = $event->data;

        if ($post->thread->type == Thread::TYPE_OF_QUESTION) {
            $questionData = Arr::get($data, 'relationships.question.data');

            /**
             * Validator
             * @see AbstractValidator
             */
            $this->questionValidator->valid($questionData);
            $price = Arr::get($questionData, 'price');
            if ($actor->userWallet->available_amount < $price) {
                throw new Exception(trans('wallet.available_amount_error')); // 钱包余额不足
            }

            // Start Transaction
            $this->connection->beginTransaction();
            try {
                // freeze amount
                $actor->userWallet->available_amount = $actor->userWallet->available_amount - $price;
                $actor->userWallet->freeze_amount = $actor->userWallet->freeze_amount + $price;
                $actor->userWallet->save();

                // Create Question
                $build = [
                    'thread_id' => $post->thread_id,
                    'user_id' => $actor->id,
                    'be_user_id' => Arr::get($questionData, 'be_user_id'),
                    'price' => $price,
                    'onlooker_unit_price' => Arr::get($questionData, 'onlooker_unit_price'), // TODO Question 围观价格走Settings
                    'is_onlooker' => $actor->can('canBeOnlooker') ? Arr::get($questionData, 'is_onlooker', true) : false,
                    'expired_at' => Carbon::today()->addDays(Question::EXPIRED_DAY),
                    // 'expired_at' => Carbon::today()->subDays(8)->addDays(Question::EXPIRED_DAY),
                ];
                $question = Question::build($build);
                $question->save();

                // Create Wallet Log
                UserWalletLog::createWalletLog(
                    $actor->id,             // 明细所属用户 id
                    -$price,                // 变动可用金额
                    $price,                 // 变动冻结金额
                    UserWalletLog::TYPE_QUESTION_RETURN_THAW, // 7 问答冻结
                    trans('wallet.question_freeze_desc'),
                    null,                   // 关联提现ID
                    null,                   // 订单ID
                    0,                      // 分成来源用户
                    $question->id           // 关联问答ID
                );

                $this->connection->commit();

            } catch (Exception $e) {
                $this->connection->rollback();
            }

            // TODO Question Send Notice
        }
    }
}
