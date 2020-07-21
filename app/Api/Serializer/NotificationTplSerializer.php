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

use Discuz\Api\Serializer\AbstractSerializer;

class NotificationTplSerializer extends AbstractSerializer
{
    protected $type = 'notification_tpls';

    /**
     * @inheritDoc
     */
    protected function getDefaultAttributes($model)
    {
        return [
            'status' => $model->status,
            'type_name' => $model->type_name,
            'title' => $model->title,
            'content' => $model->content,
            'vars' => unserialize($model->vars),
            'template_id' => $model->template_id,
        ];
    }
}
