<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Api\Serializer;

use Discuz\Api\Serializer\AbstractSerializer;

class PostGoodSerializer extends AbstractSerializer
{
    protected $type = 'post_goods';

    public function getDefaultAttributes($model)
    {
        return $model;
    }

    public function getId($model)
    {
        return 1;
    }

}
