<?php

use Discuz\Database\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateSessionTokens extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $this->schema()->create('session_tokens', function (Blueprint $table) {
            $table->string('token')->unique()->comment('token');
            $table->string('scope')->nullable()->comment('作用域');
            $table->text('payload')->nullable()->comment('负载');
            $table->unsignedInteger('user_id')->nullable()->comment('用户 id');
            $table->dateTime('created_at')->comment('创建时间');
            $table->dateTime('expired_at')->comment('过期时间');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $this->schema()->dropIfExists('session_tokens');
    }
}
