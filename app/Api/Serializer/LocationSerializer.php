<?php


namespace App\Api\Serializer;


use Discuz\Api\Serializer\AbstractSerializer;

class LocationSerializer extends AbstractSerializer
{

    protected $type = 'location';

    /**
     * @inheritDoc
     */
    protected function getDefaultAttributes($model)
    {
        return [
            'location' => $model['location']
        ];
    }

    public function getId($model)
    {
        return 1;
    }
}
