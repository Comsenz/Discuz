<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Api\Controller\Emoji;

use App\Api\Serializer\EmojiSerializer;
use App\Models\Emoji;
use Discuz\Api\Controller\AbstractListController;
use Psr\Http\Message\ServerRequestInterface;
use Tobscure\JsonApi\Document;

class ListEmojiController extends AbstractListController
{
    /**
     * {@inheritdoc}
     */
    public $serializer = EmojiSerializer::class;

    /**
     * {@inheritdoc}
     */
    protected function data(ServerRequestInterface $request, Document $document)
    {
        return Emoji::all();
    }
}
