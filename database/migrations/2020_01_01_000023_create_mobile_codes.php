<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

use Discuz\Database\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateMobileCodes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $this->schema()->create('mobile_codes', function (Blueprint $table) {
            $table->id()->comment('验证码 id');
            $table->string('mobile', 20)->default('')->comment('手机号');
            $table->string('code', 10)->default('')->comment('验证码');
            $table->string('type', 20)->default('')->comment('验证类型');
            $table->tinyInteger('state')->default(0)->comment('验证状态');
            $table->ipAddress('ip')->default('')->comment('ip');
            $table->dateTime('expired_at')->nullable()->comment('验证码过期时间');
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
        $this->schema()->dropIfExists('mobile_codes');
    }
}
