<?php


namespace App\Api\Serializer;


use Discuz\Api\Serializer\AbstractSerializer;

class ForumSettingSerializer extends AbstractSerializer
{

    protected $type = 'forum';

    /**
     * Get the default set of serialized attributes for a model.
     *
     * @param object|array $model
     * @return array
     */
    public function getDefaultAttributes($model)
    {
        return $model;
    }

    public function getId($model)
    {
        return 1;
    }
}
