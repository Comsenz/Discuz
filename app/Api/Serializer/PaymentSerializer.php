<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Api\Serializer;

use Discuz\Api\Serializer\AbstractSerializer;

class PaymentSerializer extends AbstractSerializer
{
    protected $type = 'payments';

    /**
     * Get the default set of serialized attributes for a model.
     *
     * @param object|array $model
     * @return array
     */
    public function getDefaultAttributes($model)
    {
        return [
            'payment_type' => $model['payment_type'],
            'payment_name' => $model['payment_name'],
            'payment_state' => $model['payment_state'],
        ];
    }

    public function getId($model)
    {
        return 1;
    }
}
