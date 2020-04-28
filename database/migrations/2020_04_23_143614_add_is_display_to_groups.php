<?php

use Discuz\Database\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddIsDisplayToGroups extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $this->schema()->table('groups', function (Blueprint $table) {
            $table->unsignedTinyInteger('is_display')->default(0)->after('default')->comment('是否显示在用户名后');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $this->schema()->table('groups', function (Blueprint $table) {
            $table->dropColumn('is_display');
        });
    }
}
