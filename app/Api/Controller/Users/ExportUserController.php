<?php

/**
 * Copyright (C) 2020 Tencent Cloud.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

namespace App\Api\Controller\Users;

use App\Exports\UsersExport;
use App\Models\User;
use App\Traits\UserTrait;
use Discuz\Auth\AssertPermissionTrait;
use Discuz\Foundation\Application;
use Discuz\Http\DiscuzResponseFactory;
use Illuminate\Bus\Dispatcher;
use Illuminate\Contracts\Bus\Dispatcher as BusDispatcher;
use Illuminate\Support\Arr;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Tobscure\JsonApi\Parameters;

class ExportUserController implements RequestHandlerInterface
{
    use AssertPermissionTrait;
    use UserTrait;

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

        $filter = new Parameters($request->getQueryParams());
        $filters = $filter->getFilter() ?: [];

        $ids = Arr::get($request->getQueryParams(), 'ids', '');
        $filters['id'] = $ids;

        $data = $this->data($filters);

        $filename = $this->app->config('excel.root') . DIRECTORY_SEPARATOR . 'user_excel.xlsx';

        //TODO 判断满足条件的excel是否存在,if exist 直接返回;
        $this->bus->dispatch(
            new UsersExport($filename, $data)
        );

        return DiscuzResponseFactory::FileResponse($filename);
    }

    private function data($filters)
    {
        $userField = [
            'id',
            'username',
            'mobile',
            'login_at',
            'last_login_ip',
            'last_login_port',
            'register_ip',
            'register_port',
            'users.status',
            'users.created_at',
            'users.updated_at',
        ];
        $wechatField = [
            'user_id',
            'nickname',
            'sex',
            'mp_openid',
            'unionid',
        ];

        $columnMap = [
            'id',
            'username',
            'mobile',
            'status',
            'sex',
            'groups',
            'mp_openid',
            'unionid',
            'nickname',
            'created_at',
            'register_ip',
            'register_port',
            'login_at',
            'last_login_ip',
            'last_login_port',
        ];

        $query = User::query();

        // 拼接条件
        $this->applyFilters($query, $filters);

        $users = $query->with(['wechat' => function ($query) use ($wechatField) {
            $query->select($wechatField);
        }, 'groups' => function ($query) {
            $query->select(['id', 'user_id', 'name']);
        }])->get($userField);

        $sex = ['', '男', '女'];

        return $users->map(function (User $user) use ($columnMap, $sex) {
            $user->sex = $sex[$user->wechat ? $user->wechat->sex : 0];
            if ($user->status == 0) {
                $user->status = '正常';
            } elseif ($user->status == 1) {
                $user->status = '禁用';
            } else {
                $user->status = '审核';
            }
            if (!is_null($user->groups)) {
                $user->groups = $user->groups->pluck('name')->implode(',');
            }
            if (!is_null($user->wechat)) {
                $user->nickname = $user->wechat->nickname;
                $user->mp_openid = $user->wechat->mp_openid;
                $user->unionid = $user->wechat->unionid;
            }
            $user->unsetRelation('wechat');
            $user->unsetRelation('groups');
            return $user->only($columnMap);
        })->toArray();
    }
}
