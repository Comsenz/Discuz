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
            'id'                => $model->id,
            'username'          => $model->username,
            'mobile'            => $model->mobile,
            'mobileConfirmed'   => $model->mobile_confirmed,
            'avatarUrl'         => $model->avatar,
            'threadCount'       => $model->thread_count,
            'registerIp'        => $model->register_ip,
            'lastLoginIp'       => $model->last_login_ip,
            'status'            => $model->status,
            'joinedAt'          => $this->formatDate($model->joined_at),
            'expiredAt'         => $this->formatDate($model->expired_at),
            'createdAt'         => $this->formatDate($model->created_at),
            'updatedAt'         => $this->formatDate($model->updated_at),
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
