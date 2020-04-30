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

/**
 * @package App\Models
 *
 * @property int $user_id
 * @property string $path
 * @property string $method
 * @property string $ip
 * @property string $input
 * @property int $type
 * @property Carbon updated_at
 * @property Carbon created_at
 * @method static create(array $array)
 */
class OperationLog extends Model
{
    use EventGeneratorTrait;
    use ScopeVisibilityTrait;

    const HEADER_NAME = 'Operation-Log';

    protected static $typeValue = [
        'backstage' => 0,
    ];

    /**
     * Create a new OperationLog
     *
     * @param int $user_id
     * @param string $path
     * @param string $method
     * @param string $ip
     * @param string $input
     * @param int $type
     * @return bool
     */
    public static function store(int $user_id, string $path, string $method, string $ip, string $input, int $type = 0)
    {
        $operationLog = new static;

        $operationLog->user_id = $user_id;
        $operationLog->path = $path;
        $operationLog->method = $method;
        $operationLog->ip = $ip;
        $operationLog->input = $input;
        $operationLog->type = $type;

        return $operationLog->save();
    }

    /**
     * Exists Type
     *
     * @param string $type
     * @param int $int
     * @return bool
     */
    public static function existsToType(string $type, &$int = 0) : bool
    {
        if ($bool = array_key_exists($type, self::$typeValue)) {
            $int = self::$typeValue[$type];
        }

        return $bool;
    }

    /**
     * Define the relationship with the OperationLog's author.
     *
     * @return BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
