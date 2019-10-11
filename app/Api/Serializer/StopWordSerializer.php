<?php

/**
 *      Discuz & Tencent Cloud
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: StopWordSerializer.php xxx 2019-09-26 16:22:00 LiuDongdong $
 */

namespace App\Api\Serializer;

use App\Models\StopWord;
use Discuz\Api\Serializer\AbstractSerializer;

class StopWordSerializer extends AbstractSerializer
{
    /**
     * {@inheritdoc}
     */
    protected $type = 'StopWord';

    /**
     * {@inheritdoc}
     *
     * @param StopWord $model
     */
    public function getDefaultAttributes($model)
    {
        return [
            'id'          => (int) $model->id,
            'user_id'     => (int) $model->user_id,
            'ugc'         => $model->ugc,
            'username'    => $model->username,
            'find'        => $model->find,
            'replacement' => $model->replacement,
            'created_at'  => $this->formatDate($model->created_at),
            'updated_at'  => $this->formatDate($model->updated_at),
        ];
    }
}
