<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Api\Serializer;

use Discuz\Api\Serializer\AbstractSerializer;

class SessionSerializer extends AbstractSerializer
{
    /**
     * {@inheritdoc}
     */
    protected $type = 'sessions';

    /**
     * {@inheritdoc}
     */
    public function getDefaultAttributes($model)
    {
        return ['sessionId'=> $model['sessionId']];
    }
    public function getId($model)
    {
        return $model['sessionId'];
    }
}
