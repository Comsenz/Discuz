<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Api\Controller\Report;

use App\Api\Serializer\ReportsSerializer;
use App\Commands\Report\BatchEditReport;
use Discuz\Api\Controller\AbstractListController;
use Discuz\Auth\AssertPermissionTrait;
use Illuminate\Contracts\Bus\Dispatcher;
use Psr\Http\Message\ServerRequestInterface;
use Tobscure\JsonApi\Document;

class BatchUpdateReportsController extends AbstractListController
{
    use AssertPermissionTrait;

    /**
     * {@inheritdoc}
     */
    public $serializer = ReportsSerializer::class;

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
     * {@inheritdoc}
     *
     * @param ServerRequestInterface $request
     * @param Document $document
     * @return array|mixed
     * @throws \Discuz\Auth\Exception\PermissionDeniedException
     */
    protected function data(ServerRequestInterface $request, Document $document)
    {
        $actor = $request->getAttribute('actor');
        $this->assertPermission($actor->isAdmin());

        $data = $request->getParsedBody()->get('data', []);

        $result = ['data' => [], 'meta' => []];

        collect($data)->each(function ($item) use ($actor) {
            try {
                $result['data'][] = $this->bus->dispatch(
                    new BatchEditReport($actor, $item)
                );
            } catch (\Exception $e) {
                $result['meta'][] = ['id' => $item, 'message' => $e->getMessage()];
            }
        });

        $document->setMeta($result['meta']);

        return $result['data'];
    }
}
