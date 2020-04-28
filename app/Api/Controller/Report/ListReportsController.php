<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Api\Controller\Report;

use App\Api\Serializer\ReportsSerializer;
use App\Models\Report;
use Discuz\Api\Controller\AbstractListController;
use Discuz\Auth\AssertPermissionTrait;
use Illuminate\Support\Arr;
use Psr\Http\Message\ServerRequestInterface;
use Tobscure\JsonApi\Document;

class ListReportsController extends AbstractListController
{
    use AssertPermissionTrait;

    /**
     * {@inheritdoc}
     */
    public $serializer = ReportsSerializer::class;

    /**
     * 传输关系
     *
     * {@inheritdoc}
     */
    public $optionalInclude = [];

    /**
     * 默认关系
     *
     * {@inheritdoc}
     */
    public $include = [
        'user',
    ];

    /**
     * {@inheritdoc}
     *
     * @param ServerRequestInterface $request
     * @param Document $document
     * @return mixed|void
     * @throws \Discuz\Auth\Exception\PermissionDeniedException
     */
    protected function data(ServerRequestInterface $request, Document $document)
    {
        $actor = $request->getAttribute('actor');
        $this->assertPermission($actor->isAdmin());

        $attributes = Arr::get($request->getParsedBody(), 'data.attributes', []);

        $query = Report::query();

        if (Arr::has($attributes, 'start_time')) {
            $query->whereTime('created_at', '>', Arr::get($attributes, 'start_time'));
        }

        if (Arr::has($attributes, 'end_time')) {
            $query->whereTime('created_at', '<', Arr::get($attributes, 'end_time'));
        }

        if (Arr::has($attributes, 'user_id')) {
            $query->where('user_id', '=', Arr::get($attributes, 'user_id'));
        }

        return $query->get();
    }
}
