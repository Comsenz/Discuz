<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Commands\Post;

use App\Censor\Censor;
use App\Events\Post\PostWasApproved;
use App\Events\Post\Saved;
use App\Events\Post\Saving;
use App\Models\Post;
use App\Models\PostMod;
use App\Models\User;
use App\Repositories\PostRepository;
use App\Validators\PostValidator;
use Discuz\Auth\AssertPermissionTrait;
use Discuz\Auth\Exception\PermissionDeniedException;
use Discuz\Foundation\EventsDispatchTrait;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Support\Arr;
use Illuminate\Validation\ValidationException;

class EditPost
{
    use AssertPermissionTrait;
    use EventsDispatchTrait;

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
    public function __construct($postId, User $actor, array $data)
    {
        $this->postId = $postId;
        $this->actor = $actor;
        $this->data = $data;
    }

    /**
     * @param Dispatcher $events
     * @param PostRepository $posts
     * @param Censor $censor
     * @param PostValidator $validator
     * @return Post
     * @throws PermissionDeniedException
     * @throws ValidationException
     */
    public function handle(Dispatcher $events, PostRepository $posts, Censor $censor, PostValidator $validator)
    {
        $this->events = $events;

        $post = $posts->findOrFail($this->postId, $this->actor);

        $attributes = Arr::get($this->data, 'attributes', []);

        if (isset($attributes['content'])) {
            $this->assertCan($this->actor, 'edit', $post);

            // 敏感词校验
            $content = $censor->checkText($attributes['content']);

            // 存在审核敏感词时，将主题放入待审核
            if ($censor->isMod && $post->is_first != 1) {
                $post->is_approved = 0;
            }

            $post->revise($content, $this->actor);
        } else {
            // 不修改内容时，不更新修改时间
            $post->timestamps = false;
        }

        if (isset($attributes['isApproved'])) {
            $this->assertCan($this->actor, 'approve', $post);
            $message = isset($attributes['message']) ? $attributes['message'] : '';
            $post->is_approved = $attributes['isApproved'];

            // 操作审核时触发 回复内容通知和记录日志
            $post->raise(new PostWasApproved(
                $post,
                $this->actor,
                ['message' => $message]
            ));
        }

        if (isset($attributes['isDeleted'])) {
            $this->assertCan($this->actor, 'hide', $post);

            $message = isset($attributes['message']) ? $attributes['message'] : '';

            if ($attributes['isDeleted']) {
                $post->hide($this->actor, ['message' => $message]);
            } else {
                $post->restore($this->actor, ['message' => $message]);
            }
        }

        $this->events->dispatch(
            new Saving($post, $this->actor, $this->data)
        );

        $validator->valid($post->getDirty());

        // 记录触发的审核词
        if ($post->is_approved == 0 && $censor->wordMod) {
            $stopWords = PostMod::firstOrNew(['post_id' => $post->id]);
            $stopWords->stop_word = implode(',', array_unique($censor->wordMod));

            $post->stopWords()->save($stopWords);

            // 如果是首贴，将主题放入待审核
            if ($post->is_first) {
                $post->thread->is_approved = 0;
                $post->thread->save();
            }
        }

        $post->save();

        $post->raise(new Saved($post, $this->actor, $this->data));

        $this->dispatchEventsFor($post, $this->actor);

        return $post;
    }
}
