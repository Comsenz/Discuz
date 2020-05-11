<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Api\Controller\Group;

use App\Api\Serializer\GroupSerializer;
use App\Models\Group;
use Discuz\Api\Controller\AbstractListController;
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
        // 默认用户组
        $isDefault = (bool) $this->extractFilter($request);

        $include = $this->extractInclude($request);

        $groups = Group::query()
            ->where('id', '<>', Group::UNPAID)
            ->when($isDefault, function ($query, $isDefault) {
                return $query->where('default', $isDefault);
            });

        // 判断如果是邀请页使用数据 则 不返回游客
        if (Arr::get($request->getQueryParams(), 'type') == 'invite') {
            $groups->whereNotIn('id', [Group::GUEST_ID]);
        }

        return $groups->get()->load($include);
    }
}
