<?php

use Discuz\Database\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateGroupUser extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $this->schema()->create('group_user', function (Blueprint $table) {
            $table->integer('user_id')->unsigned()->nullable()->comment('用户 id');
            $table->integer('group_id')->unsigned()->nullable()->comment('用户组 id');

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('group_id')->references('id')->on('groups')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $this->schema()->dropIfExists('group_user');
    }
}
