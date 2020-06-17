<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Api\Serializer;

use Discuz\Api\Serializer\AbstractSerializer;

class OffIAccountAssetSerializer extends AbstractSerializer
{
    protected $type = 'offiaccount_asset';

    /**
     * Get the default set of serialized attributes for a model.
     *
     * @param object|array $model
     * @return array
     */
    public function getDefaultAttributes($model)
    {
        return $model;
    }

    public function getId($model)
    {
        return 1;
    }
}
