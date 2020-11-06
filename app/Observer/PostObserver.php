<?php

/**
 * Copyright (C) 2020 Tencent Cloud.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

namespace App\Observer;

use App\Models\Post;
use App\Models\PostUser;
use App\Models\Thread;
use Discuz\Contracts\Setting\SettingsRepository;
use Illuminate\Database\Query\Builder;

class PostObserver
{
    /**
     * @var SettingsRepository
     */
    protected $settings;

    /**
     * @param SettingsRepository $settings
     */
    public function __construct(SettingsRepository $settings)
    {
        $this->settings = $settings;
    }

    /**
     * @param Post $post
     */
    public function created(Post $post)
    {
        if ($post->is_first) {
            if ($post->thread->is_approved === Thread::UNAPPROVED) {
                $post->is_approved = $post->thread->is_approved;

                $post->save();
            } else {
                $post->thread->is_approved = $post->is_approved;

                $post->thread->save();
            }
        }

        $this->refreshSitePostCount();
    }

    /**
     * @param Post $post
     */
    public function updated(Post $post)
    {
        if ($post->wasChanged(['is_approved', 'deleted_at'])) {
            if ($post->is_first) {
                $post->thread->is_approved = $post->is_approved;
                $post->thread->deleted_at = $post->deleted_at;
                $post->thread->deleted_user_id = $post->deleted_user_id;

                $post->thread->save();

                $this->refreshUserLikedCount($post);
            }

            $this->refreshSitePostCount();
        }

        if ($post->is_first && $post->wasChanged('updated_at')) {
            $post->thread->updated_at = $post->updated_at;

            $post->thread->save();
        }
    }

    /**
     * @param Post $post
     */
    public function deleted(Post $post)
    {
        $this->refreshUserLikedCount($post);
        $this->refreshSitePostCount();
    }

    /**
     * 刷新点赞该主题的用户点赞数
     *
     * @param Post $post
     */
    private function refreshUserLikedCount(Post $post)
    {
        if (! $post->is_first) {
            return;
        }

        $query = new Builder($post->getConnection());

        $userTable = $query->getGrammar()->wrapTable('users');

        $likes = PostUser::query()
            ->addSelect('post_user.user_id')
            ->selectRaw('COUNT(*) as `count`')
            ->leftJoin('posts', 'post_user.post_id', '=', 'posts.id')
            ->whereNull('posts.deleted_at')
            ->where('posts.is_first', true)
            ->where('posts.is_approved', Post::APPROVED)
            ->whereIn('post_user.user_id', PostUser::query()->where('post_id', $post->id)->pluck('user_id'))
            ->groupBy('post_user.user_id');

        /**
         * SQL:
         * UPDATE `users`,
         * (
         *   SELECT
         *     `post_user`.`user_id`,
         *     COUNT( * ) AS `count`
         *   FROM
         *     `post_user`
         *     LEFT JOIN `posts` ON `post_user`.`post_id` = `posts`.`id`
         *   WHERE
         *     `posts`.`deleted_at` IS NULL
         *     AND `posts`.`is_first` = 1
         *     AND `posts`.`is_approved` = 1
         *     AND `post_user`.`user_id` IN ( ?, ? )
         *   GROUP BY
         *     `post_user`.`user_id`
         *   ) AS likes
         *   SET `liked_count` = `count`
         * WHERE
         *   `users`.`id` = `likes`.`user_id`
         */
        $query->fromRaw("{$userTable}, ({$likes->toSql()}) as likes", $likes->getBindings())
            ->whereRaw("{$userTable}.`id` = `likes`.`user_id`")
            ->update([
                'liked_count' => $query->raw('`count`')
            ]);
    }

    /**
     * 刷新站点回复数
     */
    private function refreshSitePostCount()
    {
        $this->settings->set(
            'post_count',
            Post::query()
                ->where('is_approved', Post::APPROVED)
                ->whereNull('deleted_at')
                ->whereNotNull('user_id')
                ->count()
        );
    }
}
