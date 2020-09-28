<?php

use Discuz\Database\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddQuestionCountToUsers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $this->schema()->table('users', function (Blueprint $table) {
            $table->unsignedInteger('question_count')->default(0)->after('liked_count')->comment('提问数');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $this->schema()->table('users', function (Blueprint $table) {
            $table->dropColumn('question_count');
        });
    }
}
