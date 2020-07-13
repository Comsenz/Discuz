<?php

use Discuz\Database\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateVoteItems extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $this->schema()->create('vote_options', function (Blueprint $table) {
            $table->id()->comment('投票选项ID');
            $table->unsignedBigInteger('vote_id')->comment('投票ID');
            $table->text('content')->comment('投票选项内容');
            $table->unsignedBigInteger('count')->default(0)->comment('投票数量');
            $table->dateTime('updated_at')->comment('更新时间');
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
        $this->schema()->dropIfExists('vote_options');
    }
}
