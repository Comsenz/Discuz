<?php

use Discuz\Database\Migration;
use Illuminate\Database\Schema\Blueprint;

class UpdateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $this->schema()->table('users', function (Blueprint $table) {
            $table->integer('username_bout')->unsigned()->default(0)->comment('用户名修改次数')->after('register_reason');
            $table->string('signature')->default('')->comment('签名')->after('mobile_confirmed');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $this->schema()->table('users', function (Blueprint $table) {
            //
        });
    }
}
