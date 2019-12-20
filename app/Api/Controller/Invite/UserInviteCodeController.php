<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Api\Controller\Invite;

use App\Exceptions\NoUserException;
use Discuz\Api\JsonApiResponse;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Http\Message\ResponseInterface;

class UserInviteCodeController implements RequestHandlerInterface
{
    /**
     * {@inheritdoc}
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $actor = $request->getAttribute('actor');

        if (!$actor) {
            throw new NoUserException();
        }

        $encrypter = app('encrypter');
        $code = $encrypter->encryptString($actor->id);
        $data = [
            'data' => [
                'type' => 'invite',
                'code' => $code
            ],
        ];

        return new JsonApiResponse($data);
    }
}
