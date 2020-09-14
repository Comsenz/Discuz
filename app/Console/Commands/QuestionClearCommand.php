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

namespace App\Console\Commands;

use App\Models\Question;
use App\Models\UserWalletLog;
use Carbon\Carbon;
use Discuz\Console\AbstractCommand;
use Discuz\Foundation\Application;
use Exception;
use Illuminate\Database\ConnectionInterface;

class QuestionClearCommand extends AbstractCommand
{
    protected $signature = 'clear:question';

    protected $description = '返还过期未回答的问答金额';

    protected $app;

    /**
     * @var ConnectionInterface
     */
    protected $connection;

    /**
     * AvatarCleanCommand constructor.
     * @param string|null $name
     * @param Application $app
     * @param ConnectionInterface $connection
     */
    public function __construct(string $name = null, Application $app, ConnectionInterface $connection)
    {
        parent::__construct($name);

        $this->app = $app;
        $this->connection = $connection;
    }

    public function handle()
    {
        $today = Carbon::today()->toDateTimeString();

        $query = Question::query();
        $query->where('expired_at', '<=', $today);
        $query->where('is_answer', Question::TYPE_OF_UNANSWERED); // 未回答
        $question = $query->get();

        $bar = $this->createProgressBar(count($question));
        $bar->start();

        $question->map(function ($item) use ($bar) {
            // Start Transaction
            $this->connection->beginTransaction();
            try {
                /** @var Question $item */
                $item->is_answer = Question::TYPE_OF_EXPIRED;
                $item->save();

                // freeze amount
                $item->user->userWallet->available_amount = $item->user->userWallet->available_amount + $item->price;
                $item->user->userWallet->freeze_amount = $item->user->userWallet->freeze_amount - $item->price;
                $item->user->userWallet->save();

                // freeze log
                UserWalletLog::createWalletLog(
                    $item->user->id,        // 明细所属用户 id
                    $item->price,           // 变动可用金额
                    -$item->price,          // 变动冻结金额
                    UserWalletLog::TYPE_QUESTION_RETURN_THAW, // 9 问答返还解冻
                    trans('wallet.question_return_thaw_desc'),
                    null,                   // 关联提现ID
                    null,                   // 订单ID
                    0,                      // 分成来源用户
                    $item->id               // 关联问答ID
                );

                // 修改过期后输出
                $this->question('');
                $this->question('过期问答ID: ' . $item->id . ' 帖子ID：' . $item->thread_id);
                $this->comment('金额返还 ' . $item->price);

                $this->connection->commit();
            } catch (Exception $e) {
                $this->connection->rollback();
            }

            $bar->advance();
        });

        $bar->finish();
    }
}
