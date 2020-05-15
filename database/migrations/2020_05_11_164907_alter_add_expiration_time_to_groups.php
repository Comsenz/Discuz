<?php

use Discuz\Database\Migration;
use Illuminate\Database\Schema\Blueprint;

class AlterAddExpirationTimeToGroups extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $this->schema()->table('group_user', function (Blueprint $table) {
            $table->dateTime('expiration_time')->nullable()->after('user_id')->comment('用户组到期时间');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $this->schema()->table('group_user', function (Blueprint $table) {
            $table->dropColumn('expiration_time');
        });
    }
}
