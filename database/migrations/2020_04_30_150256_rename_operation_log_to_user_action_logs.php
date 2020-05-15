<?php

use Discuz\Database\Migration;

class RenameOperationLogToUserActionLogs extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $this->schema()->rename('operation_log', 'user_action_logs');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $$this->schema()->rename('user_action_logs', 'operation_log');
    }
}
