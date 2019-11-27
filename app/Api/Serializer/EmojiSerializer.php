<?php
declare(strict_types=1);

namespace App\Api\Serializer;

use Discuz\Api\Serializer\AbstractSerializer;

class EmojiSerializer extends AbstractSerializer
{
    protected $type = 'emoji';

    public function getDefaultAttributes($model)
    {
        return [
            'id' => $model->id,
            'category' => $model->category,
            'url' => $model->url,
            'code' => $model->code,
            'order' => $model->order,
        ];
    }
}