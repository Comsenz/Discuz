<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Api\Controller\Invite;

use App\Api\Serializer\InviteSerializer;
use App\Commands\Invite\CreateInvite;
use Discuz\Api\Controller\AbstractCreateController;
use Psr\Http\Message\ServerRequestInterface;
use Tobscure\JsonApi\Document;
use Illuminate\Contracts\Bus\Dispatcher;

class CreateAdminInviteController extends AbstractCreateController
{
    /**
     * {@inheritdoc}
     */
    public $serializer = InviteSerializer::class;

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
    protected function data(ServerRequestInterface $request, Document $document)
    {
        return $this->bus->dispatch(
            new CreateInvite($request->getAttribute('actor'), $request->getParsedBody()->get('data', []))
        );
    }
}
