<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
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
