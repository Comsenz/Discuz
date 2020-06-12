<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Policies;

use App\Models\Category;
use App\Models\Thread;
use App\Models\User;
use App\Settings\SettingsRepository;
use Discuz\Api\Events\ScopeModelVisibility;
use Discuz\Foundation\AbstractPolicy;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Arr;
use Psr\Http\Message\ServerRequestInterface as Request;

class ThreadPolicy extends AbstractPolicy
{
    /**
     * {@inheritdoc}
     */
    protected $model = Thread::class;

    /**
     * @var Dispatcher
     */
    protected $events;

    /**
     * @var SettingsRepository
     */
    protected $settings;


    /**
     * @param Dispatcher $events
     * @param SettingsRepository $settings
     */
    public function __construct(Dispatcher $events, SettingsRepository $settings)
    {
        $this->events = $events;
        $this->settings = $settings;
    }

    /**
     * @param User $actor
     * @param string $ability
     * @param Thread $thread
     * @return bool|null
     */
    public function can(User $actor, $ability, Thread $thread)
    {
        if ($actor->hasPermission('thread.' . $ability)) {
            return true;
        }

        // 是否在当前分类下有该权限
        if ($thread->category && $actor->hasPermission('category'.$thread->category->id.'.thread.'.$ability)) {
            return true;
        }
    }

    /**
     * @param User $actor
     * @param Builder $query
     */
    public function find(User $actor, Builder $query)
    {
        // 过滤不存在用户的内容
        $query->whereExists(function ($query) {
            $query->selectRaw('1')
                ->from('users')
                ->whereColumn('threads.user_id', 'users.id');
        });

        // 隐藏不允许当前用户查看的分类内容。
        $query->whereNotIn('category_id', Category::getIdsWhereCannot($actor, 'viewThreads'));

        // 回收站
        if (! $actor->hasPermission('viewTrashed')) {
            $query->where(function (Builder $query) use ($actor) {
                $query->whereNull('threads.deleted_at')
                    // ->orWhere('threads.user_id', $actor->id) // 作者是否可见
                    ->orWhere(function ($query) use ($actor) {
                        $this->events->dispatch(
                            new ScopeModelVisibility($query, $actor, 'hide')
                        );
                    });
            });
        }

        // 未通过审核的主题
        if (! $actor->hasPermission('thread.approvePosts')) {
            $query->where(function (Builder $query) use ($actor) {
                $query->where('threads.is_approved', Thread::APPROVED)
                    ->orWhere('threads.user_id', $actor->id);
            });
        }
        $request = app('request');
        //过滤小程序视频主题
        if (!$this->settings->get('miniprogram_video', 'wx_miniprogram') &&
            strpos(Arr::get($request->getServerParams(), 'HTTP_X_APP_PLATFORM'), 'wx_miniprogram') !== false) {
            $query->where('type', '<>', Thread::TYPE_OF_VIDEO);
        }
    }

    /**
     * @param User $actor
     * @param Thread $thread
     * @return bool|null
     */
    public function edit(User $actor, Thread $thread)
    {
        if ($actor->hasPermission('editOwnThreadOrPost') && ($thread->user_id == $actor->id || $actor->isAdmin())) {
            return true;
        }
    }

    /**
     * @param User $actor
     * @param Thread $thread
     * @return bool|null
     */
    public function hide(User $actor, Thread $thread)
    {
        if ($actor->hasPermission('hideOwnThreadOrPost') && ($thread->user_id == $actor->id || $actor->isAdmin())) {
            return true;
        }
    }
}
