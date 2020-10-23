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

namespace App\Commands\Users;

use App\Censor\Censor;
use App\Events\Group\PaidGroup;
use App\Events\Users\ChangeUserStatus;
use App\Events\Users\PayPasswordChanged;
use App\Exceptions\TranslatorException;
use App\MessageTemplate\GroupMessage;
use App\MessageTemplate\Wechat\WechatGroupMessage;
use App\Models\Group;
use App\Models\GroupPaidUser;
use App\Models\User;
use App\Models\UserActionLogs;
use App\Models\UserWechat;
use App\Notifications\System;
use App\Repositories\UserRepository;
use App\Validators\UserValidator;
use Discuz\Auth\AssertPermissionTrait;
use Discuz\Auth\Exception\PermissionDeniedException;
use Discuz\Contracts\Setting\SettingsRepository;
use Discuz\Foundation\EventsDispatchTrait;
use Discuz\SpecialChar\SpecialCharServer;
use Exception;
use Illuminate\Events\Dispatcher;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

class UpdateUser
{
    use AssertPermissionTrait;
    use EventsDispatchTrait;

    protected $id;

    protected $data;

    protected $actor;

    protected $users;

    protected $validator;

    protected $settings;

    protected $censor;

    protected $specialChar;

    public function __construct($id, $data, User $actor)
    {
        $this->id = $id;
        $this->data = $data;
        $this->actor = $actor;
    }

    public function handle(UserRepository $users, UserValidator $validator, Dispatcher $events, SettingsRepository $settings, Censor $censor, SpecialCharServer $specialChar)
    {
        $this->users = $users;
        $this->validator = $validator;
        $this->events = $events;
        $this->settings = $settings;
        $this->censor = $censor;
        $this->specialChar = $specialChar;

        return call_user_func([$this, '__invoke']);
    }

