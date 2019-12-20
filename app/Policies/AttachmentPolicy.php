<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Policies;

use App\Models\Attachment;
use App\Models\User;
use Discuz\Foundation\AbstractPolicy;
use Illuminate\Database\Eloquent\Builder;

class AttachmentPolicy extends AbstractPolicy
{
    /**
     * {@inheritdoc}
     */
    protected $model = Attachment::class;

    /**
     * @param User $actor
     * @param string $ability
     * @return bool|null
     */
    public function can(User $actor, $ability)
    {
        if ($actor->hasPermission('attachment.' . $ability)) {
            return true;
        }
    }

    /**
     * @param User $actor
     * @param Builder $query
     */
    public function find(User $actor, Builder $query)
    {
        if ($actor->cannot('viewAttachments')) {
            $query->whereRaw('FALSE');

            return;
        }

        // 已绑定到帖子上的附件
        $query->where('post_id', '<>', 0);
    }
}
