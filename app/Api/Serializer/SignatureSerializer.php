<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Api\Serializer;

use Discuz\Api\Serializer\AbstractSerializer;
use Illuminate\Support\Arr;

class SignatureSerializer extends AbstractSerializer
{
    protected $type = 'signature';

    /**
     * Get the default set of serialized attributes for a model.
     *
     * @param object|array $model
     * @return array
     */
    public function getDefaultAttributes($model)
    {
        return [
            'signature' => Arr::first($model),
        ];
    }

    public function getId($model)
    {
        return 1;
    }
}
