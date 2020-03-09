<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Api\Controller\Ucenter;

use App\Ucenter\Authcode;
use App\Ucenter\Client;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Laminas\Diactoros\Response\HtmlResponse;

class UcenterController implements RequestHandlerInterface
{
    const API_RETURN_SUCCEED = 1;

    const API_RETURN_FAILED = -1;

    const UC_KEY = '123';  //后期取配置

    /**
     * @inheritDoc
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $ucenterClient = new Client();
        $ucenterClient->setRequest($request);

        dd($ucenterClient->uc_user_login('admin', 'admin'));


        $content = '';
        $code = Arr::get($request->getQueryParams(), 'code');

        $get = $post = [];
        parse_str(Authcode::decode($code, self::UC_KEY), $get);

        if (Carbon::now()->timestamp - Arr::get($get, 'time') > 3600) {
            $content = 'Authracation has expiried';
        }
        if (empty($get)) {
            $content = 'Invalid Request';
        }

        if (in_array(Arr::get($get, 'action'), ['test', 'deleteuser', 'renameuser', 'gettag', 'synlogin', 'synlogout', 'updatepw', 'updatebadwords', 'updatehosts', 'updateapps', 'updateclient', 'updatecredit', 'getcredit', 'getcreditsettings', 'updatecreditsettings', 'addfeed'])) {
            $content = call_user_func([$this, Arr::get($get, 'action')], $get, $post);
        } else {
            $content = self::API_RETURN_FAILED;
        }
        return new HtmlResponse((string)$content);
    }

    protected function test($get, $post)
    {
        return self::API_RETURN_SUCCEED;
    }
}
