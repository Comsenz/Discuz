<?php

use Discuz\Database\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateUserUcenters extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $this->schema()->create('user_ucenters', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('user_id')->index()->default(0)->comment('用户 id');
            $table->unsignedInteger('ucenter_id')->index()->default(0)->default(0)->comment('uc用户 id');
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
        $this->schema()->dropIfExists('user_ucenters');
    }
}
