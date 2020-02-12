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
            'dialog_message_id' => $model->dialog_message_id,
            'sender_user_id' => $model->sender_user_id,
            'recipient_user_id'  => $model->recipient_user_id,
            'updated_at' => $this->formatDate($model->updated_at),
            'created_at' => $this->formatDate($model->created_at)
        ];
    }

    /**
     * Define the relationship with the from_user.
     *
     * @param $model
     * @return Relationship
     */
    public function user($model)
    {
        return $this->hasOne($model, UserSerializer::class);
    }
}
