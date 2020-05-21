<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
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
use App\Models\UserActionLogs;
use App\Models\User;
use App\Notifications\System;
use App\Repositories\UserRepository;
use App\Validators\UserValidator;
use Discuz\Auth\AssertPermissionTrait;
use Discuz\Contracts\Setting\SettingsRepository;
use Discuz\Foundation\EventsDispatchTrait;
use Discuz\SpecialChar\SpecialCharServer;
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
     * @throws \Discuz\Auth\Exception\PermissionDeniedException
     * @throws TranslatorException
     */
    public function __invoke()
    {
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
                    $verifyPwd = $user->checkPassword(Arr::get($attributes, 'password'));
                    if (!$verifyPwd) {
                        throw new TranslatorException('user_update_error', ['not_match_used_password']);
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
            // 传空的话不需要验证
            if (!empty($mobile)) {
                // 判断(除自己)手机号是否已经被绑定
                if (User::where('mobile', $mobile)->whereNotIn('id', [$user->id])->exists()) {
                    throw new \Exception('mobile_is_already_bind');
                }
            }

            $user->changeMobile($mobile);
        }

        if (Arr::has($attributes, 'status')) {
            $this->assertCan($this->actor, 'edit.status', $user);
            $status = Arr::get($attributes, 'status');
            $user->changeStatus($status);

            // 记录用户状态操作日志
            $logMsg = Arr::get($attributes, 'refuse_message', ''); // 拒绝原因
            $actionType = User::enumStatus($status);

            // 审核后系统通知事件
            $this->events->dispatch(new ChangeUserStatus($user, $logMsg));

            UserActionLogs::writeLog($this->actor, $user, $actionType, $logMsg);
        }

        if ($groups = Arr::get($attributes, 'groupId')) {
            $this->assertCan($this->actor, 'edit.group', $user);

            // 获取新用户组 id
            $newGroups = collect($groups)->filter(function ($groupId) {
                return (int) $groupId;
            })->unique()->sort();

            // 获取旧用户组
            $oldGroups = $user->groups->keyBy('id')->sortKeys();

            // 当新旧用户组不一致时，更新用户组并发送通知
            if ($newGroups && $newGroups != $oldGroups->keys()) {
                // 更新用户组
                $user->groups()->sync($newGroups);

                $deleteGroups = array_diff($oldGroups->keys()->toArray(), $newGroups->toArray());
                if ($deleteGroups) {
                    //删除付费用户组
                    $groupsPaid = Group::whereIn('id', $deleteGroups)->where('is_paid', Group::IS_PAID)->pluck('id')->toArray();
                    if (!empty($groupsPaid)) {
                        GroupPaidUser::whereIn('group_id', $groupsPaid)
                            ->where('user_id', $user->id)
                            ->update(['operator_id' => $this->actor->id, 'deleted_at' => Carbon::now(), 'delete_type' => GroupPaidUser::DELETE_TYPE_ADMIN]);
                    }
                }
                $newPaidGroups = $user->groups()->where('is_paid', Group::IS_PAID)->get();
                if ($newPaidGroups->count()) {
                    //新增付费用户组处理
                    foreach ($newPaidGroups as $paidgGroupKey => $paidGroupVal) {
                        $this->events->dispatch(
                            new PaidGroup($paidGroupVal->id,  $user, null, $this->actor)
                        );
                    }
                }

                // 发送系统通知
                $notifyData = [
                    'new' => Group::find($newGroups),
                    'old' => $oldGroups,
                ];

                // 系统通知
                $user->notify(new System(GroupMessage::class, $notifyData));

                // 微信通知
                $user->notify(new System(WechatGroupMessage::class, $notifyData));
            }
        }

        if ($username = Arr::get($attributes, 'username')) {
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

            if (User::where('username', $username)->exists()) {
                throw new TranslatorException('user_username_already_exists');
            }

            $user->changeUsername($username, $isAdmin);
        }

        if (Arr::has($attributes, 'signature')) {
            // 可为空
            $signature = Arr::get($attributes, 'signature');

            // 敏感词校验
            $this->censor->checkText($signature);
            if ($this->censor->isMod) {
                throw new TranslatorException('user_signature_censor_error');
            }

            // 过滤内容
            $signature = $this->specialChar->purify($signature);

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
