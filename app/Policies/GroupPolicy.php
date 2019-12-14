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
     * @param Model $model
     * @param string $ability
     * @return bool|null
     */
    public function canPermission(User $actor, Model $model, $ability)
    {
        if($actor->hasPermission($this->getAbility($ability))) {
            return true;
        }
    }

    /**
     * @param User $actor
     * @param Builder $query
     * @return void
     */
    public function findVisibility(User $actor, Builder $query)
    {
        // 当前用户是否有权限查看用户组
        if ($actor->cannot($this->getAbility('viewGroup'))) {
            // $query->whereRaw('FALSE');
            dd('aa');
            $query->where(function ($query) use ($actor) {
                dd($actor);
                $query->selectRaw('1')
                    ->from('group_user')
                    ->where('group_user.user_id', $actor->id)
                    ->whereColumn('group_user.group_id', 'groups.id');
            });
            return;
        }
    }
}
