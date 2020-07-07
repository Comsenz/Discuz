<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Policies;

use App\Models\Category;
use App\Models\User;
use Discuz\Foundation\AbstractPolicy;
use Illuminate\Database\Eloquent\Builder;

class CategoryPolicy extends AbstractPolicy
{
    /**
     * {@inheritdoc}
     */
    protected $model = Category::class;

    /**
     * @param User $actor
     * @param string $ability
     * @return bool|null
     */
    public function can(User $actor, $ability)
    {
        if ($actor->hasPermission('category.' . $ability)) {
            return true;
        }
    }

    /**
     * @param User $actor
     * @param Builder $query
     */
    public function find(User $actor, Builder $query)
    {
        $query->whereIn('id', Category::getIdsWhereCan($actor, 'viewThreads'));
    }

    /**
     * @param User $actor
     * @param Category $category
     * @return bool|null
     */
    public function viewThreads(User $actor, Category $category)
    {
        if (
            $actor->hasPermission('viewThreads')
            && $actor->hasPermission('category'.$category->id.'.viewThreads')
        ) {
            return true;
        }
    }

    /**
     * @param User $actor
     * @param Category $category
     * @return bool|null
     */
    public function createThread(User $actor, Category $category)
    {
        if (
            $actor->hasPermission([
                'createThread',
                'createThreadLong',
                'createThreadVideo',
                'createThreadImage',
            ], false)
            && $actor->hasPermission('category'.$category->id.'.viewThreads')
            && $actor->hasPermission('category'.$category->id.'.createThread')
        ) {
            return true;
        }
    }

    /**
     * @param User $actor
     * @param Category $category
     * @return bool|null
     */
    public function replyThread(User $actor, Category $category)
    {
        if (
            $actor->hasPermission('thread.reply')
            && $actor->hasPermission('category'.$category->id.'.viewThreads')
            && $actor->hasPermission('category'.$category->id.'.replyThread')
        ) {
            return true;
        }
    }
}
