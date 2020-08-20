<?php

use Discuz\Database\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateUserDistributions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $this->schema()->create('user_distributions', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('pid')->nullable()->comment('父级id');
            $table->unsignedBigInteger('user_id')->nullable()->comment('用户ID');
            $table->float('be_scale', 3, 1)->default(0)->comment('受邀时的分成比例');
            $table->tinyInteger('level')->default(1)->comment('当前用户所处深度');

            $table->dateTime('updated_at')->comment('更新时间');
            $table->dateTime('created_at')->comment('创建时间');

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

            $table->index('user_id', 'idx_user');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $this->schema()->dropIfExists('user_distributes');
    }
}
