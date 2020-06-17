<?php

use Discuz\Database\Migration;
use Illuminate\Database\Schema\Blueprint;

class AlterAddForeignKeyToUserFollow extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $this->schema()->disableForeignKeyConstraints();
        $this->schema()->table('user_follow', function (Blueprint $table) {
            $table->unsignedBigInteger('from_user_id')->change();
            $table->unsignedBigInteger('to_user_id')->change();
            $table->foreign('from_user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('to_user_id')->references('id')->on('users')->onDelete('cascade');
        });
        $this->schema()->enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $this->schema()->table('user_follow', function (Blueprint $table) {
            //
        });
    }
}
