<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Api\Serializer;

use Discuz\Api\Serializer\AbstractSerializer;

class PostGoodsSerializer extends AbstractSerializer
{
    protected $type = 'post_goods';

    public function getDefaultAttributes($model)
    {
        return [
            'user_id' => $model->user_id,
            'post_id' => $model->post_id,
            'platform_id' => $model->platform_id,
            'title' => $model->title,
            'image_path' => $model->image_path,
            'price' => $model->price,
            'type' => $model->type,
            'status' => $model->status,
            'ready_content' => $model->ready_content,
            'detail_content' => $model->detail_content,
        ];
    }

    public function user($model)
    {
        return $this->hasOne($model, UserSerializer::class);
    }

    public function post($model)
    {
        return $this->hasOne($model, PostSerializer::class);
    }
}
