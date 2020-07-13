<?php

use Discuz\Database\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateVoteLogs extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $this->schema()->create('vote_logs', function (Blueprint $table) {
            $table->id()->comment('投票记录ID');
            $table->unsignedBigInteger('vote_id')->comment('投票ID');
            $table->unsignedBigInteger('option_id')->comment('选项ID');
            $table->ipAddress('ip')->default('')->comment('IP');
            $table->dateTime('created_at')->comment('创建时间');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $this->schema()->dropIfExists('vote_logs');
    }
}
