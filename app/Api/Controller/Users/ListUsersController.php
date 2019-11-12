<?php
declare(strict_types=1);

namespace App\Api\Controller\Users;


use App\Api\Serializer\UserSerializer;
use App\Repositories\UserRepository;
use App\Searchs\Users\UserSearch;
use Discuz\Api\Controller\AbstractListController;
use Psr\Http\Message\ServerRequestInterface;
use Tobscure\JsonApi\Document;
use Illuminate\Support\Arr;

class ListUsersController extends AbstractListController
{
    public $serializer = UserSerializer::class;

    protected function data(ServerRequestInterface $request, Document $document)
    {
        // 获取当前用户
        $actor = $request->getAttribute('actor');

        // 获取请求的参数
        $inputs = $request->getQueryParams();

        // 获取请求的IP
        $ipAddress = Arr::get($request->getServerParams(), 'REMOTE_ADDR', '127.0.0.1');

        $data = $this->searcher->apply(
            new UserSearch($actor, $inputs, UserRepository::query())
        )->search()->getMultiple();

        return $data;

    }
}
