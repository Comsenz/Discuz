<?php

use Discuz\Database\Migration;
use Illuminate\Database\Schema\Blueprint;

class ChangeScaleToGroups extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $this->schema()->table('groups', function (Blueprint $table) {
            $table->float('scale', 3, 1)->default(0)->comment('分成比例');
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
            $table->dropColumn('scale');
        });
    }
}
