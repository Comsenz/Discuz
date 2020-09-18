<?php

use Discuz\Database\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddPriceToAttachments extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $this->schema()->table('attachments', function (Blueprint $table) {
            $table->decimal('price')->unsigned()->after('type')->default(0)->comment('价格');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $this->schema()->table('attachments', function (Blueprint $table) {
            $table->dropColumn('price');
        });
    }
}
