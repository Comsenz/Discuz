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
    protected $type = 'Threads';

    /**
     * {@inheritdoc}
     *
     * @param Thread $model
     */
    public function getDefaultAttributes($model)
    {
        return [
            'id'                    => (int) $model->id,
            'user_id'               => (int) $model->user_id,
            'last_posted_user_id'   => (int) $model->last_posted_user_id,
            'title'                 => $model->title,
            'price'                 => $model->price,
            'view_count'            => (int) $model->view_count,
            'post_count'            => (int) $model->post_count,
            'like_count'            => (int) $model->like_count,
            'favorite_count'        => (int) $model->favorite_count,
            'created_at'            => $this->formatDate($model->created_at),
            'updated_at'            => $this->formatDate($model->updated_at),
            'deleted_at'            => $this->formatDate($model->deleted_at),
            'deleted_user_id'       => (int) $model->deleted_user_id,
            'is_approved'           => (bool) $model->is_approved,
            'is_sticky'             => (bool) $model->is_sticky,
            'is_essence'            => (bool) $model->is_essence,
        ];
    }

    /**
     * @param $thread
     * @return Relationship
     * @throws BindingResolutionException
     */
    protected function user($thread)
    {
        return $this->hasOne($thread, UserSerializer::class, 'user');
    }

    /**
     * @param $thread
     * @return Relationship
     * @throws BindingResolutionException
     */
    public function firstPost($thread)
    {
        return $this->hasOne($thread, PostSerializer::class, 'firstPost');
    }

    /**
     * @param $thread
     * @return Relationship
     * @throws BindingResolutionException
     */
    public function posts($thread)
    {
        return $this->hasMany($thread, PostSerializer::class, 'posts');
    }
}
