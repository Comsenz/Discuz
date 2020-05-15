<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Repositories;

use App\Models\Invite;
use App\Models\User;
use Discuz\Foundation\AbstractRepository;
use Illuminate\Database\Eloquent\Model;

class InviteRepository extends AbstractRepository
{
    /**
     * @return Model|\Illuminate\Database\Eloquent\Builder
     */
    public static function query()
    {
        return Invite::query();
    }

    /**
     * @param int $id
     * @param User|null $actor
     * @return \Illuminate\Database\Eloquent\Builder|Model
     */
    public function findOrFail($id, User $actor = null)
    {
        $query = self::query()->where('id', $id);

        return $this->scopeVisibleTo($query, $actor)->firstOrFail();
    }

    /**
     * Verify the invitation code is available
     *
     * @param $code
     * @return mixed
     */
    public function verifyCode($code)
    {
        return self::query()->where([
            ['code', '=', $code],
            ['to_user_id', '=', '0'],
            ['endtime', '>', time()],
            ['status', '=', '1']
        ])->first();
    }
}
