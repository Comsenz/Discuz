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

class AddLocationToThreads extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $this->schema()->table('threads', function (Blueprint $table) {
            $table->decimal('longitude', 10, 7)->default(0)->after('paid_count')->comment('经度');
            $table->decimal('latitude', 10, 7)->default(0)->after('longitude')->comment('纬度');
            $table->string('location', 100)->after('latitude')->comment('位置');
        });

        $this->schema()->table('posts', function (Blueprint $table) {
            $table->dropColumn('longitude');
            $table->dropColumn('latitude');
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
            $table->dropColumn('longitude');
            $table->dropColumn('latitude');
            $table->dropColumn('location');
        });

        $this->schema()->table('posts', function (Blueprint $table) {
            $table->decimal('longitude', 10, 7)->default(0)->after('like_count')->comment('经度');
            $table->decimal('latitude', 10, 7)->default(0)->after('longitude')->comment('纬度');
        });
    }
}
