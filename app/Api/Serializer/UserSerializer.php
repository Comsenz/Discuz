<?php

namespace App\Api\Serializer;

use Discuz\Api\Serializer\AbstractSerializer;

class UserSerializer extends AbstractSerializer
{
    /**
     * {@inheritdoc}
     */
    protected $type = 'users';

    /**
     * {@inheritdoc}
     */
    public function getDefaultAttributes($model)
    {
        return [
            'id' => $model->id,
            'username'    => $model->username,
//            'nickname'    => $model->userWechats?$model->userWechats->nickname:$model->userWechats,
            'mobile'      => $model->mobile,
            // 'avatarUrl'   => $model->avatar_url,
//            'unionId'     => $model->union_id,
            'threadCount' => $model->thread_count,
            'lastLoginIp' => $model->last_login_ip,
            'createdAt'   => $this->formatDate($model->created_at),
            'updatedAt'   => $this->formatDate($model->updated_at),
        ];
    }

    public function wechat($user)
    {
        return $this->hasOne($user, UserWechatSerializer::class);
    }

    public function groups($user)
    {
        return $this->hasMany($user,GroupSerializer::class);
    }
}
