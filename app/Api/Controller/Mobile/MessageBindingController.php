<?php
declare(strict_types=1);


namespace App\Api\Controller\Mobile;

use App\Api\Serializer\UserSerializer;
use App\Commands\Users\MessageBinding;
use Discuz\Api\Controller\AbstractCreateController;
use Psr\Http\Message\ServerRequestInterface;
use Tobscure\JsonApi\Document;

class MessageBindingController extends AbstractCreateController
{
    public $serializer = UserSerializer::class;

    protected function data(ServerRequestInterface $request, Document $document)
    {
        // 获取当前用户
        $actor = $request->getAttribute('actor');
        // 获取请求的参数
        $inputs = $request->getParsedBody();


        $data = $this->bus->dispatch(
            new MessageBinding($actor, $inputs->toArray(),$this->bus)
        );
        return $data;

    }
}
