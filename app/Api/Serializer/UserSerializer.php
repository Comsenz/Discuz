<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Api\Serializer;

use Carbon\Carbon;
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
        $attributes = [
            'id'                => $model->id,
            'username'          => $model->username,
            'mobile'            => $model->mobile,
            'mobileConfirmed'   => $model->mobile_confirmed,
            'avatarUrl'         => $model->avatar.'?'.Carbon::now()->timestamp,
            'threadCount'       => $model->thread_count,
            'registerIp'        => $model->register_ip,
            'lastLoginIp'       => $model->last_login_ip,
            'status'            => $model->status,
            'joinedAt'          => $this->formatDate($model->joined_at),
            'expiredAt'         => $this->formatDate($model->expired_at),
            'createdAt'         => $this->formatDate($model->created_at),
            'updatedAt'         => $this->formatDate($model->updated_at),
        ];

        if($this->actor->isAdmin()) {
            $attributes['originalMobile'] = $model->getOriginal('mobile');
        }

        return $attributes;
    }

    public function wechat($user)
    {
        return $this->hasOne($user, UserWechatSerializer::class);
    }

    public function groups($user)
    {
        return $this->hasMany($user, GroupSerializer::class);
    }
}
