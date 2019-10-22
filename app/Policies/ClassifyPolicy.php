<?php
declare(strict_types=1);

/**
 *      Discuz & Tencent Cloud
 *      This is NOT a freeware, use is subject to license terms
 *
 *      Id: ClassifyPolicy.php 28830 2019-10-14 11:52 chenkeke $
 */

namespace App\Policies;


use App\Models\Classify;
use App\Models\User;
use Discuz\Foundation\AbstractPolicy;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class ClassifyPolicy extends AbstractPolicy
{
    /**
     * {@inheritdoc}
     */
    protected $model = Classify::class;

    /**
     * @param User $actor
     * @param Model $model
     * @param string $ability
     * @return bool|null
     */
    public function canPermission(User $actor, Model $model, $ability)
    {

    }

    /**
     * @param Model $actor
     * @param Builder $query
     * @return void
     */
    public function findVisibility(Model $actor, Builder $query)
    {

    }

    /**
     * @param Model $actor
     * @param Builder $query
     * @return void
     */
    public function findEditVisibility(Model $actor, Builder $query)
    {

    }

}
