<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\SmsMessages;

use Discuz\Contracts\Setting\SettingsRepository;
use Overtrue\EasySms\Contracts\GatewayInterface;
use Overtrue\EasySms\Message;
use Overtrue\EasySms\Strategies\OrderStrategy;

class SendCodeMessage extends Message
{
    protected $data;                           //传进来的数据

    protected $strategy = OrderStrategy::class;           // 定义本短信的网关使用策略，覆盖全局配置中的 `default.strategy`

    protected $gateways = ['qcloud']; // 定义本短信的适用平台，覆盖全局配置中的 `default.gateways`

    public function __construct($data)
    {
        $this->data = $data;
    }

    // 定义直接使用内容发送平台的内容
    public function getContent(GatewayInterface $gateway = null)
    {
        return '';
    }

    // 定义使用模板发送方式平台所需要的模板 ID
    public function getTemplate(GatewayInterface $gateway = null)
    {
        return app()->make(SettingsRepository::class)->get('qcloud_sms_template_id', 'qcloud');
    }

    // 模板参数
    public function getData(GatewayInterface $gateway = null)
    {
        return collect($this->data)->sortKeys()->values()->toArray();
    }
}
