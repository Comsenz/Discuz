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
            'avatarUrl'         => $this->getAvatarUrl($model),
            'threadCount'       => (int) $model->thread_count,
            'followCount'       => (int) $model->follow_count,
            'fansCount'         => (int) $model->fans_count,
            'follow'            => $model->follow,
            'status'            => $model->status,
            'loginAt'           => $this->formatDate($model->login_at),
            'joinedAt'          => $this->formatDate($model->joined_at),
            'expiredAt'         => $this->formatDate($model->expired_at),
            'createdAt'         => $this->formatDate($model->created_at),
            'updatedAt'         => $this->formatDate($model->updated_at),
            'canEdit'           => $canEdit,
            'canDelete'         => $gate->allows('delete', $model),
            'registerReason'    => $model->register_reason,     // 注册原因
            'banReason'         => '',                          // 禁用原因
        ];

        // 判断禁用原因
        if ($model->status == 1) {
            $attributes['banReason'] = !empty($model->latelyLog) ? $model->latelyLog->message : '' ;
        }

        // 限制字段 本人/权限 显示
        if ($canEdit || $this->actor->id === $model->id) {
            $attributes += [
                'originalMobile'    => $model->getRawOriginal('mobile'),
                'mobileConfirmed'   => $model->mobile_confirmed,
                'registerIp'        => $model->register_ip,
                'lastLoginIp'       => $model->last_login_ip,
                'identity'          => $model->identity,
                'realname'          => $model->realname,
                'mobile'            => $model->mobile,
            ];
        }

        // 钱包余额
        if ($this->actor->id === $model->id) {
            $attributes += [
                'canWalletPay'  => $gate->allows('walletPay', $model),
                'walletBalance' => $model->userWallet->available_amount,
            ];
        }

        return $attributes;
    }

    /**
     * 判断头像 - 是否用微信头像
     *
     * @param $model
     * @return string
     */
    public function getAvatarUrl($model)
    {
        $model->load('wechat');

        $avatar = '';
        if (empty($model->avatar)) {
            if (!empty($model->wechat)) {
                $avatar = $model->wechat->headimgurl;
            }
        } else {
            $avatar = $model->avatar;
        }

        return !empty($avatar) ? $avatar . '?' . Carbon::parse($model->avatar_at)->timestamp : '';
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
