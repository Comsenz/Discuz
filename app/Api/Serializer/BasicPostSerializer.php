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

class BasicPostSerializer extends AbstractSerializer
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
    protected function getDefaultAttributes($model)
    {
        $gate = $this->gate->forUser($this->actor);

        $canEdit = $gate->allows('edit', $model);

        $attributes = [
            'replyUserId'       => $model->reply_user_id,
            'content'           => $model->content,
            'contentHtml'       => $model->formatContent(),
            'replyCount'        => $model->reply_count,
            'likeCount'         => $model->like_count,
            'createdAt'         => $this->formatDate($model->created_at),
            'updatedAt'         => $this->formatDate($model->updated_at),
            'isApproved'        => (int) $model->is_approved,
            'canEdit'           => $canEdit,
            'canApprove'        => $gate->allows('approve', $model),
            'canDelete'         => $gate->allows('delete', $model),
            'canHide'           => $gate->allows('hide', $model),
        ];

        if ($canEdit || $this->actor->id === $model->user_id) {
            $attributes += [
                'ip' => $model->ip,
            ];
        }

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
    protected function replyUser($post)
    {
        return $this->hasOne($post, UserSerializer::class);
    }

    /**
     * @param $post
     * @return Relationship
     */
    protected function deletedUser($post)
    {
        return $this->hasOne($post, UserSerializer::class);
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
    public function mentionUsers($post)
    {
        return $this->hasMany($post, UserSerializer::class);
    }

    /**
     * @param $post
     * @return Relationship
     */
    protected function images($post)
    {
        return $this->hasMany($post, AttachmentSerializer::class);
    }

    /**
     * @param $post
     * @return Relationship
     */
    protected function attachments($post)
    {
        return $this->hasMany($post, AttachmentSerializer::class);
    }

    /**
     * @param $post
     * @return Relationship
     */
    public function logs($post)
    {
        return $this->hasMany($post, OperationLogSerializer::class);
    }

    /**
     * @param $post
     * @return Relationship
     */
    public function lastDeletedLog($post)
    {
        return $this->hasOne($post, OperationLogSerializer::class);
    }
}
