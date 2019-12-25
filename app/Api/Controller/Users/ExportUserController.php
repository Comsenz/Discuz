<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Api\Controller\Users;

use Discuz\Auth\AssertPermissionTrait;
use Discuz\Foundation\Application;
use Discuz\Http\FileResponse;
use Illuminate\Bus\Dispatcher;
use Illuminate\Contracts\Bus\Dispatcher as BusDispatcher;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Http\Message\ResponseInterface;
use App\Exports\UsersExport;
use App\Models\User;

class ExportUserController implements RequestHandlerInterface
{

    use AssertPermissionTrait;

    /**
     * 命令集调用工具类.
     *
     * @var Dispatcher
     */
    protected $bus;

    protected $app;

    public function __construct(BusDispatcher $bus, Application $app)
    {
        $this->bus = $bus;
        $this->app = $app;
    }

    /**
     * @param ServerRequestInterface $request
     * @return ResponseInterface
     * @throws \Discuz\Auth\Exception\PermissionDeniedException
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $this->assertAdmin($request->getAttribute('actor'));

        $params = $request->getQueryParams();

        $data = $this->data($params);

        $filename = $this->app->config('excel.root') . DIRECTORY_SEPARATOR . 'user_excel.xlsx';

        //TODO 判断满足条件的excel是否存在,if exist 直接返回;

        $this->bus->dispatch(
            new UsersExport($filename, $data)
        );

        return new FileResponse($filename);
    }

    private function data($params = null)
    {
        return User::select('users.id as id', 'users.username', 'users.mobile', 'user_wechats.nickname', 'user_wechats.unionid', 'users.last_login_ip', 'users.created_at', 'users.status')
            ->leftJoin('user_wechats', 'users.id', '=', 'user_wechats.user_id')
            ->orderBy('id', 'asc')
            ->get()
            ->each(function ($item, $key) {
                $item->sex = ($item->sex == 1) ? '男' : '女';
                $item->status = ($item->status == 1) ? '正常' : '禁用';
            })
            ->toArray();
    }

    private function createFilename($params)
    {
    }
}
