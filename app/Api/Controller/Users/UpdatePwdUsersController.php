<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Api\Controller\Users;

use App\Api\Serializer\UserSerializer;
use App\Models\User;
use Discuz\Api\Controller\AbstractResourceController;
use Psr\Http\Message\ServerRequestInterface;
use Tobscure\JsonApi\Document;
use Illuminate\Support\Arr;

class updatePwdUsersController extends AbstractResourceController
{
    public $serializer = UserSerializer::class;

    /**
     * @param ServerRequestInterface $request
     * @param Document $document
     */
    protected function data(ServerRequestInterface $request, Document $document)
    {
        // TODO: Implement data() method.

        $data = $request->getParsedBody();
        //验证    密码格式

        //验证权限
        if (false) {
            $objuser = User::findOrFail(Arr::get($data, 'id'));

            $objuser->password = User::setUserPasswordAttr('123456');

            $objuser->save();
            return '重置成功';
        } else {
            $user=User::where('id', Arr::get($data, 'id'))->first();
            $userpwd=$user->password;
            if (User::unsetUserPasswordAttr(Arr::get($data, 'password'), $userpwd)) {
                $objuser = User::findOrFail(Arr::get($data, 'id'));

                $objuser->password = User::setUserPasswordAttr(Arr::get($request->getParsedBody(), 'newpassword'));

                $objuser->save();
                return '修改成功';
            } else {
                return '原密码错误';
            }
        }
    }
}
