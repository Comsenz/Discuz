<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\BlockEditor\Parsers;

use App\Models\Attachment;
use App\Models\Post;
use App\Models\User;

class AttatchParser extends BaseParser
{
    public static function checkAttachExist(array $attach_ids, User $actor, int $type, Post $post)
    {
        $type_ids = [0];
        if ($post->id) {
            $type_ids[] = $post->id;
        }
        $count = Attachment::query()
            ->where('user_id', $actor->id)
            ->where('type', $type)
            ->whereIn('type_id', $type_ids)
            ->whereIn('id', $attach_ids)
            ->count();

        if ($count == count($attach_ids)) {
            return true;
        } else {
            return false;
        }
    }
}
