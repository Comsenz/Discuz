<?php
declare(strict_types=1);

/**
 *      Discuz & Tencent Cloud
 *      This is NOT a freeware, use is subject to license terms
 *
 *      Id: ResourceGroupsController.php 28830 2019-10-23 16:29 chenkeke $
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
    public $include = ['permission'];

    /**
     * {@inheritdoc}
     */
    protected function data(ServerRequestInterface $request, Document $document)
    {
        // 获取当前用户
        $actor = $request->getAttribute('actor');

        // 获取请求的参数
        $inputs = $request->getQueryParams();

        $query = GroupRepository::query();

        return $query->where('id', $inputs['id'])->first();
    }
}
