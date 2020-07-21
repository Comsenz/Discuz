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

use App\Models\Group;
use Illuminate\Database\Seeder;

class GroupTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $groups = new Group();
        $groups->getConnection()->statement('SET FOREIGN_KEY_CHECKS=0;');
        $groups->truncate();
        $groups->getConnection()->statement('SET FOREIGN_KEY_CHECKS=1;');
        $groups->insert([
            [
                'id' => 1,
                'name' => '管理员',
                'default' => 0
            ],
            [
                'id' => 6,
                'name' => '待付费',
                'default' => 0
            ],
            [
                'id' => 7,
                'name' => '游客',
                'default' => 0
            ],
            [
                'id' => 10,
                'name' => '普通会员',
                'default' => 1
            ],
        ]);
    }
}
