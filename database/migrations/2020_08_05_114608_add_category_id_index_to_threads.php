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
            $table->index('is_sticky', 'idx_is_sticky');
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
            $table->dropIndex('idx_is_sticky');
        });
    }
}
