<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Listeners\User;

use App\Events\Post\Saving as PostSaving;
use App\Events\Thread\Saving as ThreadSaving;
use Discuz\Auth\AssertPermissionTrait;
use Illuminate\Contracts\Validation\Factory;
use Illuminate\Database\Eloquent\Model;

class CheckPublish
{
    use AssertPermissionTrait;

    /**
     * @var Factory
     */
    protected $validator;

    /**
     * @param Factory $validator
     */
    public function __construct(Factory $validator)
    {
        $this->validator = $validator;
    }

    /**
     * @param Model $event
     */
    public function handle($event)
    {
        // 发布内容是否需要验证
        if ($event instanceof ThreadSaving && ! $event->thread->exists) {
            $needValidate = true;
        } elseif ($event instanceof PostSaving && ! $event->post->exists) {
            $needValidate = true;
        } else {
            $needValidate = false;
        }

        // 验证是否需要实名认证 或 绑定手机号
        if ($needValidate) {
            $rules = [];

            // 发布内容需先实名认证
            if (! $event->actor->isAdmin() && $event->actor->can('publishNeedRealName')) {
                $rules['user'][] = function ($attribute, $value, $fail) use ($event) {
                    if (! $event->actor->realname) {
                        $fail(trans('validation.publishNeedRealName'));
                    }
                };
            }

            // 发布内容需先绑定手机
            if (! $event->actor->isAdmin() && $event->actor->can('publishNeedBindPhone')) {
                $rules['user'][] = function ($attribute, $value, $fail) use ($event) {
                    if (! $event->actor->mobile) {
                        $fail(trans('validation.publishNeedBindPhone'));
                    }
                };
            }

            $this->validator->make(['user' => $event->actor], $rules)->validate();
        }
    }
}
