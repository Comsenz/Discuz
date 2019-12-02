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

/**
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
 * @package App\Models
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
     * @param $name
     * @param $description
     * @param $sort
     * @param string $icon
     * @param string $ip
     * @return static
     */
    public static function build($name, $description, $sort, $icon = '', $ip = '')
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
}
