<?php
declare(strict_types=1);

/**
 *      Discuz & Tencent Cloud
 *      This is NOT a freeware, use is subject to license terms
 *
 *      Id: InviteRepository.php 28830 2019-11-19 15:58 yanchen $
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
    public function verifyCode($code){

        return Invite::where([['code', '=', $code], ['to_user_id', '=', '0'], ['endtime', '>', time()], ['status', '=', '1']])->first();
    }

    /**
     * Use the invitation code
     * @param $user_id
     * @param $to_user_id
     */
    public function useCode($user_id, $to_user_id){

    }

    /**
     *
     */
    public function create(){

    }
}