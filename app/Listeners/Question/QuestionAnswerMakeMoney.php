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

use App\Events\Question\Saved;
use App\Models\UserWalletLog;
use App\Settings\SettingsRepository;
use Exception;
use Illuminate\Contracts\Bus\Dispatcher;
use Illuminate\Database\ConnectionInterface;

class QuestionAnswerMakeMoney
{
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

    public function handle(Saved $event)
    {
        $question = $event->question;
        $actor = $event->actor;

        $price = $question->price;

        // Start Transaction
        $this->connection->beginTransaction();
        try {
            $freezeAmount = $question->user->userWallet->freeze_amount;
            $question->user->userWallet->freeze_amount = number_format($freezeAmount - $price, 2, '.', '');
            $question->user->userWallet->save();

            // Create User Wallet Log
            UserWalletLog::createWalletLog(
                $question->user_id,     // 明细所属用户 id
                0,                      // 变动可用金额
                -$price,                // 变动冻结金额
                UserWalletLog::TYPE_EXPEND_QUESTION, // 81 问答提问支出
                trans('wallet.expend_question'),
                null,                   // 关联提现ID
                null,                   // 订单ID
                0,                      // 分成来源用户
                $question->id           // 关联问答ID
            );

            // 站长分成回答金额
            $siteAuthorScale = $this->settings->get('site_author_scale');
            $authorRatio = $siteAuthorScale / 10;
            $authorPrice = $price * $authorRatio;

            $availableAmount = $actor->userWallet->available_amount;
            $actor->userWallet->available_amount = number_format($availableAmount + $authorPrice, 2, '.', '');
            $actor->userWallet->save();

            // Create Be User Wallet Log
            UserWalletLog::createWalletLog(
                $question->be_user_id,  // 明细所属用户 id
                $price,                 // 变动可用金额
                0,                      // 变动冻结金额
                UserWalletLog::TYPE_INCOME_QUESTION_REWARD, // 35 问答答题收入
                trans('wallet.income_question_reward'),
                null,                   // 关联提现ID
                null,                   // 订单ID
                0,                      // 分成来源用户
                $question->id           // 关联问答ID
            );

            // TODO Question Send Notice
            // $this->bus->dispatch(
            // new SendNotice($event->actor, 0, $event->denyUser->deny_user_id)
            // );

            $this->connection->commit();
        } catch (Exception $e) {
            $this->connection->rollback();
        }
    }
}
