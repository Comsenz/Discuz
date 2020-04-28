<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Api\Serializer;

use Discuz\Api\Serializer\AbstractSerializer;
use Tobscure\JsonApi\Relationship;

class DialogSerializer extends AbstractSerializer
{
    protected $type = 'dialog';

    public function getDefaultAttributes($model)
    {
        return [
            'id' => $model->id,
            'dialog_message_id' => $model->dialog_message_id?:0,
            'sender_user_id' => $model->sender_user_id,
            'recipient_user_id'  => $model->recipient_user_id,
            'sender_read_at' => $this->formatDate($model->sender_read_at),
            'recipient_read_at' => $this->formatDate($model->recipient_read_at),
            'updated_at' => $this->formatDate($model->updated_at),
            'created_at' => $this->formatDate($model->created_at)
        ];
    }

    /**
     * Define the relationship with the user.
     *
     * @param $model
     * @return Relationship
     */
    public function sender($model)
    {
        return $this->hasOne($model, UserSerializer::class);
    }

    public function recipient($model)
    {
        return $this->hasOne($model, UserSerializer::class);
    }

    /**
     * Define the relationship with the dialog_message.
     *
     * @param $model
     * @return Relationship
     */
    public function dialogMessage($model)
    {
        return $this->hasOne($model, DialogMessageSerializer::class);
    }
}
