<?php


namespace App\Policies;


use App\Models\Group;
use App\Models\User;
use Discuz\Foundation\AbstractPolicy;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class GroupPolicy extends AbstractPolicy
{
    protected $model = Group::class;

    /**
     * @param User $actor
     * @param string $ability
     * @return bool|null
     */
    public function can(User $actor, $ability)
    {
        if ($actor->hasPermission('group.' . $ability)) {
            return true;
        }
    }
}
