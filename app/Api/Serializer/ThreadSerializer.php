<?php

/**
 * Copyright (C) 2020 Tencent Cloud.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

namespace App\Api\Serializer;

use App\Models\Thread;
use App\Traits\HasPaidContent;
use Discuz\Api\Serializer\AbstractSerializer;
use Discuz\Auth\Anonymous;
use Illuminate\Contracts\Auth\Access\Gate;
use Tobscure\JsonApi\Relationship;

class ThreadSerializer extends AbstractSerializer
{
    use HasPaidContent;

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
        $this->paidContent($model);

        $gate = $this->gate->forUser($this->actor);

        $attributes = [
            'type'              => (int) $model->type,
            'title'             => $model->title,
            'price'             => $model->price,
            'attachmentPrice'   => $model->attachment_price,
            'freeWords'         => (int) $model->free_words,
            'viewCount'         => (int) $model->view_count,
            'postCount'         => (int) $model->post_count,
            'paidCount'         => (int) $model->paid_count,
            'rewardedCount'     => (int) $model->rewarded_count,
            'longitude'         => $model->longitude,
            'latitude'          => $model->latitude,
            'address'           => $model->address,
            'location'          => $model->location,
            'createdAt'         => $this->formatDate($model->created_at),
            'updatedAt'         => $this->formatDate($model->updated_at),
            'isApproved'        => (int) $model->is_approved,
            'isSticky'          => (bool) $model->is_sticky,
            'isEssence'         => (bool) $model->is_essence,
            'isSite'            => (bool) $model->is_site,
            'isAnonymous'       => (bool) $model->is_anonymous,
            'canBeReward'       => $model->price == 0 && $model->user->can('canBeReward'),
            'canViewPosts'      => $gate->allows('viewPosts', $model),
            'canReply'          => $gate->allows('reply', $model),
            'canApprove'        => $gate->allows('approve', $model),
            'canSticky'         => $gate->allows('sticky', $model),
            'canEssence'        => $gate->allows('essence', $model),
            'canDelete'         => $gate->allows('delete', $model),
            'canHide'           => $gate->allows('hide', $model),
            'canEdit'           => $gate->allows('edit', $model),
        ];

        if ($model->deleted_at) {
            $attributes['isDeleted'] = true;
            $attributes['deletedAt'] = $this->formatDate($model->deleted_at);
        } else {
            $attributes['isDeleted'] = false;
        }

        if ($model->price > 0) {
            $attributes['paid'] = $model->is_paid;      // 向下兼容，建议改为 isPaid
            $attributes['isPaid'] = $model->is_paid;
        }

        if ($model->attachment_price > 0) {
            $attributes['isPaidAttachment'] = $model->is_paid_attachment;
        }

        $this->isQuestion($model, $attributes);

        return $attributes;
    }

    /**
     * @param Thread $model
     * @param array $attributes
     */
    public function isQuestion($model, &$attributes)
    {
        // 判断是否是问答帖
        if ($model->type !== Thread::TYPE_OF_QUESTION) {
            return;
        }

        // 判断问答信息是否存在
        if (empty($model->question)) {
            $attributes['onlookerState'] = false;
            return;
        }

        /**
         * 判断是否围观过帖子
         */
        if ($this->actor->isGuest()) {
            // 游客身份 直接未围观
            $attributes['onlookerState'] = false;
        } elseif (
            $model->user_id === $this->actor->id
            || $model->question->be_user_id === $this->actor->id
            || $this->actor->isAdmin()
            || ! is_null($model->onlookerState)
        ) {
            // 作者 或 被提问者 或 管理员 直接已围观
            $attributes['onlookerState'] = true;
        } else {
            // 判断是否是免费的问答免费的围观，直接等于围观过
            if ($model->question->price == 0 && $model->question->onlooker_unit_price == 0) {
                $attributes['onlookerState'] = true;
            } else {
                // 判断其它人查询订单是否围观过
                $attributes['onlookerState'] = false;
            }
        }

        /**
         * 判断是否匿名问答
         * (非当前用户不是作者)
         */
        if ($model->is_anonymous && $model->user->id != $this->actor->id) {
            $model->user = new Anonymous;
        }
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
    protected function lastPostedUser($thread)
    {
        return $this->hasOne($thread, UserSerializer::class);
    }

    /**
     * @param $thread
     * @return Relationship
     */
    protected function deletedUser($thread)
    {
        return $this->hasOne($thread, UserSerializer::class);
    }

    /**
     * @param $thread
     * @return Relationship
     */
    protected function category($thread)
    {
        return $this->hasOne($thread, CategorySerializer::class);
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

    /**
     * @param $thread
     * @return Relationship
     */
    public function paidUsers($thread)
    {
        return $this->hasMany($thread, UserSerializer::class);
    }

    /**
     * @param $thread
     * @return Relationship
     */
    public function logs($thread)
    {
        return $this->hasMany($thread, UserActionLogsSerializer::class);
    }

    /**
     * @param $thread
     * @return Relationship
     */
    public function lastDeletedLog($thread)
    {
        return $this->hasOne($thread, UserActionLogsSerializer::class);
    }

    /**
     * @param $thread
     * @return Relationship
     */
    public function threadVideo($thread)
    {
        return $this->hasOne($thread, ThreadVideoSerializer::class);
    }

    /**
     * @param $thread
     * @return Relationship
     */
    public function threadAudio($thread)
    {
        return $this->hasOne($thread, ThreadVideoSerializer::class);
    }

    /**
     * @param $thread
     * @return Relationship
     */
    public function topic($thread)
    {
        return $this->hasMany($thread, TopicSerializer::class);
    }

    public function question($thread)
    {
        return $this->hasOne($thread, QuestionAnswerSerializer::class);
    }

    public function onlookers($thread)
    {
        return $this->hasMany($thread, UserSerializer::class);
    }
}
