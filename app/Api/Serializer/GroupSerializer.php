<?php


namespace App\Api\Serializer;


use Discuz\Api\Serializer\AbstractSerializer;

class GroupSerializer extends AbstractSerializer
{

    protected $type = 'groups';

    /**
     * Get the default set of serialized attributes for a model.
     *
     * @param object|array $model
     * @return array
     */
    public function getDefaultAttributes($model)
    {
        return [
            'id' => $model->id,
            'name' => $model->name,
            'type' => $model->type,
            'color' => $model->color,
            'icon' => $model->icon
        ];
    }
}
