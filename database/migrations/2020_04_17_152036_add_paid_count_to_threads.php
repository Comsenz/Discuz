<?php

use Discuz\Database\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddPaidCountToThreads extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $this->schema()->table('threads', function (Blueprint $table) {
            $table->unsignedInteger('paid_count')->default(0)->after('view_count')->comment('付费数');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $this->schema()->table('threads', function (Blueprint $table) {
            $table->dropColumn('paid_count');
        });
    }
}
