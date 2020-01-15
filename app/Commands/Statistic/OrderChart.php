<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Commands\Statistic;

use App\Models\Order;
use App\Models\User;
use App\Models\UserWallet;
use App\Models\UserWalletCash;
use Discuz\Auth\AssertPermissionTrait;
use Discuz\Auth\Exception\PermissionDeniedException;
use Discuz\Contracts\Setting\SettingsRepository;

class OrderChart
{
    use AssertPermissionTrait;

    protected $actor;

    protected $settings;

    protected $order;

    protected $userWallet;

    protected $userWalletCash;

    protected $filter;

    public function __construct(User $actor, $filter)
    {
        $this->actor    = $actor;
        $this->filter   = $filter;
    }

    public function handle(Order $order, UserWallet $userWallet, UserWalletCash $userWalletCash, SettingsRepository $setting)
    {
        $this->order = $order;
        $this->userWallet = $userWallet;
        $this->userWalletCash = $userWalletCash;
        $this->settings = $setting;

        return call_user_func([$this, '__invoke']);
    }

    /**
     * @return mixed
     * @throws PermissionDeniedException
     */
    public function __invoke()
    {
        $this->assertAdmin($this->actor);

        return ;
    }
}
