<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Providers;

use App\Models\SessionToken;
use Discuz\Foundation\AbstractServiceProvider;
use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Contracts\Validation\Factory as Validator;

class AppServiceProvider extends AbstractServiceProvider implements DeferrableProvider
{
    /**
     * 注册服务
     *
     * @return void
     */
    public function register()
    {
    }

    /**
     * 引导服务
     *
     * @param Validator $validator
     * @return void
     */
    public function boot(Validator $validator)
    {
        // 自定义验证规则
        $validator->extend('session_token', function ($attribute, $value, $parameters, $validator) {
            // 至少需要一个参数即 scope
            $validator->requireParameterCount(1, $parameters, 'session_token');

            $userId = isset($parameters[1]) ? $parameters[1] : null;

            return SessionToken::check($value, $parameters[0], $userId);
        });
    }
}
