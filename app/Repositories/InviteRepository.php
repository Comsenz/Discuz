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
     * Get a new query builder for the posts table.
     *
     * @return Model|\Illuminate\Database\Eloquent\Builder
     */
    public static function query()
    {
        return Invite::query();
    }

    /**
     * Find a user by ID, optionally making sure it is visible to a certain
     * user, or throw an exception.
     *
     * @param $id
     * @param User|null $actor
     * @param string $ability
     * @return \Illuminate\Database\Eloquent\Builder|Model
     */
    public function findOrFail($id, User $actor = null, $ability = 'find')
    {
        $query = self::query()->where('id', $id);

//        return $this->scopeVisibleTo($query, $actor, $ability)->firstOrFail();
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

    /**
     * get admin invite codes
     * @param User $actor
     * @param $data
     * @return mixed
     */
    public function getAdminCodeList(User $actor, $data)
    {
        $query = self::query();

        $query->skip($data['offset'])->take($data['limit']);

        $query = $query->where([
            ['user_id', '=', $actor->id],
            ['type', '=', 2]
        ])->orderBy('id', 'desc')->get();

        $query->each(function ($item) {
            if ($item->status == 1) {
                if (!$item->to_user_id && $item->endtime < time()) {
                    $item->status = 3; // 已过期
                }
            }
        });

        return $query;
    }
}
