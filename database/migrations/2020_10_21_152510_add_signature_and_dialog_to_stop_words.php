<?php

use Discuz\Database\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddSignatureAndDialogToStopWords extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $this->schema()->table('stop_words', function (Blueprint $table) {
            $table->string('signature', 10)->default('')->after('username')->comment('用户签名处理方式');
            $table->string('dialog', 10)->default('')->after('signature')->comment('短消息处理方式');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $this->schema()->table('stop_words', function (Blueprint $table) {
            $table->dropColumn('signature');
            $table->dropColumn('dialog');
        });
    }
}
