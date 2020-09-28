<?php

/**
 * Copyright (C) 2020 Tencent Cloud.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

namespace App\Commands\Wallet;

use App\Events\Wallet\Saved;
use App\Exceptions\WalletException;
use App\Models\User;
use App\Models\UserWallet;
use Discuz\Auth\AssertPermissionTrait;
use Discuz\Foundation\EventsDispatchTrait;
use Illuminate\Contracts\Events\Dispatcher as EventDispatcher;

class ChangeUserWallet
{
    use AssertPermissionTrait;
    use EventsDispatchTrait;

    /**
     * @var User
     */
    protected $user;

    /**
     * @var string
     */
    protected $action;

    /**
     * @var float
     */
    protected $amount;

    /**
     * @var mixed|null
     */
    protected $data;

    /**
     * @param User $user
     * @param string $action
     * @param float $amount
     * @param array $data
     */
    public function __construct(User $user, $action, $amount, $data = [])
    {
        $this->user = $user;
        $this->action = $action;
        $this->amount = $amount;
        $this->data = $data;

        // build
        $this->data += [
            'action' => $this->action,
        ];
    }

    /**
     * @param EventDispatcher $events
     * @return UserWallet
     * @throws WalletException
     */
    public function handle(EventDispatcher $events)
    {
        $this->events = $events;

        $wallet = $this->user->userWallet;

        // TODO Wallet 支付时判断钱包金额是否充足

        // TODO 未参与金额分成运算，需要在外部进行分成运算

        switch ($this->action) {
            case UserWallet::OPERATE_INCREASE:
                $wallet->increment('available_amount', $this->amount);
                break;
            case UserWallet::OPERATE_DECREASE:
                $wallet->decrement('available_amount', $this->amount);
                break;
            case UserWallet::OPERATE_INCREASE_FREEZE:
                $wallet->increment('freeze_amount', $this->amount);
                break;
            case UserWallet::OPERATE_DECREASE_FREEZE:
                $wallet->decrement('freeze_amount', $this->amount);
                break;
            case UserWallet::OPERATE_FREEZE:
                $wallet->decrement('available_amount', $this->amount);
                $wallet->increment('freeze_amount', $this->amount);
                break;
            case UserWallet::OPERATE_UNFREEZE:
                $wallet->increment('available_amount', $this->amount);
                $wallet->decrement('freeze_amount', $this->amount);
                break;
            default:
                throw new WalletException('operate_type_error');
        }

        $wallet->save();

        $wallet->raise(new Saved($wallet, $this->user, $this->amount, $this->data));

        $this->dispatchEventsFor($wallet);

        return $wallet;
    }
}
