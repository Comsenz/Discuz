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
     * @param int $id
     * @param User $actor
     * @param string $ability
     * @return \Illuminate\Database\Eloquent\Builder|Model
     *
     */
    public function findOrFail($id, User $actor = null, $ability = 'find')
    {
        $query = Invite::where('id', $id);

        return $this->scopeVisibleTo($query, $actor, $ability)->firstOrFail();
    }

    /**
     * Verify the invitation code is available
     * @param $code
     */
    public function verifyCode($code)
    {
        return Invite::where([['code', '=', $code], ['to_user_id', '=', '0'], ['endtime', '>', time()], ['status', '=', '1']])->first();
    }

    /**
     * get admin invite codes
     * @param User $actor
     * @return mixed
     */
    public function getAdminCodeList(User $actor)
    {
        return Invite::where([
            ['user_id', '=', $actor->id],
            ['type', '=', 2]])->get()
            ->each(function ($item, $key) {
                if ($item->status == 1) {
                    $item->to_user_id && $item->status = 2;//已使用

                    if (!$item->to_user_id && $item->endtime > time()) {
                        $item->status = 3;//未使用
                    }
                    if (!$item->to_user_id && $item->endtime < time()) {
                        $item->status = 4;//已过期
                    }
                }
            });
    }
}
