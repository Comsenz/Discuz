<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Api\Serializer;

use Discuz\Api\Serializer\AbstractSerializer;

class UserWechatSerializer extends AbstractSerializer
{
    protected $type = 'wechats';

    /**
     * Get the default set of serialized attributes for a model.
     *
     * @param object|array $model
     * @return array
     */
    protected function getDefaultAttributes($model)
    {
        return [
            'mp_openid'         => $model->mp_openid,
            'dev_openid'         => $model->dev_openid,
            'min_openid'         => $model->min_openid,
            'nickname'          => $model->nickname,
            'sex'               => $model->sex, // 用户的性别，值为 1 时是男性，值为 2 时是女性，值为 0 时是未知
        ];
    }
}
