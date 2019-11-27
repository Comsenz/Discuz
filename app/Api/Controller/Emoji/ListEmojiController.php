<?php
declare(strict_types=1);

namespace App\Api\Controller\Emoji;

use App\Api\Serializer\EmojiSerializer;
use App\Models\Emoji;
use Discuz\Api\Controller\AbstractListController;
use Psr\Http\Message\ServerRequestInterface;
use Tobscure\JsonApi\Document;

class ListEmojiController extends AbstractListController
{
    public $serializer = EmojiSerializer::class;

    protected function data(ServerRequestInterface $request, Document $document)
    {
        $actor = $request->getAttribute('actor');
        $actor->id = 1;

        return Emoji::all();
    }
}
