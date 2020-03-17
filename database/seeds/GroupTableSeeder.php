<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
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
