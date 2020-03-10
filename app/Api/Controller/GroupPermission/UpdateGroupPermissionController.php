<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Api\Controller\GroupPermission;

use App\Api\Serializer\GroupPermissionSerializer;
use App\Models\Permission;
use Discuz\Api\Controller\AbstractListController;
use Discuz\Auth\AssertPermissionTrait;
use Discuz\Auth\Exception\PermissionDeniedException;
use Discuz\Http\DiscuzResponseFactory;
use Illuminate\Contracts\Bus\Dispatcher;
use Illuminate\Support\Arr;
use Psr\Http\Message\ServerRequestInterface;
use Tobscure\JsonApi\Document;

class UpdateGroupPermissionController extends AbstractListController
{
    use AssertPermissionTrait;

    public $serializer = GroupPermissionSerializer::class;

    /**
     * @var Dispatcher
     */
    protected $bus;

    /**
     * @param Dispatcher $bus
     */
    public function __construct(Dispatcher $bus)
    {
        $this->bus = $bus;
    }

    /**
     * @param ServerRequestInterface $request
     * @param Document $document
     * @return mixed
     * @throws PermissionDeniedException
     */
    protected function data(ServerRequestInterface $request, Document $document)
    {
        $this->assertCan($request->getAttribute('actor'), 'group.edit');

        $data = $request->getParsedBody()->get('data', []);

        $groupId = Arr::get($data, 'attributes.groupId');
        $permissions = collect(Arr::get($data, 'attributes.permissions'));

        // 默认权限：收藏、点赞、打赏
        $defaultPermission = [
            'thread.favorite',
            'thread.likePosts',
            'order.create'
        ];

        // 合并默认权限，去空，去重，转换格式
        $permissions = $permissions->merge($defaultPermission)
            ->filter()
            ->unique()
            ->map(function ($item) use ($groupId) {
                return [
                    'group_id' => $groupId,
                    'permission' => $item,
                ];
            })
            ->toArray();

        Permission::where('group_id', $groupId)->delete();

        Permission::insert($permissions);

        return DiscuzResponseFactory::EmptyResponse();
    }
}
