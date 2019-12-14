<?php

/**
 *      Discuz & Tencent Cloud
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: Category.php xxx 2019-11-29 16:38:00 LiuDongdong $
 */

namespace App\Models;

use App\Events\Category\Created;
use Carbon\Carbon;
use Discuz\Database\ScopeVisibilityTrait;
use Discuz\Foundation\EventGeneratorTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @package App\Models
 *
 * @property int $id
 * @property string $name
 * @property string $description
 * @property string $icon
 * @property int $sort
 * @property int $property
 * @property int thread_count
 * @property string ip
 * @property Carbon updated_at
 * @property Carbon created_at
 * @method truncate()
 * @method insert(array $array)
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
     * Refresh the thread's comments count.
     *
     * @return $this
     */
    public function refreshThreadCount()
    {
        $this->thread_count = $this->threads()
            ->where('is_approved', 1)
            ->whereNull('deleted_at')
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
}
