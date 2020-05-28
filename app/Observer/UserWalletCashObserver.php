<?php


namespace App\Observer;


use App\MessageTemplate\WithdrawalMessage;
use App\Models\UserWalletCash;
use App\Notifications\Rewarded;
use App\Notifications\Withdrawal;

class UserWalletCashObserver
{
    protected $cash;

    /**
     * @param UserWalletCash $cash
     */
    public function created(UserWalletCash $cash)
    {
        // $this->cash = $cash;
        //
        // $this->sendNotification();
        // dump('created');
    }

    public function updated(UserWalletCash $cash)
    {
        // TODO 审核通过时,通知里取得金额数是真实到账金额吗?
        // dump('updated');
    }

    public function sendNotification()
    {
        // // 数据库通知
        // $this->cash->user->notify(new Withdrawal($this->cash, WithdrawalMessage::class, [
        //     'refuse' => $this->cash->remark,
        //     'cash_status' => $this->cash->cash_status,
        // ]));

        // $order->payee->notify(new Rewarded($order, $order->user, WechatRewardedMessage::class, [
        //     'message' => $order->thread->getContentByType(Thread::CONTENT_LENGTH),
        //     'raw' => array_merge(Arr::only($order->toArray(), ['id', 'thread_id', 'amount', 'type']), [
        //         'actor_username' => $order->user->username    // 发送人姓名
        //     ]),
        // ]));
    }

}
