<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Listeners\User;

use App\Events\Users\Logind;
use Discuz\Auth\AssertPermissionTrait;
use Discuz\Contracts\Setting\SettingsRepository;

class ChckoutSite
{
    use AssertPermissionTrait;

    protected $settings;

    public function __construct(SettingsRepository $settings)
    {
        $this->settings = $settings;
    }

    public function handle(Logind $event)
    {
        $str = $this->settings->get('site_close');

        if ($str) {
            $this->assertAdmin($event->user);
        }
    }
}
