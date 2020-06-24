<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\BlockEditor\Parsers;

use App\Models\Attachment;
use App\Models\Post;
use App\Models\ThreadVideo;
use App\Models\User;

class VideoParser extends BaseParser
{
    public static function checkVideoExist(array $video_ids, User $actor, int $type, Post $post)
    {
        //新增、编辑查询关联数据
        $thread_ids = [0];
        if ($post->thread_id) {
            $thread_ids[] = $post->thread_id;
        }
        $count = ThreadVideo::query()
            ->where('user_id', $actor->id)
            ->where('type', $type)
            ->whereIn('thread_id', $thread_ids)
            ->whereIn('id', $video_ids)
            ->count();

        if ($count == count($video_ids)) {
            return true;
        } else {
            return false;
        }
    }
}
