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
use App\Models\User;
use App\Models\UserDistribution;
use App\Models\UserFollow;
use App\Repositories\InviteRepository;
use Carbon\Carbon;
use Discuz\Contracts\Setting\SettingsRepository;
use Exception;
use Illuminate\Contracts\Encryption\Encrypter;
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

    /**
     * @var Encrypter
     */
    protected $decrypt;

    public function __construct(InviteRepository $InviteRepository, SettingsRepository $settings, ConnectionInterface $db, Encrypter $decrypt)
    {
        $this->InviteRepository = $InviteRepository;
        $this->settings = $settings;
        $this->db = $db;
        $this->decrypt = $decrypt;
    }

    /**
     * @param Registered $event
     * @throws Exception
     */
    public function handle(Registered $event)
    {
        $code = Arr::get($event->data, 'code', '');

        if (!$code) {
            return;
        }

        // 邀请码 32位长度为管理员邀请
        if ($this->InviteRepository->lengthByAdmin($code)) {
            // 验证code合法性
            $invite = $this->InviteRepository->verifyCode($code);
            if ($invite) {
                $invite->to_user_id = $event->user->id;
                $invite->status = 2;
                $invite->save();
                // 同步用户组
                $event->user->groups()->sync(
                    Group::query()->find($invite->group_id)
                );

                // 修改付费状态
                if ($this->settings->get('site_mode') == 'pay') {
                    $event->user->expired_at = Carbon::now()->addDays($this->settings->get('site_expire'));
                    $event->user->save();
                }
            }
        } else {
            $fromUserId = $code; // 邀请人userID
            $toUserId = $event->user->id; // 受邀人

            // 保持数据一致性
            $this->db->beginTransaction();
            try {
                // 触发互相关注
                UserFollow::query()->create([
                    'from_user_id' => $fromUserId,
                    'to_user_id' => $toUserId,
                    'is_mutual' => 1,
                ]);
                UserFollow::query()->create([
                    'from_user_id' => $toUserId,
                    'to_user_id' => $fromUserId,
                    'is_mutual' => 1,
                ]);

                /**
                 * 刷新关注数和粉丝数
                 */
                /** @var User $fromUser */
                $fromUser = User::query()->find($fromUserId);
                $fromUser->refreshUserFollow();
                $fromUser->refreshUserFans();
                $fromUser->save();
                /** @var User $toUser */
                $toUser = User::query()->find($toUserId);
                $toUser->refreshUserFollow();
                $toUser->refreshUserFans();
                $toUser->save();

                // 多个用户组时 取主用户组(null值无限期)
                $bossGroup = $fromUser->groups()->whereNull('group_user.expiration_time')->first();

                // 建立上下级关系
                UserDistribution::query()->create([
                    'pid' => $fromUserId,
                    'user_id' => $toUserId,
                    'be_scale' => $bossGroup->scale,
                    'level' => 1, // Tag 暂时1级分销
                    'is_subordinate' => $bossGroup->is_subordinate,
                    'is_commission' => $bossGroup->is_commission,
                ]);

                $this->db->commit();
            } catch (Exception $e) {
                $this->db->rollback();
            }
        }

    }


}
