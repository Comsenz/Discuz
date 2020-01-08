<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Api\Controller\Users;

class WechatLoginController extends AbstractWechatLoginController {

    protected function getDriver()
    {
        return 'wechat';
    }

    protected function getType()
    {
        return 'mp_openid';
    }
}
