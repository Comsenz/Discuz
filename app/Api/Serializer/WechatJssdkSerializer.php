<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Api\Serializer;

use Discuz\Api\Serializer\AbstractSerializer;

class WechatJssdkSerializer extends AbstractSerializer
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
            'debug' => $model['debug'],
            'beta' => $model['beta'],
            'jsApiList' => $model['jsApiList'],
            'appId' => $model['appId'],
            'nonceStr' => $model['nonceStr'],
            'timestamp' => $model['timestamp'],
            'url' => $model['url'],
            'signature' => $model['signature'],
        ];
    }

    public function getId($model)
    {
        return 1;
    }
}
