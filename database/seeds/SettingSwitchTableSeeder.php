<?php

use Illuminate\Database\Seeder;
use App\Models\Setting;
class SettingSwitchTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $setting = new Setting();
        $setting->insert([
            'key' => 'site_manage',          // 站点开关：0 开启站点，1 关闭站点
            'value' => '[{"key":1,"desc":"PC端","value":true},{"key":2,"desc":"H5端","value":true},{"key":3,"desc":"小程序端","value":true}]',                 // 默认开启
            'tag' => 'default',
        ]);
    }
}
