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

class CreateUserWalletLogs extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $this->schema()->create('user_wallet_logs', function (Blueprint $table) {
            $table->id()->comment('钱包明细 id');
            $table->unsignedBigInteger('user_id')->comment('明细所属用户 id');
            $table->decimal('change_available_amount', 10, 2)->comment('变动可用金额');
            $table->decimal('change_freeze_amount', 10, 2)->comment('变动冻结金额');
            $table->unsignedSmallInteger('change_type')->default(0)->comment('10：提现冻结，11：提现成功，12：撤销提现解冻； 31：打赏收入，32：人工收入； 50：人工支出');
            $table->string('change_desc', 255)->default('')->comment('变动描述');
            $table->unsignedBigInteger('order_id')->nullable()->comment('关联订单记录ID');
            $table->unsignedBigInteger('user_wallet_cash_id')->nullable()->comment('关联提现记录ID');
            $table->dateTime('created_at')->comment('创建时间');
            $table->dateTime('updated_at')->comment('更新时间');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $this->schema()->dropIfExists('user_wallet_logs');
    }
}
