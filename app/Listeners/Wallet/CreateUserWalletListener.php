<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Listeners\Wallet;

use App\Events\Users\Registered;
use App\Models\UserWallet;

class CreateUserWalletListener
{
    public function handle(Registered $event)
    {
        $user_wallet = UserWallet::where('user_id', $event->user->id)->first();

        if (empty($user_wallet)) {
            UserWallet::createUserWallet($event->user->id);
        }
    }
}
