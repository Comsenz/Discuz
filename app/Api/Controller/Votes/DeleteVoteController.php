<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Api\Controller\Votes;

use App\Commands\Vote\DeleteVote;
use Discuz\Api\Controller\AbstractDeleteController;
use Illuminate\Contracts\Bus\Dispatcher;
use Illuminate\Support\Arr;
use Psr\Http\Message\ServerRequestInterface;

class DeleteVoteController extends AbstractDeleteController
{
    protected $bus;

    public function __construct(Dispatcher $bus)
    {
        $this->bus = $bus;
    }

    /**
     * @inheritDoc
     */
    protected function delete(ServerRequestInterface $request)
    {
        $this->bus->dispatch(
            new DeleteVote(Arr::get($request->getQueryParams(), 'id'), $request->getAttribute('actor'))
        );
    }
}
