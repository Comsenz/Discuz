<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Api\Serializer;

use App\Models\Group;
use Discuz\Api\Serializer\AbstractSerializer;
use Tobscure\JsonApi\Relationship;

class GroupSerializer extends AbstractSerializer
{
    /**
     * {@inheritdoc}
     */
    protected $type = 'groups';

    /**
     * {@inheritdoc}
     *
     * @param Group $model
     * @return array
     */
    public function getDefaultAttributes($model)
    {
        return [
            'name'  => $model->name,
            'type'  => $model->type,
            'color' => $model->color,
            'icon'  => $model->icon,
            'default' => $model->default
        ];
    }

    /**
     * @param $group
     * @return Relationship
     */
    public function permission($group)
    {
        return $this->hasMany($group, GroupPermissionSerializer::class);
    }
}
