<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Api\Serializer;

use Discuz\Api\Serializer\AbstractSerializer;

class GroupPermissionSerializer extends AbstractSerializer
{
    protected $type = 'permissions';

    /**
     * Get the default set of serialized attributes for a model.
     *
     * @param object|array $model
     * @return array
     */
    public function getDefaultAttributes($model)
    {
        return [
            'group_id' => $model->group_id,
            'permission' => $model->permission
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function getId($model)
    {
        return hash('crc32b', $model->group_id.$model->permission);
    }
}
