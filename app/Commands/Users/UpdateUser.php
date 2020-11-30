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
use App\Censor\CensorNotPassedException;
use App\Events\Group\PaidGroup;
use App\Events\Users\ChangeUserStatus;
use App\Events\Users\PayPasswordChanged;
use App\Exceptions\TranslatorException;
use App\Models\Group;
use App\Models\GroupPaidUser;
use App\Models\User;
use App\Models\UserActionLogs;
use App\Notifications\Messages\Database\GroupMessage;
use App\Notifications\System;
use App\Repositories\UserRepository;
use App\Validators\UserValidator;
use Discuz\Auth\AssertPermissionTrait;
use Discuz\Auth\Exception\PermissionDeniedException;
use Discuz\Contracts\Setting\SettingsRepository;
use Discuz\Foundation\EventsDispatchTrait;
use Discuz\SpecialChar\SpecialCharServer;
use EasyWeChat\Kernel\Exceptions\InvalidConfigException;
use Exception;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Events\Dispatcher;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class UpdateUser
{
    use AssertPermissionTrait;
    use EventsDispatchTrait;

    /**
     * @var int
     */
    protected $id;

    /**
     * @var Collection|array
     */
    protected $data;

    /**
     * @var User
     */
    protected $actor;

    /**
     * @var UserRepository
     */
    protected $users;

    /**
     * @var UserValidator
     */
    protected $validator;

    /**
     * @var SettingsRepository
     */
    protected $settings;

    /**
     * @var Censor
     */
    protected $censor;

    /**
     * @var SpecialCharServer
     */
    protected $specialChar;

    /**
     * @param int $id
     * @param Collection|array $data
     * @param User $actor
     */
    public function __construct($id, $data, User $actor)
    {
        $this->id = $id;
        $this->data = $data;
        $this->actor = $actor;
    }

    /**
     * @param UserRepository $users
     * @param UserValidator $validator
     * @param Dispatcher $events
     * @param SettingsRepository $settings
     * @param Censor $censor
     * @param SpecialCharServer $specialChar
     * @return mixed
     */
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
     * @return User
     * @throws CensorNotPassedException
     * @throws GuzzleException
     * @throws InvalidConfigException
     * @throws PermissionDeniedException
     * @throws TranslatorException
     * @throws ValidationException
     */
    public function __invoke()
    {
        $user = $this->users->findOrFail($this->id, $this->actor);

        $canEdit = $this->actor->can('edit', $user);
        $isSelf = $this->actor->id === $user->id;

        $attributes = Arr::get($this->data, 'data.attributes');

        // 下列部分方法使用地址引用的方式修改了该值，以便验证用户参数
        $validate = [];

        // 修改用户名
        $this->rename($user, $canEdit, $isSelf, $attributes, $validate);

        // 修改登录密码
        $this->changePassword($user, $canEdit, $isSelf, $attributes, $validate);

        // 修改支付密码
        $this->changePayPassword($user, $isSelf, $attributes, $validate);

        // 修改手机号
        $this->changeMobile($user, $attributes);

        // 修改用户状态
        $this->changeStatus($user, $isSelf, $attributes);

        // 修改用户用户组
        $this->changeGroups($user, $attributes);

        // 修改用户到期时间
        $this->changeExpiredAt($user, $attributes);

        // 修改用户签名
        $this->changeSignature($user, $canEdit, $isSelf, $attributes);

        // 修改注册原因
        $this->changeRegisterReason($user, $attributes, $validate);

        $this->validator->valid($validate);

        $user->save();

        $this->dispatchEventsFor($user, $this->actor);

        return $user;
    }

    /**
     * @param User $user
     * @param bool $canEdit
     * @param bool $isSelf
     * @param array $attributes
     * @param array $validate
     * @return array
     * @throws CensorNotPassedException
     * @throws GuzzleException
     * @throws InvalidConfigException
     * @throws PermissionDeniedException
     * @throws TranslatorException
     */
    protected function rename(User $user, bool $canEdit, bool $isSelf, array $attributes, array &$validate)
    {
        $username = Arr::get($attributes, 'username');

        if (! $username || $username == $user->username) {
            return $validate;
        }

        if (! $isSelf) {
            $this->assertPermission($canEdit);
        }

        // 敏感词校验
        $this->censor->checkText($username, 'username');

        if ($this->censor->isMod) {
            throw new TranslatorException('user_username_censor_error');
        }

        // 过滤内容
        $username = $this->specialChar->purify($username);

        $isAdmin = $this->actor->isAdmin();

        if (! $isAdmin && $user->username_bout >= $this->settings->get('username_bout', 'default', 1)) {
            throw new TranslatorException('user_username_bout_limit_error');
        }

        $user->changeUsername($username, $isAdmin);

        $validate['username'] = $username;

        return $validate;
    }

    /**
     * @param User $user
     * @param bool $canEdit
     * @param bool $isSelf
     * @param array $attributes
     * @param array $validate
     * @return array
     * @throws PermissionDeniedException
     * @throws TranslatorException
     */
    protected function changePassword(User $user, bool $canEdit, bool $isSelf, array $attributes, array &$validate)
    {
        $newPassword = Arr::get($attributes, 'newPassword');

        if (! $newPassword) {
            return $validate;
        }

        if ($isSelf) {
            // 小程序注册的账号密码为空，不验证原密码
            if ($user->password) {
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

            $validate['password_confirmation'] = Arr::get($attributes, 'password_confirmation');
        } else {
            $this->assertPermission($canEdit);
        }

        $user->changePassword($newPassword);

        $validate['password'] = $newPassword;

        return $validate;
    }

    /**
     * @param User $user
     * @param bool $isSelf
     * @param array $attributes
     * @param array $validate
     * @return array
     * @throws TranslatorException
     */
    protected function changePayPassword(User $user, bool $isSelf, array $attributes, array &$validate)
    {
        $payPassword = Arr::get($attributes, 'payPassword');

        if (! $payPassword || ! $isSelf) {
            // 管理员可清除支付密码
            if (
                Arr::get($attributes, 'removePayPassword')
                && ! empty($user->pay_password)
                && $this->actor->isAdmin()
            ) {
                $user->changePayPassword('');
            } else {
                return $validate;
            }
        }

        // 当原支付密码为空时，视为初始化支付密码，不需要验证 pay_password_token
        // 当原支付密码不为空时，则需验证 pay_password_token
        if ($user->pay_password) {
            // 验证新密码与原密码不能相同
            if ($user->checkWalletPayPassword($payPassword)) {
                throw new TranslatorException('user_update_error', ['cannot_use_the_same_password']);
            }

            $this->validator->setUser($user);

            $validate['pay_password_token'] = Arr::get($attributes, 'pay_password_token');
        }

        $user->changePayPassword($payPassword);

        $validate['pay_password'] = $payPassword;
        $validate['pay_password_confirmation'] = Arr::get($attributes, 'pay_password_confirmation');

        // 修改支付密码事件
        $user->raise(new PayPasswordChanged($user));

        return $validate;
    }

    /**
     * @param User $user
     * @param array $attributes
     * @throws PermissionDeniedException
     * @throws Exception
     */
    protected function changeMobile(User $user, array $attributes)
    {
        if (! Arr::has($attributes, 'mobile')) {
            return;
        }

        $this->assertCan($this->actor, 'editMobile', $user);

        $mobile = Arr::get($attributes, 'mobile');

        // 手机号是否已绑定
        if (! empty($mobile)) {
            if (User::query()->where('mobile', $mobile)->where('id', '<>', $user->id)->exists()) {
                throw new Exception('mobile_is_already_bind');
            }
        }

        $user->changeMobile($mobile);
    }

    /**
     * @param User $user
     * @param bool $isSelf
     * @param array $attributes
     * @throws PermissionDeniedException
     */
    protected function changeStatus(User $user, bool $isSelf, array $attributes)
    {
        if ($isSelf || ! Arr::has($attributes, 'status')) {
            return;
        }

        $this->assertCan($this->actor, 'edit.status', $user);

        $status = (int) Arr::get($attributes, 'status');

        $user->changeStatus($status);

        // 审核备注
        $logMsg = Arr::get($attributes, 'refuse_message', '');

        // 审核后系统通知事件
        $this->events->dispatch(new ChangeUserStatus($user, $logMsg));

        // 记录用户状态操作日志
        UserActionLogs::writeLog($this->actor, $user, User::enumStatus($status), $logMsg);
    }

    /**
     * @param User $user
     * @param array $attributes
     * @throws PermissionDeniedException
     */
    protected function changeGroups(User $user, array $attributes)
    {
        $groups = Arr::get($attributes, 'groupId');

        // 用户 id 1 默认是站点管理员，不能修改用户组
        if ($user->id == 1 || ! $groups) {
            return;
        }

        $this->assertCan($this->actor, 'edit.group', $user);

        // 获取新用户组 id
        $newGroups = collect($groups)->filter(function ($groupId) {
            return (int) $groupId;
        })->unique()->sort();

        // 只有管理员用户组可以编辑为管理员或游客
        if (
            ! $this->actor->isAdmin() &&
            ($newGroups->search(Group::ADMINISTRATOR_ID) !== false || $newGroups->search(Group::GUEST_ID) !== false)
        ) {
            throw new PermissionDeniedException();
        }

        // 获取旧用户组
        $oldGroups = $user->groups->keyBy('id')->sortKeys();

        // 当新旧用户组不一致时，更新用户组并发送通知
        if ($newGroups && $newGroups->toArray() != $oldGroups->keys()->toArray()) {
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

            // Tag 发送通知
            $user->notify(new System(GroupMessage::class, $user, $notifyData));
        }
    }

    /**
     * @param User $user
     * @param array $attributes
     * @throws PermissionDeniedException
     */
    protected function changeExpiredAt(User $user, array $attributes)
    {
        $expiredAt = Arr::get($attributes, 'expired_at');

        if (! $expiredAt) {
            return;
        }

        $this->assertAdmin($this->actor);

        $user->expired_at = Carbon::parse($expiredAt);
    }

    /**
     * @param User $user
     * @param bool $canEdit
     * @param bool $isSelf
     * @param array $attributes
     * @throws CensorNotPassedException
     * @throws GuzzleException
     * @throws InvalidConfigException
     * @throws PermissionDeniedException
     * @throws TranslatorException
     */
    protected function changeSignature(User $user, bool $canEdit, bool $isSelf, array $attributes)
    {
        if (! Arr::has($attributes, 'signature')) {
            return;
        }

        if (! $isSelf) {
            $this->assertPermission($canEdit);
        }

        if ($signature = Arr::get($attributes, 'signature', '')) {
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

    /**
     * @param User $user
     * @param array $attributes
     * @param array $validate
     * @return array
     */
    protected function changeRegisterReason(User $user, array $attributes, array &$validate)
    {
        if (Arr::has($attributes, 'register_reason') && $user->status == 2) {
            $registerReason = $this->specialChar->purify(Arr::get($attributes, 'register_reason'));

            $user->register_reason = $registerReason;

            $validate['register_reason'] = $registerReason;
        }

        return $validate;
    }
}
