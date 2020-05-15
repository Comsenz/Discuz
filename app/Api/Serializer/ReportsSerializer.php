<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Api\Serializer;

use Discuz\Api\Serializer\AbstractSerializer;
use Tobscure\JsonApi\Relationship;

class ReportsSerializer extends AbstractSerializer
{
    /**
     * {@inheritdoc}
     */
    protected $type = 'reports';

    /**
     * {@inheritdoc}
     */
    protected function getDefaultAttributes($model)
    {
        return [
            'user_id' => $model->user_id,
            'thread_id' => $model->thread_id,
            'post_id' => $model->post_id,
            'type' => $model->type, // 举报类型:0个人主页 1主题 2评论/回复
            'reason' => $model->reason,
            'status' => $model->status,
            'created_at' => $this->formatDate($model->created_at),
            'updated_at' => $this->formatDate($model->updated_at),
        ];
    }

    /**
     * @param $report
     * @return Relationship
     */
    protected function user($report)
    {
        return $this->hasOne($report, UserSerializer::class);
    }
}
