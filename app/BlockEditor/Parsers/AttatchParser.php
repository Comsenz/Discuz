<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\BlockEditor\Parsers;
use App\Models\Attachment;
use App\Models\User;

class AttatchParser extends BaseParser
{

    public static function checkImageAttachExist(array $attach_ids, User $actor)
    {
         $count = Attachment::query()
            ->where('user_id', $actor->id)
            ->where('type_id', 0)
            ->where('type', Attachment::TYPE_OF_IMAGE)
            ->whereIn('id', $attach_ids)
            ->count();

         if ($count == count($attach_ids)) {
             return true;
         } else {
             return false;
         }
    }
}
