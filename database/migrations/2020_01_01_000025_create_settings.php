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
            $table->string('key', 100)->comment('设置项 key');
            $table->text('value')->nullable()->comment('设置项 value');
            $table->string('tag', 100)->default('default')->comment('设置项 tag');

            $table->primary(['key', 'tag']);
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
