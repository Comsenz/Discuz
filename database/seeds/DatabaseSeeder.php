<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(SettingsTableSeeder::class);
        $this->call(GroupTableSeeder::class);
        $this->call(GroupPermissionTableSeeder::class);
        $this->call(CategoriesTableSeeder::class);
        $this->call(EmojiTableSeeder::class);
        $this->call(NotificationTplSeeder::class);
    }
}
