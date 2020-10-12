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

use App\Commands\Wallet\ChangeUserWallet;
use App\Events\Question\Saved;
use App\Models\UserWallet;
use App\Models\UserWalletLog;
use App\Settings\SettingsRepository;
use Discuz\Foundation\EventsDispatchTrait;
use Exception;
use Illuminate\Contracts\Bus\Dispatcher;
use Illuminate\Database\ConnectionInterface;

class QuestionAnswerMakeMoney
{
    use EventsDispatchTrait;

    /**
     * @var Dispatcher
     */
    protected $bus;

    /**
     * @var SettingsRepository
     */
    protected $settings;

    /**
     * @var ConnectionInterface
     */
    protected $connection;

    public function __construct(Dispatcher $bus, SettingsRepository $settings, ConnectionInterface $connection)
    {
        $this->bus = $bus;
        $this->settings = $settings;
        $this->connection = $connection;
    }

    /**
     * @param Saved $event
     * @throws Exception
     */
    public function handle(Saved $event)
    {
        $question = $event->question;
        $actor = $event->actor;

        $price = $question->price;

        if ($price <= 0) {
            return;
        }

        // Start Transaction
        $this->connection->beginTransaction();
        try {
            // freeze amount decrease
            $data = [
                'question_id' => $question->id, // 关联问答ID
                'change_type' => UserWalletLog::TYPE_EXPEND_QUESTION, // 81 问答提问支出
                'change_desc' => trans('wallet.expend_question'),
            ];
            $this->bus->dispatch(new ChangeUserWallet($question->user, UserWallet::OPERATE_DECREASE_FREEZE, $price, $data));

            // 站长分成回答金额
            $siteAuthorScale = $this->settings->get('site_author_scale');
            $authorRatio = $siteAuthorScale / 10;
            $authorPrice = $price * $authorRatio;

            // available amount increase
            $actorData = [
                'question_id' => $question->id, // 关联问答ID
                'change_type' => UserWalletLog::TYPE_INCOME_QUESTION_REWARD, // 35 问答答题收入
                'change_desc' => trans('wallet.income_question_reward'),
            ];
            $this->bus->dispatch(new ChangeUserWallet($actor, UserWallet::OPERATE_INCREASE, $authorPrice, $actorData));

            $this->connection->commit();
        } catch (Exception $e) {
            $this->connection->rollback();

            throw $e;
        }
    }
}
