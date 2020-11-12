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

use App\Models\Question;
use App\Traits\HasPaidContent;
use Discuz\Api\Serializer\AbstractSerializer;
use Tobscure\JsonApi\Relationship;

class QuestionAnswerSerializer extends AbstractSerializer
{
    use HasPaidContent;

    /**
     * {@inheritdoc}
     */
    protected $type = 'question_answer';

    /**
     * {@inheritdoc}
     *
     * @param Question $model
     */
    public function getDefaultAttributes($model)
    {
        $this->paidContent($model);

        return [
            'thread_id'             => $model->thread_id,
            'user_id'               => $model->thread->is_anonymous ? 0 : $model->user_id, // 判断是否是匿名
            'be_user_id'            => $model->be_user_id,
            'content'               => $model->content,
            'content_html'          => $model->formatContent(),
            'ip'                    => $model->ip,
            'port'                  => (int) $model->port,
            'price'                 => $model->price,
            'onlooker_unit_price'   => $model->onlooker_unit_price,
            'onlooker_price'        => $model->onlooker_price,
            'onlooker_number'       => $model->onlooker_number,
            'is_onlooker'           => (bool) $model->is_onlooker,
            'is_answer'             => $model->is_answer,
            'is_approved'           => $model->is_approved,
            'created_at'            => $this->formatDate($model->created_at),
            'updated_at'            => $this->formatDate($model->updated_at),
            'expired_at'            => $this->formatDate($model->expired_at),
            'answered_at'           => $this->formatDate($model->answered_at),
        ];
    }

    /**
     * @param Question $model
     * @return Relationship
     */
    public function beUser($model)
    {
        return$this->hasOne($model, UserSerializer::class);
    }

    /**
     * @param Question $model
     * @return Relationship
     */
    protected function images($model)
    {
        return $this->hasMany($model, AttachmentSerializer::class);
    }

}
