<?php

/**
 *      Discuz & Tencent Cloud
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: Saving.php xxx 2019-10-10 13:21:00 LiuDongdong $
 */

namespace App\Events\StopWord;

use App\Models\StopWord;
use App\Models\User;

class Saving
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
     * @var array
     */
    public $data;

    /**
     * Saving constructor.
     *
     * @param StopWord $stopWord
     * @param User $actor
     * @param array $data
     */
    public function __construct(StopWord $stopWord, User $actor, array $data)
    {
        $this->stopWord = $stopWord;
        $this->actor = $actor;
        $this->data = $data;
    }
}
