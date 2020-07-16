<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Rules\Posts;

use App\Censor\Censor;
use App\Censor\CensorNotPassedException;
use App\Exceptions\TranslatorException;
use Discuz\Validation\AbstractRule;
use TencentCloud\Common\Exception\TencentCloudSDKException;

/**
 * Class PostCensorVerify
 * @package App\Rules\Posts
 */
class PostCensorVerify extends AbstractRule
{

    public $message = 'censor_error';

    protected $censor;

    public function __construct()
    {
        $this->censor = app()->make(Censor::class);
    }

    /**
     * @param string $attribute
     * @param $value
     * @return bool
     * @throws TencentCloudSDKException
     */
    public function passes($attribute, $value)
    {
        $this->censor->checkText($value);
        if ($this->censor->isMod) {
            throw new CensorNotPassedException('content_banned');
        }
    }

    /**
     * Get the validation error message.
     *
     * @return string|array
     */
    public function message()
    {
    }

    /**
     * @param $array
     */
    public function errorMessage($array)
    {
    }
}
