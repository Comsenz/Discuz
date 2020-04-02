<?php


namespace App\Api\Serializer;


use Discuz\Api\Serializer\AbstractSerializer;

class QrSerializer extends AbstractSerializer
{
    protected $type = 'qr';

    /**
     * @inheritDoc
     */
    protected function getDefaultAttributes($model)
    {
        return [
            'scene_str' => $model['scene_str'],
            'img' => $model['img']
        ];
    }

    public function getId($model)
    {
        return 1;
    }
}
