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

use Discuz\Database\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateFinance extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $this->schema()->create('finance', function (Blueprint $table) {
            $table->id()->comment('自增ID');
            $table->unsignedDecimal('income', 10, 2)->comment('用户充值金额');
            $table->unsignedDecimal('withdrawal', 10, 2)->comment('用户提现金额');
            $table->unsignedInteger('order_count')->comment('订单数量');
            $table->unsignedDecimal('order_amount', 10, 2)->comment('订单金额');
            $table->unsignedDecimal('total_profit', 10, 2)->comment('平台盈利');
            $table->unsignedDecimal('register_profit', 10, 2)->comment('注册收入');
            $table->unsignedDecimal('master_portion', 10, 2)->comment('打赏贴的分成');
            $table->unsignedDecimal('withdrawal_profit', 10, 2)->comment('提现手续费收入');
            $table->date('created_at')->comment('创建时间');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $this->schema()->dropIfExists('finance');
    }
}
