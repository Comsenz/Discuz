<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Api\Controller\Users;

use App\Api\Serializer\UserProfileSerializer;
use App\Commands\Users\RealUser;
use Discuz\Api\Controller\AbstractCreateController;
use Discuz\Auth\AssertPermissionTrait;
use Illuminate\Contracts\Bus\Dispatcher;
use Psr\Http\Message\ServerRequestInterface;
use Tobscure\JsonApi\Document;

class RealUserController extends AbstractCreateController
{
    use AssertPermissionTrait;

    public $serializer = UserProfileSerializer::class;

    protected $bus;

    public function __construct(Dispatcher $bus)
    {
        $this->bus = $bus;
    }

    /**
     * @param ServerRequestInterface $request
     * @param Document $document
     * @return mixed
     * @throws \Discuz\Auth\Exception\PermissionDeniedException
     */
    protected function data(ServerRequestInterface $request, Document $document)
    {
        $actor = $request->getAttribute('actor');

        $this->assertPermission($actor->id);

        $data = $request->getParsedBody()->get('data', []);
        return $this->bus->dispatch(
            new RealUser( $data, $actor)
        );
    }
}
