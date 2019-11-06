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
use Tobscure\JsonApi\Relationship;

class StopWordSerializer extends AbstractSerializer
{
    /**
     * {@inheritdoc}
     */
    protected $type = 'stop-words';

    /**
     * {@inheritdoc}
     *
     * @param StopWord $model
     */
    public function getDefaultAttributes($model)
    {
        return [
            'ugc'         => $model->ugc,
            'username'    => $model->username,
            'find'        => $model->find,
            'replacement' => $model->replacement,
            'created_at'  => $this->formatDate($model->created_at),
            'updated_at'  => $this->formatDate($model->updated_at),
        ];
    }

    /**
     * @param $stopWord
     * @return Relationship
     */
    protected function user($stopWord)
    {
        return $this->hasOne($stopWord, UserSerializer::class);
    }
}
