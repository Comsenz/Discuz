<?php

/**
 *      Discuz & Tencent Cloud
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: ThreadSerializer.php xxx 2019-10-09 20:10:00 LiuDongdong $
 */

namespace App\Api\Serializer;

use Discuz\Api\Serializer\AbstractSerializer;

class ThreadSerializer extends AbstractSerializer
{
    protected $type = 'Threads';

    public function getDefaultAttributes($model)
    {
        return [
            'id' => $model->id,
            'user_id' => $model->user_id,
            'last_posted_user_id' => $model->last_posted_user_id,
            'title' => $model->title,
            'price' => $model->price,
            'view_count' => $model->view_count,
            'reply_count' => $model->reply_count,
            'like_count' => $model->like_count,
            'favorite_count' => $model->favorite_count,
            'created_at' => $model->created_at,
            'updated_at' => $model->updated_at,
            'deleted_at' => $model->deleted_at,
            'delete_user_id' => $model->delete_user_id,
            'is_approved' => $model->is_approved,
            'is_sticky' => $model->is_sticky,
            'is_essence' => $model->is_essence,
        ];
    }
}
