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

class CreateOrders extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $this->schema()->create('orders', function (Blueprint $table) {
            $table->id()->comment('订单 id');
            $table->char('order_sn', 22)->default('')->comment('订单编号');
            $table->string('payment_sn', 20)->default('')->comment('支付编号');
            $table->unsignedDecimal('amount', 10, 2)->comment('订单总金额');
            $table->unsignedDecimal('master_amount', 10, 2)->default(0.00)->comment('站长分成金额');
            $table->unsignedBigInteger('user_id')->comment('付款人 id');
            $table->unsignedBigInteger('payee_id')->comment('收款人 id');
            $table->unsignedTinyInteger('type')->default(0)->comment('交易类型：1注册、2打赏、3付费主题、4付费用户组');
            $table->unsignedInteger('thread_id')->nullable()->index()->comment('主题 id');
            $table->unsignedTinyInteger('status')->default(0)->comment('订单状态：0待付款；1已付款；2.取消订单；3支付失败；4订单过期');
            $table->unsignedSmallInteger('payment_type')->default(0)->comment('付款方式：微信（10：pc扫码，11：h5支付，12：微信内支付');
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
        $this->schema()->dropIfExists('orders');
    }
}
