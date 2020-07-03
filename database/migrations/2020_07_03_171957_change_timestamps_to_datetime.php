<?php

use Discuz\Database\Migration;
use Illuminate\Database\Schema\Blueprint;

class ChangeTimestampsToDatetime extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $this->schema()->table('operation_logs', function (Blueprint $table) {
            $table->dateTime('created_at')->comment('创建时间')->change();
            $table->dateTime('updated_at')->comment('更新时间')->change();
        });

        $this->schema()->table('reports', function (Blueprint $table) {
            $table->dateTime('created_at')->comment('创建时间')->change();
            $table->dateTime('updated_at')->comment('更新时间')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

    }
}
