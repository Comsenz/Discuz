<?php

/**
 *      Discuz & Tencent Cloud
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: Deleting.php xxx 2019-11-06 17:06:00 LiuDongdong $
 */

namespace App\Events\StopWord;

use App\Models\StopWord;
use App\Models\User;

class Deleting
{
    /**
     * The stop word that will be deleted.
     *
     * @var StopWord
     */
    public $stopWord;

    /**
     * The user who is performing the action.
     *
     * @var User
     */
    public $actor;

    /**
     * Any user input associated with the command.
     *
     * @var array
     */
    public $data;

    /**
     * @param StopWord $stopWord
     * @param User $actor
     * @param array $data
     */
    public function __construct(StopWord $stopWord, User $actor, array $data = [])
    {
        $this->stopWord = $stopWord;
        $this->actor = $actor;
        $this->data = $data;
    }
}
