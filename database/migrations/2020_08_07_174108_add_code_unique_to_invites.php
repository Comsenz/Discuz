<?php

use Discuz\Database\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddCodeUniqueToInvites extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $this->schema()->table('invites', function (Blueprint $table) {
            $table->unique('code', 'uk_code');
            $table->index('user_id', 'idx_user_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $this->schema()->table('invites', function (Blueprint $table) {
            $table->dropIndex('uk_code');
            $table->dropIndex('idx_user_id');
        });
    }
}
