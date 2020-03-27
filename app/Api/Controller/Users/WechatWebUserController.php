<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Api\Controller\Users;

class WechatWebUserController extends AbstractWechatUserController
{
    protected function getDriver()
    {
        return 'wechatweb';
    }

    protected function getType()
    {
        return 'dev_openid';
    }
}
