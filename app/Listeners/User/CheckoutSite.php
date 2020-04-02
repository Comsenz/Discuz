<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Listeners\User;

use App\Events\Users\Logind;
use Discuz\Auth\AssertPermissionTrait;
use Discuz\Contracts\Setting\SettingsRepository;

class CheckoutSite
{
    use AssertPermissionTrait;

    protected $settings;

    public function __construct(SettingsRepository $settings)
    {
        $this->settings = $settings;
    }

    /**
     * @param Logind $event
     * @throws \Discuz\Auth\Exception\PermissionDeniedException
     */
    public function handle(Logind $event)
    {
        (bool)$this->settings->get('site_close') && $this->assertAdmin($event->user);
    }
}
