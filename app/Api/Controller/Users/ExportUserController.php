<?php

namespace App\Api\Controller\Users;

use App\Commands\Users\CreateUsers;
use App\Models\Post;
use App\Models\User;
use App\Notifications\Replied;
use App\Oauth\RefreshToken;
use Carbon\Carbon;
use Discuz\Foundation\Application;
use Discuz\Http\FileResponse;
use Illuminate\Bus\Dispatcher;
use Illuminate\Contracts\Bus\Dispatcher as BusDispatcher;
use Illuminate\Contracts\Routing\ResponseFactory as ResponseFactoryContract;
use Illuminate\Contracts\View\Factory as ViewFactoryContract;
use Illuminate\Routing\ResponseFactory;
use Illuminate\Support\Str;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Http\Message\ResponseInterface;
use App\Exports\UsersExport;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

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
        $filename = $this->app->config('excel.root') . DIRECTORY_SEPARATOR . 'user_excel.xlsx';

        $this->bus->dispatch(
            new UsersExport($filename)
        );

        return new FileResponse($filename);
    }
}