<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Api\Controller\Group;

use App\Api\Serializer\GroupSerializer;
use App\Models\Group;
use Discuz\Api\Controller\AbstractListController;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Arr;
use Psr\Http\Message\ServerRequestInterface;
use Tobscure\JsonApi\Document;

class ListGroupsController extends AbstractListController
{
    /**
     * {@inheritdoc}
     */
    public $serializer = GroupSerializer::class;

    /**
     * {@inheritdoc}
     */
    public $optionalInclude = ['permission'];

    /**
     * {@inheritdoc}
     */
    protected function data(ServerRequestInterface $request, Document $document)
    {
        $include = $this->extractInclude($request);
        $filter = $this->extractFilter($request);

        $isDefault = (bool) Arr::get($filter, 'isDefault', false);
        $type = Arr::get($filter, 'type', '');

        $groups = Group::query()
            ->where('id', '<>', Group::UNPAID)
            ->when($isDefault, function (Builder $query) {
                return $query->where('default', true);
            })
            ->when($type === 'invite', function (Builder $query) use ($include) {
                // 邀请用户组关联权限不返回 分类下权限
                if (in_array('permission', $include)) {
                    $query->with(['permission' => function ($query) {
                        $query->where('permission', 'not like', 'category%');
                    }]);
                }

                // 不返回游客用户组
                // no guest
                return $query->where('id', '<>', Group::GUEST_ID);
                // or more
                // return $query->whereNotIn('id', [Group::GUEST_ID]);
            });

        return $groups->get()->loadMissing($include);
    }
}
