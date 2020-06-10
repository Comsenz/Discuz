<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Api\Serializer;

use App\Models\Post;
use Discuz\Api\Serializer\AbstractSerializer;
use Illuminate\Contracts\Auth\Access\Gate;
use Tobscure\JsonApi\Relationship;

class PostResourceSerializer extends AbstractSerializer
{
    /**
     * {@inheritdoc}
     */
    protected $type = 'posts';

    /**
     * @var Gate
     */
    protected $gate;

    /**
     * {@inheritdoc}
     *
     * @param Post $model
     */
    public function getDefaultAttributes($model)
    {
        $attributes = [
            'id' => $model->id,
            'user_id' => $model->user_id,
            'thread_id' => $model->thread_id,
            'content' => $model->content,
            'ip' => $model->ip,
            'is_first' => $model->is_first,
            'is_comment' => $model->is_comment,
        ];

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
     * @param $post
     * @return Relationship
     */
    public function comment_posts($post)
    {
        return $this->hasMany($post, UserActionLogsSerializer::class);
    }
}
