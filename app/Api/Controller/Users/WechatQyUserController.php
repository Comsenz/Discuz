<?php


namespace App\Api\Controller\Users;

class WechatQyUserController extends AbstractWechatQyUserController
{


    protected function getDriver()
    {
        return 'wechatqy';
    }

    protected function getType()
    {
        return 'qy_userid';
    }
}
