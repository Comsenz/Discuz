<?php

/**
 *      Discuz & Tencent Cloud
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: CreateUserWalletListner.php xxx 2019-10-24 17:14:00 zhouzhou $
 */

namespace App\Listeners;

use App\Events\Users\Saving;
use App\Models\UserWallet;

class CreateUserWalletListner
{
    public function handle(Saving $event)
    {
        $user_wallet = UserWallet::where('user_id', $event->user->id)->first();
        if (empty($user_wallet)) {
            return UserWallet::createUserWallet($event->user->id);
        } else {
            return $user_wallet;
        }
    }
}
