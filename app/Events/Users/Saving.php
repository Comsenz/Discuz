<?php
declare(strict_types=1);

/**
 *      Discuz & Tencent Cloud
 *      This is NOT a freeware, use is subject to license terms
 *
 *      Id: Saving.php 28830 2019-09-26 17:51 chenkeke $
 */

namespace App\Events\Users;

use App\Models\User;

class Saving
{
    /**
     * @var Users
     */
    public $user;
    
    /**
     * @param Circle $circle
     * @param User   $actor
     * @param array  $data
     */
    public function __construct(User $user)
    {
        $this->user = $user;

    }
}