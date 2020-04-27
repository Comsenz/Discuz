<?php


namespace App\Api\Controller\Reports;

use App\Api\Serializer\ReportsSerializer;
use App\Commands\Report\CreateReport;
use Discuz\Api\Controller\AbstractCreateController;
use Illuminate\Contracts\Bus\Dispatcher;
use Psr\Http\Message\ServerRequestInterface;
use Tobscure\JsonApi\Document;

class CreateReportsController extends AbstractCreateController
{
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

    protected function data(ServerRequestInterface $request, Document $document)
    {
        $actor = $request->getAttribute('actor');
        $data = $request->getParsedBody()->get('data', []);

        return $this->bus->dispatch(
            new CreateReport($actor, $data)
        );
    }
}
