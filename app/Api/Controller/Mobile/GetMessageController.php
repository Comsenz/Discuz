<?php
declare(strict_types=1);


namespace App\Api\Controller\Mobile;

use App\Api\Serializer\UserIdentSerializer;
use App\Commands\Users\GetMessage;
use Discuz\Api\Controller\AbstractCreateController;
use Psr\Http\Message\ServerRequestInterface;
use Tobscure\JsonApi\Document;

class GetMessageController extends AbstractCreateController
{
    public $serializer = UserIdentSerializer::class;

    public function data(ServerRequestInterface $request, Document $document)
    {
        // 获取当前用户
        $actor = $request->getAttribute('actor');
        // 获取请求的参数
        $inputs = $request->getParsedBody();

        $data = $this->bus->dispatch(
            new GetMessage($actor, $inputs->toArray())
        );
        return $data;

    }
}