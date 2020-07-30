<?php

/**
 * Copyright (C) 2020 Tencent Cloud.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

use Discuz\Database\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddReadAtToDialog extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $this->schema()->table('dialog', function (Blueprint $table) {
            $table->dateTime('sender_read_at')->nullable()->after('recipient_user_id')->comment('发送人阅读时间');
            $table->dateTime('recipient_read_at')->nullable()->after('recipient_user_id')->comment('收信人阅读时间');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $this->schema()->table('dialog', function (Blueprint $table) {
            $table->dropColumn('sender_read_at');
            $table->dropColumn('recipient_read_at');
        });
    }
}
