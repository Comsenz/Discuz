<?php

/**
 *      Discuz & Tencent Cloud
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: ThreadSerializer.php xxx 2019-10-09 20:10:00 LiuDongdong $
 */

namespace App\Api\Serializer;

use App\Models\Thread;
use Discuz\Api\Serializer\AbstractSerializer;
use Illuminate\Contracts\Container\BindingResolutionException;
use Tobscure\JsonApi\Relationship;

class ThreadSerializer extends AbstractSerializer
{
    /**
     * {@inheritdoc}
     */
    protected $type = 'threads';

    /**
     * {@inheritdoc}
     *
     * @param Thread $model
     */
    public function getDefaultAttributes($model)
    {
        $attributes = [
            // 'id'                    => (int) $model->id,
            // 'user_id'               => (int) $model->user_id,
            // 'last_posted_user_id'   => (int) $model->last_posted_user_id,
            'title'                 => $model->title,
            'price'                 => $model->price,
            'viewCount'             => (int) $model->view_count,
            'postCount'             => (int) $model->post_count,
            'likeCount'             => (int) $model->like_count,
            'createdAt'             => $this->formatDate($model->created_at),
            'updatedAt'             => $this->formatDate($model->updated_at),
            // 'deleted_at'            => $this->formatDate($model->deleted_at),
            // 'deleted_user_id'       => (int) $model->deleted_user_id,
            'isApproved'            => (bool) $model->is_approved,
            'isSticky'              => (bool) $model->is_sticky,
            'isEssence'             => (bool) $model->is_essence,
        ];

        if ($model->deleted_at) {
            $attributes['isDeleted'] = true;
            $attributes['deletedAt'] = $this->formatDate($model->deleted_at);
        }

        return $attributes;
    }

    /**
     * @param $thread
     * @return Relationship
     */
    protected function user($thread)
    {
        return $this->hasOne($thread, UserSerializer::class);
    }

    /**
     * @param $thread
     * @return Relationship
     */
    public function firstPost($thread)
    {
        return $this->hasOne($thread, PostSerializer::class);
    }

    /**
     * @param $thread
     * @return Relationship
     */
    public function lastThreePosts($thread)
    {
        return $this->hasMany($thread, PostSerializer::class);
    }

    /**
     * @param $thread
     * @return Relationship
     */
    public function posts($thread)
    {
        return $this->hasMany($thread, PostSerializer::class);
    }
}
