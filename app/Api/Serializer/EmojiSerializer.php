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

use App\Models\Emoji;
use Discuz\Api\Serializer\AbstractSerializer;
use Illuminate\Contracts\Routing\UrlGenerator;

class EmojiSerializer extends AbstractSerializer
{
    protected $url;

    public function __construct(UrlGenerator $url)
    {
        $this->url = $url;
    }

    /**
     * {@inheritdoc}
     */
    protected $type = 'emoji';

    /**
     * {@inheritdoc}
     *
     * @param Emoji $model
     */
    public function getDefaultAttributes($model)
    {
        return [
            'category'          => $model->category,
            // 'url'               => $model->url,
            'url'               => $this->url->to('/' . $model->url),
            'code'              => $model->code,
            'order'             => $model->order,
        ];
    }
}
