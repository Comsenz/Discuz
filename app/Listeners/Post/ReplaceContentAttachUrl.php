<?php

/**
 * Copyright (C) 2020 Tencent Cloud.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

namespace App\Listeners\Post;

use App\Api\Controller\Threads\ResourceThreadController;
use App\Api\Serializer\AttachmentSerializer;
use App\Models\Attachment;
use App\Models\Thread;
use Discuz\Api\Events\WillSerializeData;
use s9e\TextFormatter\Utils;

class ReplaceContentAttachUrl
{
    public function handle(WillSerializeData $event)
    {
        if ($event->isController(ResourceThreadController::class)) {
            /** @var Thread $thread */
            $thread = $event->data;

            $post = $thread->firstPost;

            /** @var AttachmentSerializer $attachmentSerializer */
            $attachmentSerializer = app(AttachmentSerializer::class);

            $attachmentSerializer->setRequest($event->request);

            // 所有图片及附件 URL
            $attachments = $post->images
                ->merge($post->attachments)
                ->keyBy('id')
                ->map(function (Attachment $attachment) use ($attachmentSerializer) {
                    if ($attachment->type === Attachment::TYPE_OF_IMAGE) {
                        return $attachmentSerializer->getDefaultAttributes($attachment)['thumbUrl'];
                    } elseif ($attachment->type === Attachment::TYPE_OF_FILE) {
                        return $attachmentSerializer->getDefaultAttributes($attachment)['url'];
                    }
                });

            // 数据原始内容，即 s9e 解析后的 XML
            $xml = $post->getRawOriginal('content');

            // 替换插入内容中的图片 URL
            Utils::replaceAttributes($xml, 'IMG', function ($img) use (&$xml, $attachments) {
                if (isset($img['title']) && isset($attachments[$img['title']])) {
                    $xml = str_replace(htmlspecialchars($img['src']), $attachments[$img['title']], $xml);
                }

                return $img;
            });

            // 替换插入内容中的附件 URL
            Utils::replaceAttributes($xml, 'URL', function ($url) use (&$xml, $attachments) {
                if (isset($url['title']) && isset($attachments[$url['title']])) {
                    $xml = str_replace(htmlspecialchars($url['url']), $attachments[$url['title']], $xml);
                }

                return $url;
            });

            $post->parsedContent = $xml;
        }
    }
}
