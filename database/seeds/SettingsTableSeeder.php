<?php

use Illuminate\Database\Seeder;
use App\Models\Setting;

class SettingsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $settings = new Setting();
        $settings->truncate();
        $settings->insert([
            [
                'key' => 'site_close',          // 站点开关：0 开启站点，1 关闭站点
                'value' => '0',                 // 默认开启
            ],[
                'key' => 'site_mode',           // 站点模式：public 公开，pay 付费
                'value' => 'public'             // 默认公开
            ],[
                'key' => 'register_close',      // 注册开关：0 禁止，1 允许
                'value' => '1'                  // 默认允许
            ],[
                'key' => 'qcloud_sms',          // 腾讯云短信开关：0 关闭，1 开启
                'value' => '0',                 // 默认关闭
                'tag' => 'qcloud'
            ],[
                'key' => 'site_author',         // 站长用户ID 1 管理员
                'value' => '1'                  // 默认用户1
            ]
        ]);
    }
}
