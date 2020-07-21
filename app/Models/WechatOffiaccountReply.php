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

use Carbon\Carbon;
use Closure;
use Discuz\Database\ScopeVisibilityTrait;
use Discuz\Foundation\EventGeneratorTrait;
use Illuminate\Database\Eloquent\Model;

/**
 * @package App\Models
 *
 * @property string $name
 * @property string $keyword
 * @property string $media_id
 * @property int $match_type
 * @property int $reply_type
 * @property int $content
 * @property int $media_type
 * @property int $type
 * @property int $status
 * @property Carbon created_at
 * @property Carbon updated_at
 * @property Carbon deleted_at
 * @method static create(array $array)
 * @method static where(...$params)
 */
class WechatOffiaccountReply extends Model
{
    use EventGeneratorTrait;
    use ScopeVisibilityTrait;

    protected $fillable = [
        'name',
        'keyword',
        'match_type',
        'reply_type',
        'content',
        'media_id',
        'media_type',
        'type',
    ];

    /**
     * 消息回复类型:
     * 1文本 2图片 3语音 4视频 5图文
     *
     * @var array
     */
    public static $enumReplyType = [
        1 => 'Text',
        2 => 'Image',
        3 => 'Voice',
        4 => 'Video',
        5 => 'News',
    ];

    /**
     * Create a new OffiaccountReply
     *
     * @param array $attributes
     * @return static
     */
    public static function build(array $attributes)
    {
        $offiaccountReply = new static;

        $offiaccountReply->fill($attributes);

        return $offiaccountReply;
    }

    /**
     * 消息回复类型 - 枚举
     *
     * @param $mixed
     * @param mixed $default 枚举值/闭包
     * @return bool|false|int|mixed|string|callback
     */
    public static function enumReplyType($mixed, $default = null)
    {
        $enum = static::$enumReplyType;

        if (is_numeric($mixed)) {
            if ($bool = array_key_exists($mixed, $enum)) {
                // 获取对应value值
                $trans = $enum[$mixed];
            }
        } elseif (is_string($mixed)) {
            if ($bool = in_array($mixed, $enum)) {
                // 获取对应key值
                $trans = array_search($mixed, $enum);
            }
        } else {
            return false;
        }

        if (!isset($trans)) {
            return false;
        }

        if (empty($default)) {
            $result = $trans;
        } elseif ($default instanceof Closure) {
            $result = $default(['key' => $mixed, 'value' => $trans, 'bool' => $bool]);
        } else {
            $result = $bool;
        }

        return $result;
    }

    /**
     * 判断是否匹配到文字
     * (可以传第二个参数，拿到数据)
     *
     * @param $keyword
     * @param false $replyData
     * @return bool
     */
    public static function match($keyword, &$replyData = false)
    {
        $reply = self::query();
        $replyMatch = clone $reply;

        // 优先 全匹配查询
        $match = $reply->where('match_type', 0)->where('keyword', 'like', $keyword)->first();

        if (is_null($match)) {
            // 半匹配模糊查询
            $match = $replyMatch->where('match_type', 1)->where('keyword', 'like', '%' . $keyword . '%')->first();

            if (is_null($match)) {
                return false;  // 匹配不到不回复
            }

            if ($replyData !== false) {
                $replyData = $match;
            }
        }

        return true;
    }
}
