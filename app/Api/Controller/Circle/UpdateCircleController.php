<?php
declare(strict_types=1);

/**
 *      Discuz & Tencent Cloud
 *      This is NOT a freeware, use is subject to license terms
 *
 *      1: UpdateCircleController.php 28830 2019-09-26 10:04 chenkeke $
 */

namespace App\Api\Controller\Circle;

use Discuz\Api\Controller\AbstractCreateController;
use Psr\Http\Message\ServerRequestInterface;
use Tobscure\JsonApi\Document;

class UpdateCircleController extends AbstractCreateController
{
    public $serializer = UserSerializer::class;

    public function data(ServerRequestInterface $request, Document $document)
    {
        $attributes = Arr::get($request->getParsedBody(), 'data.attributes', []);

        $user = User::findOrFail($id);

        $user->name = $attributes['name'];

        $user->save();

        return $user;
    }
}