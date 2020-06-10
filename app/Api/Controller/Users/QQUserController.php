<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */
namespace App\Api\Controller\Users;


class QQUserController extends AbstractQQUserController
{
    protected function getDriver()
    {
        return 'qq';
    }

}
