<?php

/**
 *      Discuz & Tencent Cloud
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: Created.php xxx 2019-10-10 13:09:00 LiuDongdong $
 */

namespace App\Events\StopWord;

use App\Models\StopWord;
use App\Models\User;

class Created
{
    /**
     * @var StopWord
     */
    public $stopWord;

    /**
     * @var User
     */
    public $actor;

    /**
     * Created constructor.
     *
     * @param StopWord $stopWord
     * @param User|null $actor
     */
    public function __construct(StopWord $stopWord, $actor = null)
    {
        // TODO: User $actor
        $this->stopWord = $stopWord;
        $this->actor = $actor;
    }
}
