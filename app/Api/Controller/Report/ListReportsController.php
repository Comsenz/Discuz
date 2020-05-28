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
use Discuz\Http\UrlGenerator;
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
     * @var UrlGenerator
     */
    protected $url;

    /**
     * @param UrlGenerator $url
     */
    public function __construct(UrlGenerator $url)
    {
        $this->url = $url;
    }

    /**
     * {@inheritdoc}
     *
     * @param ServerRequestInterface $request
     * @param Document $document
     * @return \Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection|mixed
     * @throws \Discuz\Auth\Exception\PermissionDeniedException
     * @throws \Tobscure\JsonApi\Exception\InvalidParameterException
     */
    protected function data(ServerRequestInterface $request, Document $document)
    {
        $actor = $request->getAttribute('actor');
        $this->assertPermission($actor->isAdmin());

        $filter = $this->extractFilter($request);

        $limit = $this->extractLimit($request);
        $offset = $this->extractOffset($request);
        $include = $this->extractInclude($request);

        $query = Report::query();

        if (Arr::has($filter, 'start_time')) {
            $query->whereTime('created_at', '>', Arr::get($filter, 'start_time'));
        }

        if (Arr::has($filter, 'end_time')) {
            $query->whereTime('created_at', '<', Arr::get($filter, 'end_time'));
        }

        if (Arr::has($filter, 'user_id')) {
            $query->where('user_id', '=', Arr::get($filter, 'user_id'));
        }

        $reportCount = $limit > 0 ? $query->count() : null;

        $query->skip($offset)->take($limit)->orderBy('created_at', 'desc');

        $document->addPaginationLinks(
            $this->url->route('reports.list'),
            $request->getQueryParams(),
            $offset,
            $limit,
            $reportCount
        );

        $data = $query->get();

        $data->loadMissing($include);

        $document->setMeta([
            'total' => $reportCount,
            'pageCount' => ceil($reportCount / $limit),
        ]);

        return $data;
    }
}
