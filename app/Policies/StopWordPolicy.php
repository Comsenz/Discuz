<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Policies;

use App\Models\StopWord;
use App\Models\User;
use Discuz\Foundation\AbstractPolicy;

class StopWordPolicy extends AbstractPolicy
{
    /**
     * {@inheritdoc}
     */
    protected $model = StopWord::class;

    /**
     * @param User $actor
     * @param string $ability
     * @return bool|null
     */
    public function can(User $actor, $ability)
    {
        if ($actor->hasPermission('stopWord.' . $ability)) {
            return true;
        }
    }
}
