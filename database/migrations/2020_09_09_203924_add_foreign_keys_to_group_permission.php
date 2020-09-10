<?php

use App\Models\Group;
use App\Models\Permission;
use Discuz\Database\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToGroupPermission extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // 删除不存在的用户组的权限
        Permission::query()->whereNotIn('group_id', Group::query()->pluck('id'))->delete();

        $this->schema()->table('group_permission', function (Blueprint $table) {
            $table->foreign('group_id')->references('id')->on('groups')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $this->schema()->table('group_permission', function (Blueprint $table) {
            $table->dropForeign(['group_id']);
        });
    }
}
