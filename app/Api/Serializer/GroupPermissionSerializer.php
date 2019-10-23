<?php
declare(strict_types=1);

/**
 *      Discuz & Tencent Cloud
 *      This is NOT a freeware, use is subject to license terms
 *
 *      Id: GroupPermissionSerializer.php 28830 2019-10-23 11:06 chenkeke $
 */

namespace App\Api\Serializer;


use Discuz\Api\Serializer\AbstractSerializer;

class GroupPermissionSerializer extends AbstractSerializer
{

    protected $type = 'groupPermission';

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
        return hexdec(substr(md5($model->group_id.$model->permission), -3));
    }
}
