<?php

namespace App\Rules\Settings;

use Discuz\Qcloud\Services\BillingService;
use Discuz\Validation\AbstractRule;

/**
 * 腾讯云设置 - 验证
 *
 * Class QcloudSecretVerify
 * @package App\Rules\Settings
 */
class QcloudSecretVerify extends AbstractRule
{
    private $qcloudSecretKey;

    public function __construct($qcloudSecretKey)
    {
        $this->qcloudSecretKey = $qcloudSecretKey;
    }

    /**
     * 腾讯云api设置 - 验证
     *
     * @param string $attribute
     * @param mixed $qcloudSecretId
     * @return bool|void
     */
    public function passes($attribute, $qcloudSecretId)
    {
        $QCloud = [
            'qcloud_secret_id' => $qcloudSecretId,
            'qcloud_secret_key' => $this->qcloudSecretKey,
        ];

        /**
         * 调用腾讯 验证账户是否存在
         */
        $billing = new BillingService($QCloud);
        $billing->DescribeAccountBalance();

        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string|array
     */
    public function message()
    {
        // TODO: Implement message() method.
    }
}
