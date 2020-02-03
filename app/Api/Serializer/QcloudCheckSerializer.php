<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Api\Serializer;

use Discuz\Api\Serializer\AbstractSerializer;

class QcloudCheckSerializer extends AbstractSerializer
{
    protected $type = 'qcloud-check';

    /**
     * Get the default set of serialized attributes for a model.
     *
     * @param object|array $model
     * @return array
     */
    public function getDefaultAttributes($model)
    {
        return [
            'qcloud_version' => $model['qcloud_version'],
            'client_version' => $model['client_version'],
            'need_update' => version_compare($model['qcloud_version'], $model['client_version'], '>')
        ];
    }

    public function getId($model)
    {
        return 1;
    }
}
