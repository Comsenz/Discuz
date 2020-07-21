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

class CreateUserWalletCash extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $this->schema()->create('user_wallet_cash', function (Blueprint $table) {
            $table->id()->comment('提现 id');
            $table->unsignedBigInteger('user_id')->comment('提现用户 id');
            $table->unsignedBigInteger('cash_sn')->comment('提现交易编号');
            $table->unsignedDecimal('cash_charge', 10, 2)->comment('提现手续费');
            $table->unsignedDecimal('cash_actual_amount', 10, 2)->comment('实际提现金额');
            $table->unsignedDecimal('cash_apply_amount', 10, 2)->comment('提现申请金额');
            $table->unsignedTinyInteger('cash_status')->default(0)->comment('提现状态：1：待审核，2：审核通过，3：审核不通过，4：待打款， 5，已打款， 6：打款失败');
            $table->string('remark', 255)->default('')->comment('备注或原因');
            $table->dateTime('trade_time')->nullable()->comment('交易时间');
            $table->string('trade_no', 64)->nullable()->comment('交易号');
            $table->string('error_code', 64)->nullable()->comment('错误代码');
            $table->string('error_message', 64)->nullable()->comment('交易失败描叙');
            $table->unsignedTinyInteger('refunds_status')->default(0)->comment('返款状态，0未返款，1已返款');
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
        $this->schema()->dropIfExists('user_wallet_cash');
    }
}
