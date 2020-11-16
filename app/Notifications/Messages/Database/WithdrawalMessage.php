<?php

namespace App\Notifications\Messages\Database;

use Discuz\Notifications\Messages\SimpleMessage;

class WithdrawalMessage extends SimpleMessage
{
    protected $cash;

    protected $actor;

    public function __construct()
    {
        //
    }

    public function setData(...$parameters)
    {
        // 解构赋值
        [$firstData, $actor, $cash] = $parameters;
        // set parent tpl data
        $this->firstData = $firstData;

        $this->actor = $actor;
        $this->cash = $cash;
    }

    protected function titleReplaceVars()
    {
        return [];
    }

    public function contentReplaceVars($data)
    {
        return $data;
    }

    public function render()
    {
        return [
            'user_id' => $this->cash->user->id,  // 提现人ID
            'wallet_cash_id' => $this->cash->id, // 提现记录ID
            'cash_actual_amount' => $this->cash->cash_actual_amount, // 实际提现金额
            'cash_apply_amount' => $this->cash->cash_apply_amount,   // 提现申请金额
            'cash_status' => $this->cash->cash_status,
            'remark' => $this->cash->remark,
            'created_at' => $this->cash->formatDate('created_at'),
        ];
    }
}