    /**
     * @return mixed
     * @throws PermissionDeniedException
     * @throws TranslatorException
     * @throws Exception
     */
    public function __invoke()
    {
        /** @var User $user */
        $user = $this->users->findOrFail($this->id, $this->actor);

        $isSelf = $this->actor->id === $user->id;

        if (!$isSelf) {
            $this->assertCan($this->actor, 'edit', $user);
        }

        $validator = [];

        $attributes = Arr::get($this->data, 'data.attributes');

        // 修改登录密码
        if ($newPassword = Arr::get($attributes, 'newPassword')) {
            if ($isSelf) {
                //小程序注册的账号密码为空，不验证旧密码
                if ($this->actor->password != '') {
                    // 验证原密码
                    if (! $user->checkPassword(Arr::get($attributes, 'password'))) {
                        throw new TranslatorException('user_update_error', ['not_match_used_password']);
                    }

                    // 验证新密码与原密码不能相同
                    if ($user->checkPassword($newPassword)) {
                        throw new TranslatorException('user_update_error', ['cannot_use_the_same_password']);
                    }
                }

                $this->validator->setUser($user);
                $validator['password_confirmation'] = Arr::get($attributes, 'password_confirmation');
            }
            $user->changePassword($newPassword);
            $validator['password'] = $newPassword;
        }

        // 修改支付密码
        if ($payPassword = Arr::get($attributes, 'payPassword')) {
            if ($isSelf) {
                // 当原支付密码为空时，视为初始化支付密码，不需要验证 pay_password_token
                // 当原支付密码不为空时，则需验证 pay_password_token
                if ($user->pay_password) {
                    // 验证新密码与原密码不能相同
                    if ($user->checkWalletPayPassword($payPassword)) {
                        throw new TranslatorException('user_update_error', ['cannot_use_the_same_password']);
                    }

                    $this->validator->setUser($user);
                    $validator['pay_password_token'] = Arr::get($attributes, 'pay_password_token');
                }

                $validator['pay_password'] = $payPassword;
                $validator['pay_password_confirmation'] = Arr::get($attributes, 'pay_password_confirmation');

                $user->changePayPassword($payPassword);

                // 修改支付密码事件
                $user->raise(new PayPasswordChanged($user));
            }
        } elseif ($removePayPassword = Arr::get($attributes, 'removePayPassword')) {
            // 清除支付密码，管理员操作，不能与设置支付密码同时进行
            if (! empty($user->pay_password) && $this->actor->isAdmin()) {
                $user->pay_password = '';
            }
        }

        if (Arr::has($attributes, 'mobile')) {
            $this->assertCan($this->actor, 'edit.mobile', $user);

            $mobile = Arr::get($attributes, 'mobile');

            // 手机号是否已绑定
            if (! empty($mobile)) {
                if (User::query()->where('mobile', $mobile)->where('id', '<>', $user->id)->exists()) {
                    throw new Exception('mobile_is_already_bind');
                }
            }

            $user->changeMobile($mobile);
        }

        if ($this->actor->id != $user->id && Arr::has($attributes, 'status')) {
            $this->assertCan($this->actor, 'edit.status', $user);
            $status = Arr::get($attributes, 'status');
            $user->changeStatus($status);

            // 禁用、拒审时清理微信绑定关系
            if ($status == 1 || $status == 3) {
                UserWechat::query()->where('user_id', $user->id)->delete();
            }

            // 记录用户状态操作日志
            $logMsg = Arr::get($attributes, 'refuse_message', ''); // 拒绝原因
            $actionType = User::enumStatus($status);

            // 审核后系统通知事件
            $this->events->dispatch(new ChangeUserStatus($user, $logMsg));

            UserActionLogs::writeLog($this->actor, $user, $actionType, $logMsg);
        }

        //修改注册原因
        if (Arr::has($attributes, 'register_reason') && $user->status == 2) {
            $registerReason = Arr::get($attributes, 'register_reason');
            $registerReason = $this->specialChar->purify($registerReason);
            $user->register_reason = $registerReason;

            $validator['register_reason'] = $registerReason;
        }

        if ($expiredAt = Arr::get($this->data, 'data.attributes.expired_at')) {
            $this->assertAdmin($this->actor);

            $user->expired_at = Carbon::parse($expiredAt);
        }

        if ($groups = Arr::get($attributes, 'groupId')) {
            // 判断是否是修改admin
            if ($user->id != 1) {
                $this->assertCan($this->actor, 'edit.group', $user);

                // 获取新用户组 id
                $newGroups = collect($groups)->filter(function ($groupId) {
                    return (int) $groupId;
                })->unique()->sort();
                // 只有管理员用户组可以编辑为管理员或游客
                if (
                    !$this->actor->isAdmin() &&
                    ($newGroups->search(1) !== false || $newGroups->search(7) !== false)
                ) {
                    throw new PermissionDeniedException();
                }

                // 获取旧用户组
                $oldGroups = $user->groups->keyBy('id')->sortKeys();

                // 当新旧用户组不一致时，更新用户组并发送通知
                if ($newGroups && $newGroups != $oldGroups->keys()) {
                    // 更新用户组
                    $user->groups()->sync($newGroups);

                    $deleteGroups = array_diff($oldGroups->keys()->toArray(), $newGroups->toArray());
                    if ($deleteGroups) {
                        //删除付费用户组
                        $groupsPaid = Group::query()->whereIn('id', $deleteGroups)->where('is_paid', Group::IS_PAID)->pluck('id')->toArray();
                        if (!empty($groupsPaid)) {
                            GroupPaidUser::query()->whereIn('group_id', $groupsPaid)
                                ->where('user_id', $user->id)
                                ->update(['operator_id' => $this->actor->id, 'deleted_at' => Carbon::now(), 'delete_type' => GroupPaidUser::DELETE_TYPE_ADMIN]);
                        }
                    }
                    $newPaidGroups = $user->groups()->where('is_paid', Group::IS_PAID)->get();
                    if ($newPaidGroups->count()) {
                        //新增付费用户组处理
                        foreach ($newPaidGroups as $paidGroupVal) {
                            $this->events->dispatch(
                                new PaidGroup($paidGroupVal->id, $user, null, $this->actor)
                            );
                        }
                    }

                    // 发送系统通知
                    $notifyData = [
                        'new' => Group::query()->find($newGroups),
                        'old' => $oldGroups,
                    ];

                    // 系统通知
                    $user->notify(new System(GroupMessage::class, $notifyData));

                    // 微信通知
                    $user->notify(new System(WechatGroupMessage::class, $notifyData));
                }
            }
        }

        if (($username = Arr::get($attributes, 'username')) && $username != $user->username) {
            $validator['username'] = $username;

            // 敏感词校验
            $this->censor->checkText($username, 'username');
            if ($this->censor->isMod) {
                throw new TranslatorException('user_username_censor_error');
            }

            // 过滤内容
            $username = $this->specialChar->purify($username);

            $isAdmin = $this->actor->isAdmin();
            if (!$isAdmin) {
                if ($user->username_bout >= $this->settings->get('username_bout', 'default', 1)) {
                    throw new TranslatorException('user_username_bout_limit_error');
                }
            }

            $user->changeUsername($username, $isAdmin);
        }

        if (Arr::has($attributes, 'signature')) {
            if ($signature = Arr::get($attributes, 'signature')) {
                // 敏感词校验
                $this->censor->checkText($signature, 'signature');

                // 过滤内容
                $signature = $this->specialChar->purify($signature);
            }

            if (Str::of($signature)->length() > 140) {
                throw new TranslatorException('user_signature_limit_error');
            }

            $user->changeSignature($signature);
        }

        $this->validator->valid($validator);

        $user->save();

        $this->dispatchEventsFor($user, $this->actor);

        return $user;
    }
}
