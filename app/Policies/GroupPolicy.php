<?php


namespace App\Policies;


use App\Models\Group;
use App\Models\User;
use Discuz\Foundation\AbstractPolicy;
use Illuminate\Database\Eloquent\Model;

class GroupPolicy extends AbstractPolicy
{
    protected $model = Group::class;

    /**
     * @param User $actor
     * @param Model $model
     * @param string $ability
     * @return bool|null
     */
    public function canPermission(User $actor, Model $model, $ability)
    {
        if($actor->hasPermission('group.'.$ability)) {
            return true;
        }
    }
}
