<?php
declare(strict_types = 1);

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Api\Serializer;

use Discuz\Api\Serializer\AbstractSerializer;

class PayOrderSerializer extends AbstractSerializer
{
    protected $type = 'payorder';

    public function getDefaultAttributes($model)
    {
        if (isset($model->payment_params)) {
            return $model->payment_params;
        } else {
            return '';
        }
    }
}
