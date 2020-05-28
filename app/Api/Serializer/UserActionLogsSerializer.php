<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Api\Serializer;

use Discuz\Api\Serializer\AbstractSerializer;
use Illuminate\Support\Str;

class UserActionLogsSerializer extends AbstractSerializer
{
    /**
     * {@inheritdoc}
     */
    protected $type = 'user-action-logs';

    /**
     * {@inheritdoc}
     */
    protected function getDefaultAttributes($model)
    {
        return [
            'action'            => $model->action,
            'message'           => $model->message,
            'type'              => Str::after($model->log_able_type, 'App\Models\\'),
            'createdAt'         => $this->formatDate($model->created_at),
        ];
    }
}
