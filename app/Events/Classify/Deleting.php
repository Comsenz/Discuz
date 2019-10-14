<?php
declare(strict_types=1);

/**
 *      Discuz & Tencent Cloud
 *      This is NOT a freeware, use is subject to license terms
 *
 *      Id: Deleting.php 28830 2019-10-14 13:54 chenkeke $
 */

namespace App\Events\Classify;


use App\Models\Classify;

class Deleting
{
    /**
     * @var Classify
     */
    public $classify;

    /**
     * @var User
     */
    public $actor;

    /**
     * 用户输入的参数.
     *
     * @var array
     */
    public $data;

    /**
     * @param Classify $classify
     * @param User   $actor
     * @param array  $data
     */
    public function __construct(Classify $classify, $actor = null, array $data = [])
    {
        $this->classify = $classify;
        $this->actor = $actor;
        $this->data = $data;
    }
}