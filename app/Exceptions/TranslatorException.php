<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Exceptions;

use Discuz\Locale\AbstractLocaleException;
use Exception;

/**
 * 本地化语言包-捕获异常
 *
 * Class TranslatorException
 * @example throw new TranslatorException('user_error', ['not_match']);
 * @package App\Exceptions
 */
class TranslatorException extends AbstractLocaleException
{
    public function __construct($message = '', array $detail = [], $code = 500, Exception $previous = null)
    {
        $this->message = $message;

        $this->handle(func_get_args());

        parent::__construct($message, $code, $previous);
    }

    public function handle($args)
    {
        if (empty($args)) {
            return;
        }
        $app = app('translator');

        $this->detail = collect($args)->filter(function ($value) {
            if (is_array($value)) {
                return true;
            }
        })->flatten()->map(function ($item) use ($app) {
            return $app->get($this->getLocaleName() . '.' . $item);
        })->toArray();
    }

    /**
     * 错误数组
     *
     * @return array
     */
    public function getDetail() : array
    {
        return $this->detail;
    }
}
