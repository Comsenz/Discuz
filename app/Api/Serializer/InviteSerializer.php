<?php
declare(strict_types=1);

/**
 *      Discuz & Tencent Cloud
 *      This is NOT a freeware, use is subject to license terms
 *
 *      Id: InviteSerializer.php 28830 2019-10-12 15:50 chenkeke $
 */

namespace App\Api\Serializer;


use Discuz\Api\Serializer\AbstractSerializer;

class InviteSerializer extends AbstractSerializer
{
    protected $type = 'invite';

    public function getDefaultAttributes($model)
    {
        return [
            'id' => $model->id,
            'user_group_id' => $model->user_group_id,
            'code' => $model->code,
            'dateline' => $model->dateline,
            'endtime' => $model->endtime,
            'user_id' => $model->user_id,
            'to_user_id' => $model->to_user_id,
            'status' => $model->status
        ];
    }
}