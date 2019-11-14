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
            'key' => $model->key,
            'value' => $model->value,
            'tag' => $model->tag
        ];
    }

    public function getId($model)
    {
        return $model->key;
    }
}
