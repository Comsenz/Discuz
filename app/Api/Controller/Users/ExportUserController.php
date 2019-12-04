<?php

namespace App\Api\Controller\Users;

use App\Commands\Users\CreateUsers;
use App\Oauth\RefreshToken;
use Discuz\Foundation\Application;
use Discuz\Http\FileResponse;
use Illuminate\Bus\Dispatcher;
use Illuminate\Contracts\Bus\Dispatcher as BusDispatcher;
use Illuminate\Routing\ResponseFactory;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Http\Message\ResponseInterface;
use App\Exports\UsersExport;
use App\Models\User;

class ExportUserController implements RequestHandlerInterface
{
    /**
     * 命令集调用工具类.
     *
     * @var Dispatcher
     */
    protected $bus;

    protected $app;

    public function __construct( BusDispatcher $bus, Application $app)
    {
        $this->bus = $bus;
        $this->app = $app;
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        // 获取当前用户
        $actor = $request->getAttribute('actor');

        $params = $request->getQueryParams();

        $data = $this->data($params);

        $filename = $this->app->config('excel.root') . DIRECTORY_SEPARATOR . 'user_excel.xlsx';

        $this->bus->dispatch(
            new UsersExport($filename, $data)
        );

        return new FileResponse($filename);
    }

    private function data($params = null){

        return User::select('users.id as id', 'users.username',  'user_profiles.sex', 'users.mobile', 'users.adminid', 'users.last_login_ip', 'users.status')
            ->leftJoin('user_profiles', 'users.id', '=', 'user_profiles.user_id')
            ->orderBy('id', 'asc')
            ->get()
            ->each(function ($item, $key) {

                $item->sex = ($item->sex == 1) ? '男' : '女';
                $item->status = ($item->status == 1) ? '正常' : '禁用';

            })
            ->toArray();
    }
}