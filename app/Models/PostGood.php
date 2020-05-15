<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Models;

use Carbon\Carbon;
use Discuz\Database\ScopeVisibilityTrait;
use Discuz\Foundation\EventGeneratorTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Arr;

/**
 * @package App\Models
 *
 * @property int $user_id
 * @property int $post_id
 * @property string $platform_id
 * @property string $title
 * @property float $price
 * @property string $image_path
 * @property int $type
 * @property int $status
 * @property string $ready_content
 * @property string $detail_content
 * @property Carbon updated_at
 * @property Carbon created_at
 * @property Carbon deleted_at
 * @method static create(array $array)
 */
class PostGood extends Model
{
    use EventGeneratorTrait;
    use ScopeVisibilityTrait;

    public static $key;

    /**
     * 允许的域名
     *  0淘宝 1天猫 2京东 3拼多多H5 4有赞 5淘宝口令粘贴值 6京东粘贴值H5域名 7有赞粘贴值
     *
     * @var array[]
     */
    protected static $domainName = [
        0 => 'taobao',      // 淘宝
        1 => 'tmall',       // 天猫
        2 => 'jd',          // 京东
        3 => 'yangkeduo',   // 拼多多H5
        4 => 'm.youzan',    // 有赞
        5 => 'm.tb',        // 淘宝口令粘贴值
        6 => 'm.jd',        // 京东粘贴值H5域名
        7 => 'youzan',      // 有赞粘贴值
    ];

    /**
     * Create a new PostGoods
     *
     * @param int $user_id
     * @param int $post_id
     * @param string $platform_id
     * @param string $title
     * @param float $price
     * @param string $imagePath
     * @param int $type
     * @param int $status
     * @param string $readyContent
     * @param string $detailContent
     * @return bool
     */
    public static function store(
        int $user_id,
        int $post_id,
        string $platform_id,
        string $title,
        float $price,
        string $imagePath,
        int $type,
        int $status,
        string $readyContent,
        string $detailContent
    ) {
        $goods = new static;

        $goods->user_id = $user_id;
        $goods->post_id = $post_id;
        $goods->platform_id = $platform_id;
        $goods->title = $title;
        $goods->price = $price;
        $goods->image_path = $imagePath;
        $goods->type = $type;
        $goods->status = $status;
        $goods->ready_content = $readyContent;
        $goods->detail_content = $detailContent;

        return $goods->save();
    }

    /**
     * 根据 值/类型 判断是否存在
     *
     * @param $mixed
     * @param callable|null $callback 回调 值/字符串 内容
     * @return bool
     */
    public static function enumType($mixed, callable $callback = null) : bool
    {
        $domain = static::$domainName;

        if (is_numeric($mixed)) {
            self::$key = $mixed;
            if ($result = array_key_exists($mixed, $domain) && !is_null($callback)) {
                $callback(['key' => $mixed, 'value' => $domain[$mixed]]);
            }
        } elseif (is_string($mixed)) {
            if ($result = in_array($mixed, $domain)) {
                self::$key = array_search($mixed, $domain);
                $callback ? $callback(['key' => self::$key, 'value' => $mixed]) : null;
            }
        } elseif (is_array($mixed)) {
            // 单独处理口令
            if (in_array('m', $mixed)) {
                self::passwordValueChange($mixed);
            }

            $result = array_walk($domain, function ($value, $key) use ($domain, $mixed, $callback) {
                if (in_array($value, $mixed)) {
                    self::$key = $key;
                    $callback ? $callback(['key' => self::$key, 'value' => $value]) : null;
                    return true;
                }
            });
        }

        return $result ?? false;
    }

    /**
     * 口令值单独处理
     *
     * @param $mixed
     */
    public static function passwordValueChange(&$mixed)
    {
        foreach ($mixed as $key => $item) {
            if ($item == 'm') {
                $name = $mixed[$key + 1];
                $mixed[$key + 1] = $item . '.' .$name;
                $mixed = Arr::except($mixed, [$key]);
                break;
            }
        }

        $mixed = array_values($mixed);
    }

    /**
     * http请求方式
     * (兼容京东)
     *
     * @param string $address
     * @return string
     */
    public static function setBySending(&$address = '')
    {
        if (self::$key == 2) {
            $domain = static::$domainName;
            $address = str_replace($domain[2] . '.com/', $domain[6] . '.com/product/', $address);
            $mode = 'File';
        } elseif (self::$key == 6) {
            $mode = 'File';
        } elseif (self::$key == 3) {
            $mode = 'DoNotSend';
        }

        return $mode ?? 'Guzzle';
    }

    /**
     * Define the relationship with the report's author.
     *
     * @return BelongsTo
     */
    public function post()
    {
        return $this->belongsTo(Post::class);
    }
}
