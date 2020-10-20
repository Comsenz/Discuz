<?php


namespace App\Repositories;

use App\Models\CreditScoreRule;
use App\Models\User;
use Discuz\Foundation\AbstractRepository;
use Illuminate\Database\Eloquent\Model;

class CreditScoreRuleRepository extends AbstractRepository
{
    /**
     * Get a new query builder for the posts table.
     *
     * @return Model|\Illuminate\Database\Eloquent\Builder
     */
    public static function query()
    {
        return CreditScoreRule::query();
    }

    public function all()
    {
        return CreditScoreRule::all();
    }

    public function get($id)
    {
        return (new CreditScoreRule())->newModelQuery()->where('id', $id)->get();
    }



    public function findOrFail($id, User $actor = null)
    {
        $query = CreditScoreRule::where('id', $id);

        return $this->scopeVisibleTo($query, $actor)->firstOrFail();
    }


}
