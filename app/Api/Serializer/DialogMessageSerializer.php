<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Api\Serializer;

use Discuz\Api\Serializer\AbstractSerializer;
use Tobscure\JsonApi\Relationship;

class DialogMessageSerializer extends AbstractSerializer
{
    protected $type = 'dialog_message';

    public function getDefaultAttributes($model)
    {
        return [
            'user_id' => $model->user_id,
            'dialog_id' => $model->dialog_id,
            'attachment_id' => $model->attachment_id,
            'summary' => $model->summary,
            'message_text' => $model->message_text,
            'message_text_html'  => $model->formatMessageText(),
            'updated_at' => $this->formatDate($model->updated_at),
            'created_at' => $this->formatDate($model->created_at)
        ];
    }

    /**
     * User
     * @param $model
     * @return Relationship
     */
    public function user($model)
    {
        return $this->hasOne($model, UserSerializer::class);
    }

    /**
     * Attachment
     * @param $model
     * @return Relationship
     */
    public function attachment($model)
    {
        return $this->hasOne($model, AttachmentSerializer::class);
    }
}
