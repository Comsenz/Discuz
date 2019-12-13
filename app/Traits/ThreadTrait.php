<?php

namespace App\Traits;

use App\Exceptions\ThreadException;
use App\Models\OperationLog;
use Exception;
use Illuminate\Database\Eloquent\Model;

trait ThreadTrait
{
    /**
     * @param $message
     * @throws ThreadException
     */
    public function ThreadException($message = 'fail')
    {
        throw new ThreadException($message);
    }

    /**
     * 判断是否可以操作
     *
     * @param Model $thread
     * @param string $action
     * @param mixed $behavior
     * @throws ThreadException
     */
    public function action(Model $thread, $behavior, &$action = '')
    {
        if (!$thread->offsetExists('user')) {
            $this->ThreadException('count_fail');
        }

        if (is_numeric($behavior)) {
            $action = $this->behavior($thread);
        } else {
            // 操作行为
            $this->actionThread($thread, $behavior);
            $action = $behavior;
        }
    }

    /**
     * 帖子状态的改变
     *
     * @param $thread
     * @return mixed
     * @throws ThreadException
     */
    public function behavior($thread)
    {
        if (!array_key_exists($thread->is_approved, OperationLog::behavior())) {
            $this->ThreadException('behavior_fail');
        }

        if (is_null($thread->deleted_at)) {
            if ($thread->is_approved == 1) {
                $this->increments($thread);
            }

            if ($thread->is_approved != 2) {
                $this->decrement($thread);
            }
        }

        return OperationLog::behavior()[$thread->is_approved];
    }

    /**
     * 操作帖子的行为
     *
     * @param $thread
     * @param string $behavior
     * @throws ThreadException
     */
    public function actionThread($thread, $behavior)
    {
        if (!in_array($behavior, OperationLog::actionType())) {
            $this->ThreadException('action_fail');
        }

        switch ($behavior) {
            case 'create':
                $this->increments($thread);
                break;
            case 'hide':
                if ($thread->is_approved == 1) {
                    $this->decrement($thread);
                }
                break;
            case 'restore':
                if ($thread->is_approved == 1) {
                    $this->increments($thread);
                }
                break;
        }
    }

    public function increments(Model $thread)
    {
//        $thread->category->increment('thread_count');
//        $thread->user->increment('thread_count');
    }

    public function decrement(Model $thread)
    {
//        $thread->category->decrement('thread_count');
//        $thread->user->decrement('thread_count');
    }
}
