<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Api\Serializer;

use Discuz\Api\Serializer\AbstractSerializer;

class CategorySerializer extends AbstractSerializer
{
    /**
     * {@inheritdoc}
     */
    protected $type = 'categories';

    /**
     * {@inheritdoc}
     */
    protected function getDefaultAttributes($model)
    {
        return [
            'name'              => $model->name,
            'description'       => $model->description,
            'icon'              => $model->icon,
            'sort'              => $model->sort,
            'property'          => $model->property,
            'thread_count'      => (int) $model->thread_count,
            'ip'                => $model->ip,
            'created_at'        => $this->formatDate($model->created_at),
            'updated_at'        => $this->formatDate($model->updated_at),
            'canViewThreads'    => $this->actor->can('viewThreads', $model),
            'canCreateThread'   => $this->actor->can('createThread', $model),
            'canReplyThread'    => $this->actor->can('replyThread', $model),
        ];
    }
}
