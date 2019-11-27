<?php


namespace App\Api\Serializer;


use Discuz\Api\Serializer\AbstractSerializer;

class SettingSerializer extends AbstractSerializer
{

    protected $type = 'settings';

    /**
     * Get the default set of serialized attributes for a model.
     *
     * @param object|array $model
     * @return array
     */
    public function getDefaultAttributes($model)
    {
        return [
            $model['tag'] => [
                $model['key'] => $model['value']
            ]
        ];
    }

    public function getId($model)
    {
        return 1;
    }
}
