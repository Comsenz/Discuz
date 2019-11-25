<?php


namespace App\Policies;


use App\Models\User;
use Discuz\Foundation\AbstractPolicy;
use Illuminate\Database\Eloquent\Builder;

class UserPolicy extends AbstractPolicy
{
    protected $model = User::class;

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
