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

class ChangeThreadVideoColumns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $this->schema()->table('thread_video', function (Blueprint $table) {
            $table->unsignedInteger('post_id')->after('thread_id')->index()->comment('帖子 id');
            $table->unsignedTinyInteger('type')->default(0)->after('user_id')->comment('类型：0 视频 1 音频');
            $table->unsignedInteger('width')->default(0)->after('file_id')->comment('视频宽');
            $table->unsignedInteger('height')->default(0)->after('file_id')->comment('视频高');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $this->schema()->table('thread_video', function (Blueprint $table) {
            $table->dropColumn('post_id');
            $table->dropColumn('type');
            $table->dropColumn('width');
            $table->dropColumn('height');
        });
    }
}
