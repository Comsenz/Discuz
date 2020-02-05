<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Api\Serializer;

use Carbon\Carbon;
use Discuz\Api\Serializer\AbstractSerializer;
use Illuminate\Contracts\Auth\Access\Gate;
use Tobscure\JsonApi\Relationship;

class UserSerializer extends AbstractSerializer
{
    /**
     * {@inheritdoc}
     */
    protected $type = 'users';

    /**
     * @var Gate
     */
    protected $gate;

    /**
     * @param Gate $gate
     */
    public function __construct(Gate $gate)
    {
        $this->gate = $gate;
    }

    /**
     * {@inheritdoc}
     */
    public function getDefaultAttributes($model)
    {
        $gate = $this->gate->forUser($this->actor);

        $canEdit = $gate->allows('edit', $model);

        $attributes = [
            'id'                => (int) $model->id,
            'username'          => $model->username,
            'mobile'            => $model->mobile,
            'avatarUrl'         => $model->avatar ? $model->avatar . '?' . Carbon::parse($model->avatar_at)->timestamp : '',
            'threadCount'       => (int) $model->thread_count,
            'followCount'      => (int) $model->follow_count,
            'fansCount'        => (int) $model->fans_count,
            'status'            => $model->status,
            'loginAt'           => $this->formatDate($model->login_at),
            'joinedAt'          => $this->formatDate($model->joined_at),
            'expiredAt'         => $this->formatDate($model->expired_at),
            'createdAt'         => $this->formatDate($model->created_at),
            'updatedAt'         => $this->formatDate($model->updated_at),
            'canEdit'           => $canEdit,
            'canDelete'         => $gate->allows('delete', $model),
            'registerReason'    => $model->register_reason,
        ];

        if ($canEdit || $this->actor->id === $model->id) {
            $attributes += [
                'originalMobile'    => $model->getOriginal('mobile'),
                'mobileConfirmed'   => $model->mobile_confirmed,
                'registerIp'        => $model->register_ip,
                'lastLoginIp'       => $model->last_login_ip,
                'identity'          => $model->identity,
                'realname'         => $model->realname,
            ];
        }

        return $attributes;
    }

    /**
     * @param $user
     * @return Relationship
     */
    public function wechat($user)
    {
        return $this->hasOne($user, UserWechatSerializer::class);
    }

    /**
     * @param $user
     * @return Relationship
     */
    public function groups($user)
    {
        return $this->hasMany($user, GroupSerializer::class);
    }
}
