<?php


namespace App\Policies;


use App\Models\User;
use Discuz\Foundation\AbstractPolicy;
use Illuminate\Database\Eloquent\Builder;

class UserPolicy extends AbstractPolicy
{
    protected $model = User::class;

    /**
     * @param User $actor
     * @param string $ability
     * @return bool|null
     */
    public function can(User $actor, $ability)
    {
        if ($actor->hasPermission('user.' . $ability)) {
            return true;
        }
    }

    public function find(User $actor, Builder $query) {

        if($actor->isAdmin())
        {
            return;
        }

        if($actor->cannot('viewUserList')) {
            $query->whereRaw('FALSE');
        }
    }
}
