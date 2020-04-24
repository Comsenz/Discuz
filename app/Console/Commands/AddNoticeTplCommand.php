<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Console\Commands;

use Discuz\Console\AbstractCommand;
use App\Models\NotificationTpl as NotificationTplModel;

class AddNoticeTplCommand extends AbstractCommand
{
    protected $signature = 'upgrade:notice';

    protected $description = '更新迭代/新增通知类型数据格式';

    protected $table;

    /**
     * AddNoticeTplCommand constructor.
     *
     * @param string|null $name
     */
    public function __construct(string $name = null)
    {
        parent::__construct($name);
    }

    public function handle()
    {
        $data = $this->getAddData();

        $bar = $this->createProgressBar(count($data));

        $bar->start();

        $this->comment('');

        collect($data)->each(function ($item, $key) use ($bar) {

            $where = [
                'type' => $item['type'],
                'type_name' => $item['type_name']
            ];

            // false 不存在->执行添加
            if (!NotificationTplModel::where($where)->exists()) {
                $tplId = NotificationTplModel::insertGetId($item);
                if ($tplId != $key) {
                    // 删除刚刚插入的数据 终止脚本
                    NotificationTplModel::where(['id' => $tplId])->delete();

                    // TODO 重建自增ID
                    // $notice = new NotificationTplModel;
                    // $this->table = $notice->table;
                    // $autoNum = NotificationTplModel::count() + 1;
                    // $sql = 'alter table ' . $this->table . ' auto_increment = ' . $autoNum;
                    // DB::raw($sql);
                    // 再次插入
                    // $tplId = NotificationTplModel::insertGetId($item);

                    $this->error('');
                    $this->error('脚本已终止,通知表内容有改动,无法对应通知ID 脚本无法执行');

                    return false;
                }

                // 插入成功后输出
                if ($item['type'] == 0) {
                    $info = '插入*系统*通知: ' . ' id:' . $tplId . ' 名称[' . $item['type_name'] . ']';
                    $this->comment($info);
                } elseif ($item['type'] == 1) {
                    $info = '插入[微信]通知: ' . ' id:' . $tplId . ' 名称[' . $item['type_name'] . ']';
                    $this->comment($info);
                }
            }

            $bar->advance();
        });

        $this->question('');
        $this->question('执行新增通知脚本[完成]');

        $bar->finish();
    }

    /**
     * @return array[]
     */
    public function getAddData()
    {
        return [
            25 => [
                'status' => 1,
                'type' => 0,
                'type_name' => '内容回复通知',
                'title' => '内容通知',
                'content' => '',
                'vars' => '',
            ],
            26 => [
                'status' => 1,
                'type' => 0,
                'type_name' => '内容点赞通知',
                'title' => '内容通知',
                'content' => '',
                'vars' => '',
            ],
            27 => [
                'status' => 1,
                'type' => 0,
                'type_name' => '内容打赏通知',
                'title' => '内容通知',
                'content' => '',
                'vars' => '',
            ],
            28 => [
                'status' => 1,
                'type' => 0,
                'type_name' => '内容@通知',
                'title' => '内容通知',
                'content' => '',
                'vars' => '',
            ],
            29 => [
                'status' => 0,
                'type' => 1,
                'type_name' => '内容回复通知',
                'title' => '微信内容通知',
                'content' => NotificationTplModel::getWechatFormat([
                    'first' => '{username}回复了你',
                    'keyword1' => '{content}',
                    'keyword2' => '{subject}',
                    'keyword3' => '{dateline}',
                    'remark' => '点击查看',
                    'redirect_url' => '{redirecturl}',
                ]),
                'vars' => serialize([
                    '{username}' => '回复人用户名',
                    '{content}' => '回复内容',
                    '{subject}' => '原文内容',
                    '{dateline}' => '通知时间',
                    '{redirecturl}' => '跳转地址',
                ])
            ],
            30 => [
                'status' => 0,
                'type' => 1,
                'type_name' => '内容点赞通知',
                'title' => '微信内容通知',
                'content' => NotificationTplModel::getWechatFormat([
                    'first' => '{username}点赞了你',
                    'keyword1' => '{content}',
                    'keyword2' => '{dateline}',
                    'remark' => '点击查看',
                    'redirect_url' => '{redirecturl}',
                ]),
                'vars' => serialize([
                    '{username}' => '点赞人用户名',
                    '{content}' => '点赞内容',
                    '{dateline}' => '通知时间',
                    '{redirecturl}' => '跳转地址',
                ])
            ],
            31 => [
                'status' => 0,
                'type' => 1,
                'type_name' => '内容打赏通知',
                'title' => '微信内容通知',
                'content' => NotificationTplModel::getWechatFormat([
                    'first' => '{username}打赏了你{money}',
                    'keyword1' => '{content}',
                    'keyword2' => '{dateline}',
                    'remark' => '点击查看',
                    'redirect_url' => '{redirecturl}',
                ]),
                'vars' => serialize([
                    '{username}' => '打赏人用户名',
                    '{money}' => '金额',
                    '{content}' => '打赏内容',
                    '{dateline}' => '通知时间',
                    '{redirecturl}' => '跳转地址',
                ])
            ],
            32 => [
                'status' => 0,
                'type' => 1,
                'type_name' => '内容@通知',
                'title' => '微信内容通知',
                'content' => NotificationTplModel::getWechatFormat([
                    'first' => '{username}@了你',
                    'keyword1' => '{content}',
                    'keyword2' => '{dateline}',
                    'remark' => '点击查看',
                    'redirect_url' => '{redirecturl}',
                ]),
                'vars' => serialize([
                    '{username}' => '@人用户名',
                    '{content}' => '@内容',
                    '{dateline}' => '通知时间',
                    '{redirecturl}' => '跳转地址',
                ])
            ],
        ];
    }
}
