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

use App\Models\StopWord;
use Discuz\Api\Serializer\AbstractSerializer;
use Tobscure\JsonApi\Relationship;

class StopWordSerializer extends AbstractSerializer
{
    /**
     * {@inheritdoc}
     */
    protected $type = 'stop-words';

    /**
     * {@inheritdoc}
     *
     * @param StopWord $model
     */
    public function getDefaultAttributes($model)
    {
        return [
            'ugc'         => $model->ugc,
            'username'    => $model->username,
            'signature'   => $model->signature,
            'dialog'      => $model->dialog,
            'find'        => $model->find,
            'replacement' => $model->replacement,
            'created_at'  => $this->formatDate($model->created_at),
            'updated_at'  => $this->formatDate($model->updated_at),
        ];
    }

    /**
     * @param $stopWord
     * @return Relationship
     */
    protected function user($stopWord)
    {
        return $this->hasOne($stopWord, UserSerializer::class);
    }
}
