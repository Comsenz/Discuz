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
use Illuminate\Contracts\Auth\Access\Gate;
use Tobscure\JsonApi\Relationship;

class PostSerializer extends AbstractSerializer
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
     * @param Gate $gate
     */
    public function __construct(Gate $gate)
    {
        $this->gate = $gate;
    }

    /**
     * {@inheritdoc}
     *
     * @param Post $model
     */
    public function getDefaultAttributes($model)
    {
        $gate = $this->gate->forUser($this->actor);

        $attributes = [
            'content'           => $model->content,
            'ip'                => $model->ip,
            'replyCount'        => $model->reply_count,
            'likeCount'         => $model->like_count,
            'createdAt'         => $this->formatDate($model->created_at),
            'updatedAt'         => $this->formatDate($model->updated_at),
            'isFirst'           => (bool) $model->is_first,
            'isApproved'        => (int) $model->is_approved,
            'canApprove'        => $gate->allows('approve', $model),
            'canDelete'         => $gate->allows('delete', $model),
        ];

        if ($model->deleted_at) {
            $attributes['isDeleted'] = true;
            $attributes['deletedAt'] = $this->formatDate($model->deleted_at);
        }

        Post::setStateUser($this->actor);

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

    /**
     * @param $post
     * @return Relationship
     */
    protected function likedUsers($post)
    {
        return $this->hasMany($post, UserSerializer::class);
    }

    /**
     * @param $post
     * @return Relationship
     */
    public function logs($post)
    {
        return $this->hasMany($post, OperationLogSerializer::class);
    }
}
