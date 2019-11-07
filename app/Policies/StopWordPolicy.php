<?php

/**
 *      Discuz & Tencent Cloud
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: StopWordPolicy.php xxx 2019-11-05 14:04:00 LiuDongdong $
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
