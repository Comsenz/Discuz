<?php
declare(strict_types=1);

/**
 *      Discuz & Tencent Cloud
 *      This is NOT a freeware, use is subject to license terms
 *
 *      1: UpdateCircle.php 28830 2019-09-26 10:09 chenkeke $
 */

namespace App\Commands\CircleExtend;


use App\Repositories\CircleExtendRepository;
use Illuminate\Contracts\Events\Dispatcher;

class UpdateCircleExtend
{
    /**
     * The ID of the post to edit.
     *
     * @var int
     */
    public $postId;

    /**
     * The user performing the action.
     *
     * @var User
     */
    public $actor;

    /**
     * The attributes to update on the post.
     *
     * @var array
     */
    public $data;

    /**
     * @param int $postId The ID of the post to edit.
     * @param User $actor The user performing the action.
     * @param array $data The attributes to update on the post.
     */
    public function __construct($postId, $actor, array $data)
    {
        $this->postId = $postId;
        $this->actor = $actor;
        $this->data = $data;
    }

    /**
     * @param Dispatcher $events
     * @param CircleRepository $repository
     * @return Circle
     */
    public function handle(Dispatcher $events, CircleRepository $repository)
    {
        var_dump($this->postId);
        var_dump($this->actor);
        var_dump($this->data);
        $circle = $repository->getdata($this->data);

        return $circle;
    }
}