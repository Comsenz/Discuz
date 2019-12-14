<?php

use Discuz\Database\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateGroupPermission extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $this->schema()->create('group_permission', function (Blueprint $table) {
            $table->unsignedInteger('group_id')->comment('用户组ID');
            $table->string('permission')->default('')->comment('权限名称');

            $table->primary(['group_id', 'permission']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $this->schema()->dropIfExists('group_permission');
    }
}
