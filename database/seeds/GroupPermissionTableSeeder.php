<?php

use Illuminate\Database\Seeder;
use App\Models\GroupPermission;

class GroupPermissionTableSeeder extends Seeder
{

    protected $permissions = [
        // 用户
        'user.view' => [7, 10],
        'user.edit' => [],
        'viewUserList' => [7, 10],

        // 用户组
        'group.delete' => [],

        // 主题
        'viewThreads' => [7, 10],
        'createThread' => [10],
        'thread.reply' => [10],

        // 回复
        'deletePosts' => [10],
    ];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = collect($this->permissions)->map(function ($value, $key) {
            return collect($value)->map(function($value) use ($key) {
                return [
                    'group_id' => $value,
                    'permission' => $key
                ];
            });
        })->reduce(function ($value, $item) {
            return $item->merge($value);
        });

        $settings = new GroupPermission();
        $settings->truncate();
        $settings->insert($data->toArray());
    }
}
