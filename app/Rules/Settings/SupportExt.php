<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Rules\Settings;

use Discuz\Validation\AbstractRule;

/**
 * 附件公共 - 验证
 *
 * Class SupportExt
 * @package App\Rules\Settings
 */
class SupportExt extends AbstractRule
{
    /**
     * 默认错误提示
     * @var string
     */
    public $message = 'set_error';

    /**
     * 判断不允许存在的扩展名
     *
     * @param string $attribute
     * @param mixed $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $ext = explode(',', $value);

        $suffix = 'php';
        $bool = !in_array($suffix, $ext);

        if (!$bool) {
            $this->message = $attribute . '_' . $suffix . '_format_error';
        }

        return $bool;
    }

    /**
     * Get the validation error message.
     *
     * @return string|array
     */
    public function message()
    {
        return trans('setting.' . $this->message);
    }
}
