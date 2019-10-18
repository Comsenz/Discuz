<?php

/**
 *      Discuz & Tencent Cloud
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: PostSerializer.php xxx 2019-10-18 10:50:00 LiuDongdong $
 */

namespace App\Api\Serializer;

use App\Models\Post;
use Discuz\Api\Serializer\AbstractSerializer;

class PostSerializer extends AbstractSerializer
{
    /**
     * {@inheritdoc}
     */
    protected $type = 'Posts';

    /**
     * {@inheritdoc}
     *
     * @param Post $model
     */
    public function getDefaultAttributes($model)
    {
        return [
            'id'                => (int) $model->id,
            'user_id'           => (int) $model->user_id,
            'thread_id'         => (int) $model->thread_id,
            'content'           => $model->content,
            'ip'                => $model->ip,
            'comment_count'     => $model->comment_count,
            'like_count'        => $model->like_count,
            'created_at'        => $this->formatDate($model->created_at),
            'updated_at'        => $this->formatDate($model->updated_at),
            'deleted_at'        => $this->formatDate($model->deleted_at),
            'delete_user_id'    => (int) $model->delete_user_id,
            'is_first'          => (int) $model->is_first,
            'is_approved'       => (int) $model->is_approved,
        ];
    }
}
