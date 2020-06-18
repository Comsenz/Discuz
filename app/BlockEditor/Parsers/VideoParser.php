<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\BlockEditor\Parsers;

use App\Models\Attachment;
use App\Models\ThreadVideo;
use App\Models\User;

class VideoParser extends BaseParser
{
    public static function checkVideoExist(array $video_ids, User $actor, int $type)
    {
        $count = ThreadVideo::query()
            ->where('user_id', $actor->id)
            ->where('type', $type)
            ->where('thread_id', 0)
            ->whereIn('id', $video_ids)
            ->count();

        if ($count == count($video_ids)) {
            return true;
        } else {
            return false;
        }
    }
}
