<?php

/**
 *      Discuz & Tencent Cloud
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: CreateUserWalletListener.php xxx 2019-10-24 17:14:00 zhouzhou $
 */

namespace App\Listeners\Wallet;

use App\Events\Users\Saving;
use App\Models\UserWallet;

class CreateUserWalletListener
{
    public function handle(Saving $event)
    {
        $user_wallet = UserWallet::where('user_id', $event->user->id)->first();
        if (empty($user_wallet)) {
            UserWallet::createUserWallet($event->user->id);
        }
    }
}
