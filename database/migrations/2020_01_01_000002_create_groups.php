<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

use Discuz\Database\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateGroups extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $this->schema()->create('groups', function (Blueprint $table) {
            $table->id()->comment('用户组 id');
            $table->string('name')->default('')->comment('用户组名称');
            $table->string('type', 50)->default('')->comment('类型');
            $table->string('color', 20)->default('')->comment('颜色');
            $table->string('icon', 100)->default('')->comment('icon');
            $table->tinyInteger('default')->unsigned()->default(0)->comment('是否默认');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $this->schema()->dropIfExists('groups');
    }
}
