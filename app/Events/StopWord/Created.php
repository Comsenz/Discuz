<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
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
    public function __construct(StopWord $stopWord, User $actor = null)
    {
        $this->stopWord = $stopWord;
        $this->actor = $actor;
    }
}
