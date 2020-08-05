<?php

use Discuz\Database\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddCategoryIdIndexToThreads extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $this->schema()->table('threads', function (Blueprint $table) {
            $table->index('category_id', 'idx_category_id');
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
            $table->dropIndex('idx_category_id');
        });
    }
}
