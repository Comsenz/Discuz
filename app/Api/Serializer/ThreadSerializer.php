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
use Illuminate\Contracts\Auth\Access\Gate;
use Tobscure\JsonApi\Relationship;

class ThreadSerializer extends AbstractSerializer
{
    /**
     * {@inheritdoc}
     */
    protected $type = 'threads';

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
     * @param Thread $model
     */
    public function getDefaultAttributes($model)
    {
        $gate = $this->gate->forUser($this->actor);

        $attributes = [
            'title'             => $model->title,
            'price'             => $model->price,
            'viewCount'         => (int) $model->view_count,
            'postCount'         => (int) $model->post_count,
            'likeCount'         => (int) $model->like_count,
            'createdAt'         => $this->formatDate($model->created_at),
            'updatedAt'         => $this->formatDate($model->updated_at),
            'isApproved'        => (int) $model->is_approved,
            'isSticky'          => (bool) $model->is_sticky,
            'isEssence'         => (bool) $model->is_essence,
            'canApprove'        => $gate->allows('approve', $model),
            'canSticky'         => $gate->allows('sticky', $model),
            'canEssence'        => $gate->allows('essence', $model),
            'canDelete'         => $gate->allows('delete', $model),
        ];

        if ($model->deleted_at) {
            $attributes['isDeleted'] = true;
            $attributes['deletedAt'] = $this->formatDate($model->deleted_at);
        }

        Thread::setStateUser($this->actor);

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
    protected function category($thread)
    {
        return $this->hasOne($thread, ClassifySerializer::class);
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

    /**
     * @param $thread
     * @return Relationship
     */
    public function rewarded($thread)
    {
        return $this->hasMany($thread, OrderSerializer::class);
    }

    /**
     * @param $thread
     * @return Relationship
     */
    public function rewardedUsers($thread)
    {
        return $this->hasMany($thread, UserSerializer::class);
    }
}
