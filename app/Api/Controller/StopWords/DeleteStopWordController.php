<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Api\Controller\StopWords;

use App\Commands\StopWord\DeleteStopWord;
use Discuz\Api\Controller\AbstractDeleteController;
use Illuminate\Contracts\Bus\Dispatcher;
use Illuminate\Support\Arr;
use Psr\Http\Message\ServerRequestInterface;

class DeleteStopWordController extends AbstractDeleteController
{
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
     */
    protected function delete(ServerRequestInterface $request)
    {
        $ids = explode(',', Arr::get($request->getQueryParams(), 'id'));
        $actor = $request->getAttribute('actor');

        foreach ($ids as $id) {
            $this->bus->dispatch(
                new DeleteStopWord($id, $actor)
            );
        }
    }
}
