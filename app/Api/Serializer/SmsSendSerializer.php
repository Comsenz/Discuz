<?php


namespace App\Api\Serializer;


use Discuz\Api\Serializer\AbstractSerializer;

class SmsSendSerializer extends AbstractSerializer
{

    protected $type = 'smssend';

    /**
     * Get the default set of serialized attributes for a model.
     *
     * @param object|array $model
     * @return array
     */
    protected function getDefaultAttributes($model)
    {
        return [
            'interval' => $model['interval']
        ];
    }

    public function getId($model)
    {
        return 1;
    }
}
