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
use Illuminate\Contracts\Container\BindingResolutionException;
use Tobscure\JsonApi\Relationship;

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
            'reply_count'     => $model->reply_count,
            'like_count'        => $model->like_count,
            'created_at'        => $this->formatDate($model->created_at),
            'updated_at'        => $this->formatDate($model->updated_at),
            'deleted_at'        => $this->formatDate($model->deleted_at),
            'deleted_user_id'   => (int) $model->deleted_user_id,
            'is_first'          => (bool) $model->is_first,
            'is_approved'       => (bool) $model->is_approved,
        ];
    }

    /**
     * @param $post
     * @return Relationship
     * @throws BindingResolutionException
     */
    protected function user($post)
    {
        return $this->hasOne($post, UserSerializer::class, 'user');
    }

    /**
     * @param $post
     * @return Relationship
     * @throws BindingResolutionException
     */
    protected function thread($post)
    {
        return $this->hasOne($post, ThreadSerializer::class, 'thread');
    }
}
