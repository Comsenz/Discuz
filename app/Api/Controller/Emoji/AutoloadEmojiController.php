<?php

namespace App\Api\Controller\Emoji;

use App\Commands\Emoji\LoadEmoji;
use Discuz\Auth\AssertPermissionTrait;
use Discuz\Foundation\EventsDispatchTrait;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Illuminate\Support\Arr;
use Illuminate\Contracts\Bus\Dispatcher;

class AutoloadEmojiController implements RequestHandlerInterface
{
    use AssertPermissionTrait;
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

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $actor = $request->getAttribute('actor');
        //分类
        $category = Arr::get($request->getQueryParams(), 'category','');

        $emoji = $this->bus->dispatch(
            new LoadEmoji($actor, $category)
        );
    }
}
