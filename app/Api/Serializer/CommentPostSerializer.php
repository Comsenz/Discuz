<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Api\Serializer;

use App\Models\Post;
use Tobscure\JsonApi\Relationship;

class CommentPostSerializer extends BasicPostSerializer
{
    /**
     * {@inheritdoc}
     */
    protected $type = 'comment-posts';

    /**
     * {@inheritdoc}
     *
     * @param Post $model
     */
    public function getDefaultAttributes($model)
    {
        $attributes = parent::getDefaultAttributes($model);

        $attributes['isFirst'] = false;
        $attributes['isComment'] = (bool) $model->is_comment;

        return $attributes;
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
