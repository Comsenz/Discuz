<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Api\Controller\Group;

use App\Api\Serializer\GroupSerializer;
use App\Repositories\GroupRepository;
use Discuz\Api\Controller\AbstractResourceController;
use Psr\Http\Message\ServerRequestInterface;
use Tobscure\JsonApi\Document;

class ResourceGroupsController extends AbstractResourceController
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
        $inputs = $request->getQueryParams();
        $include = $this->extractInclude($request);

        $query = GroupRepository::query();

        if (in_array('permission', $include)) {
            $query->with(['permission' => function ($query) {
                $query->where('permission', 'not like', 'category%');
            }]);
        }

        return $query->where('id', $inputs['id'])->first();
    }
}
