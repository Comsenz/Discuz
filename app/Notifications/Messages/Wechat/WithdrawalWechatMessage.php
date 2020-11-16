<?php

namespace App\Notifications\Messages\Wechat;

use App\Models\UserWalletCash;
use Discuz\Notifications\Messages\SimpleMessage;
use Illuminate\Contracts\Routing\UrlGenerator;
use Illuminate\Support\Arr;

class WithdrawalWechatMessage extends SimpleMessage
{
    protected $cash;

    protected $actor;

    protected $data;

    /**
     * @var UrlGenerator
     */
    protected $url;

    public function __construct(UrlGenerator $url)
    {
        $this->url = $url;
    }

    public function setData(...$parameters)
    {
        [$firstData, $actor, $cash, $data] = $parameters;
        // set parent tpl data
        $this->firstData = $firstData;

        $this->actor = $actor;
        $this->cash = $cash;
        $this->data = $data;

        $this->template();
    }

    public function template()
    {
        $build = [
            'title' => $this->getTitle(),
            'content' => $this->getContent($this->data),
            'raw' => Arr::get($this->data, 'raw'),
        ];

        Arr::set($build, 'raw.tpl_id', $this->firstData->id);

        return $build;
    }

    protected function titleReplaceVars()
    {
        return [];
    }

    public function contentReplaceVars($data)
    {
        $cash_actual_amount = Arr::get($data, 'cash_actual_amount');
        $created_at = Arr::get($data, 'created_at', '')->toDateTimeString();
        $cashStatus = Arr::get($data, 'cash_status');
        $refuse = Arr::get($data, 'refuse', '');

        $build = [
            $cash_actual_amount,  // 1. 提现金额
            $created_at,          // 2. 提现时间
            UserWalletCash::enumCashStatus($cashStatus), // 3. 提现结果
        ];

        // 4. 原因
        if (! UserWalletCash::notificationByWhich($cashStatus)) {
            array_push($build, $refuse);
        }

        // 5. 跳转地址
        array_push($build, $this->url->to(''));

        return $build;
    }

}
