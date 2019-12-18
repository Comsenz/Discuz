<?php

/**
 *      Discuz & Tencent Cloud
 *      This is NOT a freeware, use is subject to license terms
 *
 *      Id: UpdateGroupPermissionController.php 28830 2019-10-23 11:06 chenkeke $
 */

namespace App\Api\Controller\GroupPermission;


use App\Api\Serializer\GroupPermissionSerializer;
use App\Models\Permission;
use Discuz\Api\Controller\AbstractListController;
use Discuz\Auth\AssertPermissionTrait;
use Illuminate\Contracts\Bus\Dispatcher;
use Illuminate\Support\Arr;
use Psr\Http\Message\ServerRequestInterface;
use Tobscure\JsonApi\Document;
use Zend\Diactoros\Response\EmptyResponse;

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
     * @return mixed|EmptyResponse
     * @throws \Discuz\Auth\Exception\PermissionDeniedException
     */
    protected function data(ServerRequestInterface $request, Document $document)
    {
        $this->assertCan($request->getAttribute('actor'), 'group.edit');

        $data = $request->getParsedBody()->get('data', []);

        $permissions = Arr::get($data, 'attributes.permissions');
        $groupId = Arr::get($data, 'attributes.groupId');

        Permission::where('group_id', $groupId)->delete();

        Permission::insert(array_map(function ($permission) use ($groupId) {
            return [
                'permission' => $permission,
                'group_id' => $groupId
            ];
        }, $permissions));

        return new EmptyResponse();
    }
}
