<?php

use Discuz\Database\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateSettings extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $this->schema()->create('settings', function (Blueprint $table) {
            $table->string('key')->comment('设置表key')->primary();
            $table->string('value')->comment('设置表value');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $this->schema()->dropIfExists('settings');
    }
}
