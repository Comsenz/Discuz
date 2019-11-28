<?php

/**
 *      Discuz & Tencent Cloud
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: OperationLogSerializer.php xxx 2019-11-27 16:17:00 LiuDongdong $
 */

namespace app\Api\Serializer;

use Discuz\Api\Serializer\AbstractSerializer;
use Illuminate\Support\Str;

class OperationLogSerializer extends AbstractSerializer
{
    /**
     * {@inheritdoc}
     */
    protected $type = 'operation-logs';

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
