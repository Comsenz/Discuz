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
use Tobscure\JsonApi\Relationship;

class PostSerializer extends AbstractSerializer
{
    /**
     * {@inheritdoc}
     */
    protected $type = 'posts';

    /**
     * {@inheritdoc}
     *
     * @param Post $model
     */
    public function getDefaultAttributes($model)
    {
        $attributes = [
            // 'id'                => (int) $model->id,
            // 'user_id'           => (int) $model->user_id,
            // 'thread_id'         => (int) $model->thread_id,
            // 'reply_id'          => (int) $model->reply_id,
            'content'           => $model->content,
            'ip'                => $model->ip,
            'replyCount'        => $model->reply_count,
            'likeCount'         => $model->like_count,
            'createdAt'         => $this->formatDate($model->created_at),
            'updatedAt'         => $this->formatDate($model->updated_at),
            // 'deletedAt'         => $this->formatDate($model->deleted_at),
            // 'deleted_user_id'   => (int) $model->deleted_user_id,
            'isFirst'           => (bool) $model->is_first,
            'isApproved'        => (bool) $model->is_approved,
        ];

        if ($model->deleted_at) {
            $attributes['isDeleted'] = true;
            $attributes['deletedAt'] = $this->formatDate($model->deleted_at);
        }

        return $attributes;
    }

    /**
     * @param $post
     * @return Relationship
     */
    protected function user($post)
    {
        return $this->hasOne($post, UserSerializer::class);
    }

    /**
     * @param $post
     * @return Relationship
     */
    protected function thread($post)
    {
        return $this->hasOne($post, ThreadSerializer::class);
    }
}
