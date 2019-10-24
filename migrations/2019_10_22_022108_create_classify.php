<?php

use Discuz\Database\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateClassify extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $this->schema()->create('classify', function (Blueprint $table) {
            $table->smallIncrements('id')->comment('分类id');
            $table->string('name')->default('')->comment('分类名称');
            $table->text('description')->comment('分类说明');
            $table->string('icon')->default('')->comment('分类图标URL');
            $table->unsignedSmallInteger('sort')->default(0)->comment('显示顺序');
            $table->unsignedTinyInteger('property')->default(0)->comment('属性：0:正常 1:首页展示');
            $table->unsignedInteger('threads')->default(0)->comment('主题数');
            $table->ipAddress('ip')->default('')->comment('更新IP');
            $table->dateTime('created_at');
            $table->dateTime('updated_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('classify');
    }
}
