<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Api\Serializer;

use App\Models\Post;
use Tobscure\JsonApi\Relationship;

class PostSerializer extends BasicPostSerializer
{
    /**
     * {@inheritdoc}
     *
     * @param Post $model
     */
    public function getDefaultAttributes($model)
    {
        $attributes = parent::getDefaultAttributes($model);

        $attributes['isFirst'] = (bool) $model->is_first;
        $attributes['isComment'] = false;

        return $attributes;
    }

    /**
     * @param $post
     * @return Relationship
     */
    protected function commentPosts($post)
    {
        return $this->hasMany($post, CommentPostSerializer::class);
    }

    /**
     * @param $post
     * @return Relationship
     */
    protected function lastThreeComments($post)
    {
        return $this->hasMany($post, CommentPostSerializer::class);
    }
}
