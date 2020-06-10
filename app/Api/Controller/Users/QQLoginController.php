<?php
/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Api\Controller\Users;


use App\Models\SessionToken;
use Illuminate\Support\Arr;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class QQLoginController extends AbstractQQLoginController
{
    public $type = 'qq';

    /**
     * 授权处理
     * @inheritDoc
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $queryParams = $request->getQueryParams();
        //调起授权页面展示样式
        $display = Arr::get($queryParams, 'display');
        $request = $request->withAttribute('session', new SessionToken())->withAttribute('display', $display);
        //token不存在重新授权
        $code = Arr::get($queryParams, 'code');
        if(empty($code)) {
            //code不存在跳转至code获取url(回调地址与token获取回调地址相同)
            $this->socialite->setRequest($request);
            return $this->socialite->driver($this->type)->redirect();
        }
        $request = $request->withAttribute('sessionId', Arr::get($queryParams, 'sessionId'))
            ->withAttribute('state', Arr::get($queryParams, 'state'))->withAttribute('code',$code);
        //code存在跳转至accessToken获取url
        $this->socialite->setRequest($request);
        $accessTokenInfo = $this->socialite->driver($this->type)->getAccessToken();
        $accessToken = $accessTokenInfo['access_token'];
        //token获取后带上token跳转至用户信息获取接口
        return $this->socialite->driver($this->type)->redirectUser($accessToken);

    }
}
