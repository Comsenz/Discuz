<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Api\Serializer;

use Discuz\Api\Serializer\AbstractSerializer;

class VerifyMobileSerializer extends AbstractSerializer
{
    protected $type = 'verifymobile';

    /**
     * Get the default set of serialized attributes for a model.
     *
     * @param object|array $model
     * @return array
     */
    protected function getDefaultAttributes($model)
    {
        return [
            'type' => $model['type']
        ];
    }

    public function getId($model)
    {
        return 1;
    }
}
