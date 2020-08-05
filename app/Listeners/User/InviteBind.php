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

namespace App\Listeners\User;

use App\Events\Users\Registered;
use App\Models\Group;
use App\Models\Invite;
use App\Models\UserDistribution;
use App\Models\UserFollow;
use App\Repositories\InviteRepository;
use Carbon\Carbon;
use Discuz\Contracts\Setting\SettingsRepository;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Database\ConnectionInterface;
use Illuminate\Support\Arr;

class InviteBind
{
    protected $InviteRepository;

    protected $settings;

    /**
     * @var ConnectionInterface
     */
    protected $db;

    public function __construct(InviteRepository $InviteRepository, SettingsRepository $settings, ConnectionInterface $db)
    {
        $this->InviteRepository = $InviteRepository;
        $this->settings = $settings;
        $this->db = $db;
    }

    /**
     * @param Registered $event
     * @throws \Exception
     */
    public function handle(Registered $event)
    {
        $code = Arr::get($event->data, 'code', '');

        if ($code) {
            $len = mb_strlen($code, 'utf-8');

            // 邀请码 32位长度为管理员邀请
            if ($len == 32) {
                // 验证code合法性
                $invite = $this->InviteRepository->verifyCode($code);

                if ($invite) {
                    // 保持数据一致性
                    $this->db->beginTransaction();
                    try {
                        $invite->to_user_id = $event->user->id;
                        $invite->status = 2;
                        $invite->save();
                        // 同步用户组
                        $defaultGroup = Group::find($invite->group_id);
                        $event->user->groups()->sync($defaultGroup->id);

                        //修改付费状态
                        if ($this->settings->get('site_mode') == 'pay') {
                            $event->user->expired_at = Carbon::now()->addDays($this->settings->get('site_expire'));
                            $event->user->save();
                        }

                        // 触发互相关注
                        UserFollow::query()->create([
                            'from_user_id' => $invite->user_id,
                            'to_user_id' => $invite->to_user_id,
                            'is_mutual' => 1,
                        ]);
                        UserFollow::query()->create([
                            'from_user_id' => $invite->to_user_id,
                            'to_user_id' => $invite->user_id,
                            'is_mutual' => 1,
                        ]);

                        // 建立上下级关系
                        UserDistribution::query()->create([
                            'pid' => $invite->user_id,
                            'user_id' => $event->user->id,
                            'invites_code' => $code,
                            'be_scale' => $invite->group->scale,
                            'level' => 1,
                        ]);

                        $this->db->commit();
                    } catch (\Exception $e) {
                        $this->db->rollback();
                        throw new \Exception($e->getMessage());
                    }
                }
            } else {
                $encrypter = app('encrypter');

                try {
                    $user_id = $encrypter->decryptString($code);
                } catch (DecryptException $e) {
//                    throw new DecryptException();
                    // 邀请码解密失败后普通注册
                    return;
                }
                // 生成记录
                Invite::insert([
                    'group_id' => 0,
                    'code' => $code,
                    'user_id' => $user_id,
                    'to_user_id' => $event->user->id,
                    'created_at' => Carbon::now()->toDate(),
                    'updated_at' => Carbon::now()->toDate(),
                ]);
            }
        }
    }
}
