<?php

namespace App\Api\Serializer;

use Discuz\Api\Serializer\AbstractSerializer;

class SettingTags extends AbstractSerializer
{
    protected $type = 'settings_tags';

    /**
     * Get the default set of serialized attributes for a model.
     *
     * @param object|array $model
     * @return array
     */
    public function getDefaultAttributes($model)
    {
        $collModel = collect($model);

        $model = $collModel->pluck('value', 'key')->toArray();

        return $model;
    }

    public function getId($model)
    {
        return $model[0]['tag'];
    }
}
