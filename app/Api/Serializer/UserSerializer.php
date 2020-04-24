<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Api\Serializer;

use App\Models\User;
use App\Repositories\UserFollowRepository;
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

    protected $userFollow;

    /**
     * @param Gate $gate
     * @param UserFollowRepository $userFollow
     */
    public function __construct(Gate $gate, UserFollowRepository $userFollow)
    {
        $this->gate = $gate;
        $this->userFollow = $userFollow;
    }

    /**
     * {@inheritdoc}
     *
     * @param User $model
     */
    public function getDefaultAttributes($model)
    {
        $gate = $this->gate->forUser($this->actor);

        $canEdit = $gate->allows('edit', $model);

        $attributes = [
            'id'                => (int) $model->id,
            'username'          => $model->username,
            'avatarUrl'         => $this->getAvatarUrl($model),
            'isReal'            => $this->getIsReal($model),
            'threadCount'       => (int) $model->thread_count,
            'followCount'       => (int) $model->follow_count,
            'fansCount'         => (int) $model->fans_count,
            'likedCount'        => (int) $model->liked_count,
            'signature'         => $model->signature,
            'usernameBout'     => (int) $model->username_bout,
            'follow'            => $this->userFollow->findFollowDetail($this->actor->id, $model->id), //TODO 解决N+1
            'status'            => $model->status,
            'loginAt'           => $this->formatDate($model->login_at),
            'joinedAt'          => $this->formatDate($model->joined_at),
            'expiredAt'         => $this->formatDate($model->expired_at),
            'createdAt'         => $this->formatDate($model->created_at),
            'updatedAt'         => $this->formatDate($model->updated_at),
            'canEdit'           => $canEdit,
            'canDelete'         => $gate->allows('delete', $model),
            'showGroups'        => $model->hasPermission('showGroups'),     // 是否显示用户组
            'registerReason'    => $model->register_reason,                 // 注册原因
            'banReason'         => '',                                      // 禁用原因
        ];

        // 判断禁用原因
        if ($model->status == 1) {
            $attributes['banReason'] = !empty($model->latelyLog) ? $model->latelyLog->message : '' ;
        }

        // 限制字段 本人/权限 显示
        if ($canEdit || $this->actor->id === $model->id) {
            $attributes += [
                'originalMobile'    => $model->getRawOriginal('mobile'),
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
     * 获取头像地址
     *
     * @param User $model
     * @return string
     */
    public function getAvatarUrl(User $model)
    {
        return $model->avatar ? $model->avatar . '?' . Carbon::parse($model->avatar_at)->timestamp : '';
    }

    /**
     * 是否实名认证
     *
     * @param User $model
     * @return string
     */
    public function getIsReal(User $model)
    {
        if (isset($model->realname) && $model->realname != null) {
            return true;
        } else {
            return false;
        }
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
