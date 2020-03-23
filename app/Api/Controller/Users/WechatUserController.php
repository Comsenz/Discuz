<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Api\Controller\Users;

class WechatUserController extends AbstractWechatUserController
{
    protected function getDriver()
    {
        return 'wechat';
    }

    protected function getType()
    {
        return 'mp_openid';
    }
}
