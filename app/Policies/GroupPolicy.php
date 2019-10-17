<?php


namespace App\Policies;


use App\Models\Group;
use Discuz\Foundation\AbstractPolicy;
use Illuminate\Database\Eloquent\Model;

class GroupPolicy extends AbstractPolicy
{
    protected $model = Group::class;

    /**
     * @param Model $actor
     * @param Model $model
     * @param string $ability
     * @return bool
     */
    public function canPermission(Model $actor, Model $model, $ability): bool
    {
        if($actor->hasPermission('group.'.$ability)) {
            return true;
        }
    }
}
