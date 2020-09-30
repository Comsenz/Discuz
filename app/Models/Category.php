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

namespace App\Models;

use App\Events\Category\Created;
use Carbon\Carbon;
use Discuz\Database\ScopeVisibilityTrait;
use Discuz\Foundation\EventGeneratorTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property string $name
 * @property string $description
 * @property string $icon
 * @property int $sort
 * @property int $property
 * @property int $thread_count
 * @property array $moderators
 * @property string $ip
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
class Category extends Model
{
    use EventGeneratorTrait;
    use ScopeVisibilityTrait;

    /**
     * {@inheritdoc}
     */
    protected $dates = ['created_at', 'updated_at'];

    /**
     * Create a new category.
     *
     * @param string $name
     * @param string $description
     * @param int $sort
     * @param string $icon
     * @param string $ip
     * @return static
     */
    public static function build(string $name, string $description, int $sort, string $icon = '', string $ip = '')
    {
        $category = new static;

        $category->name = $name;
        $category->description = $description;
        $category->sort = $sort;
        $category->icon = $icon;
        $category->ip = $ip;

        $category->raise(new Created($category));

        return $category;
    }

    /**
     * @param string $value
     * @return array
     */
    public function getModeratorsAttribute($value)
    {
        return explode(',', $value);
    }

    /**
     * @param $value
     */
    public function setModeratorsAttribute($value)
    {
        $this->attributes['moderators'] = is_array($value) ? implode(',', $value) : $value;
    }

    /**
     * Refresh the thread's comments count.
     *
     * @return $this
     */
    public function refreshThreadCount()
    {
        $this->thread_count = $this->threads()
            ->where('is_approved', Thread::APPROVED)
            ->whereNull('deleted_at')
            ->whereNotNull('user_id')
            ->count();

        return $this;
    }

    /**
     * Define the relationship with the category's threads.
     *
     * @return HasMany
     */
    public function threads()
    {
        return $this->hasMany(Thread::class);
    }

    /**
     * @param User $user
     * @param string $permission
     * @param bool $condition
     * @return array
     */
    protected static function getIdsWherePermission(User $user, string $permission, bool $condition = true): array
    {
        static $categories;

        if (! $categories) {
            $categories = static::all();
        }

        if ($permission === 'createThread') {
            $hasGlobalPermission = $user->hasPermission([
                'createThread',
                'createThreadLong',
                'createThreadVideo',
                'createThreadImage',
                'createThreadAudio',
            ], false);
        } else {
            $hasGlobalPermission = $user->hasPermission($permission);
        }

        $canForCategory = function (self $category) use ($user, $permission, $hasGlobalPermission) {
            return $hasGlobalPermission && $user->hasPermission('category'.$category->id.'.'.$permission);
        };

        $ids = [];

        foreach ($categories as $category) {
            if ($canForCategory($category) === $condition) {
                $ids[] = $category->id;
            }
        }

        return $ids;
    }

    public static function getIdsWhereCan(User $user, string $permission): array
    {
        return static::getIdsWherePermission($user, $permission, true);
    }

    public static function getIdsWhereCannot(User $user, string $permission): array
    {
        return static::getIdsWherePermission($user, $permission, false);
    }
}
