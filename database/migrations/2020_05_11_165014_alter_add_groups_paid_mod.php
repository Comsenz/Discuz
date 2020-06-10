<?php

use Discuz\Database\Migration;
use Illuminate\Database\Schema\Blueprint;

class AlterAddGroupsPaidMod extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $this->schema()->table('groups', function (Blueprint $table) {
            $table->unsignedTinyInteger('is_paid')->default(0)->after('is_display')->comment('是否收费：0不收费，1收费');
            $table->unsignedDecimal('fee', 10, 2)->default(0.00)->after('is_paid')->comment('收费金额');
            $table->unsignedBigInteger('days')->default(0)->after('fee')->comment('付费获得天数');
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
            $table->dropColumn('is_paid');
            $table->dropColumn('fee');
            $table->dropColumn('days');
        });
    }
}
