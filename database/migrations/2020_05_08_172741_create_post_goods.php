<?php

use Discuz\Database\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePostGoods extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $this->schema()->create('post_goods', function (Blueprint $table) {
            $table->id()->comment('商品 id');

            $table->unsignedBigInteger('user_id')->default(0)->comment('用户 id');
            $table->unsignedBigInteger('post_id')->default(0)->comment('帖子 id');
            $table->string('platform_id')->default('')->comment('平台商品 id');
            $table->string('title')->default('')->comment('商品标题');
            $table->unsignedDecimal('price', 10, 2)->default(0)->comment('价格');
            $table->string('image_path')->default('')->comment('商品封面图');
            $table->unsignedTinyInteger('type')->default(0)->comment('商品来源:0淘宝 1天猫 2京东 等');
            $table->unsignedTinyInteger('status')->default(0)->comment('商品状态:0正常 1失效/下架');
            $table->text('ready_content')->comment('预解析内容');
            $table->text('detail_content')->comment('解析详情页地址');

            $table->dateTime('created_at')->comment('创建时间');
            $table->dateTime('updated_at')->comment('更新时间');
            $table->dateTime('deleted_at')->nullable()->comment('删除时间');

            $table->index('user_id', 'idx_user');
            $table->index('post_id', 'idx_post');
            $table->index('platform_id', 'idx_platform');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $this->schema()->dropIfExists('post_goods');
    }
}
