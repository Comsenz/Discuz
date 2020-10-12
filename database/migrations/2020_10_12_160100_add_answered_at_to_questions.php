<?php

use Discuz\Database\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddAnsweredAtToQuestions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $this->schema()->table('questions', function (Blueprint $table) {
            $table->dateTime('answered_at')->nullable()->comment('回答时间');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $this->schema()->table('questions', function (Blueprint $table) {
            $table->dropColumn('answered_at');
        });
    }
}
