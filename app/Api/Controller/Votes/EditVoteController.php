<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Api\Controller\Votes;

use App\Api\Serializer\VoteSerializer;
use App\Commands\Vote\CreateVote;
use App\Commands\Vote\EditVote;
use Discuz\Api\Controller\AbstractCreateController;
use Illuminate\Contracts\Bus\Dispatcher;
use Illuminate\Support\Arr;
use Psr\Http\Message\ServerRequestInterface;
use Tobscure\JsonApi\Document;

class EditVoteController extends AbstractCreateController
{
    public $serializer = VoteSerializer::class;

    /**
     * @var Dispatcher
     */
    protected $bus;

    public $include = ['options'];

    /**
     * @param Dispatcher $bus
     */
    public function __construct(Dispatcher $bus)
    {
        $this->bus = $bus;
    }

    /**
     * {@inheritdoc}
     */
    protected function data(ServerRequestInterface $request, Document $document)
    {
        $actor = $request->getAttribute('actor');
        $attributes = Arr::get($request->getParsedBody(), 'data.attributes');
        $id = Arr::get($request->getQueryParams(), 'id');

        return $this->bus->dispatch(
            new EditVote($actor, $id, $attributes)
        );
    }
}
