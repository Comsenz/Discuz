<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Commands\Post;

use App\BlockEditor\BlocksParser;
use App\Censor\Censor;
use App\Events\Post\Created;
use App\Events\Post\Saved;
use App\Events\Post\Saving;
use App\Models\Post;
use App\Models\PostMod;
use App\Models\Thread;
use App\Models\User;
use App\Repositories\ThreadRepository;
use App\Validators\PostValidator;
use Discuz\Auth\AssertPermissionTrait;
use Discuz\Auth\Exception\PermissionDeniedException;
use Discuz\Foundation\EventsDispatchTrait;
use Exception;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Arr;
use Illuminate\Validation\ValidationException;

class CreatePost
{
    use AssertPermissionTrait;
    use EventsDispatchTrait;

    /**
     * The id of the thread.
     *
     * @var int
     */
    public $threadId;

    /**
     * The id of the post waiting to be replied.
     *
     * @var int
     */
    public $replyPostId;

    /**
     * The id of the post waiting to be replied.
     *
     * @var int
     */
    public $replyUserId;

    /**
     * The user performing the action.
     *
     * @var User
     */
    public $actor;

    /**
     * The attributes of the new thread.
     *
     * @var array
     */
    public $data;

    /**
     * The current ip address of the actor.
     *
     * @var string
     */
    public $ip;

    /**
     * The current port of the actor.
     *
     * @var int
     */
    public $port;

    /**
     * @param $threadId
     * @param User $actor
     * @param array $data
     * @param null $ip
     */
    public function __construct($threadId, User $actor, array $data, $ip, $port)
    {
        $this->threadId = $threadId;
        $this->replyPostId = Arr::get($data, 'attributes.replyId', null);
        $this->actor = $actor;
        $this->data = $data;
        $this->ip = $ip;
        $this->port = $port;
    }

    /**
     * @param Dispatcher $events
     * @param ThreadRepository $threads
     * @param PostValidator $validator
     * @param Censor $censor
     * @param Post $post
     * @return Post
     * @throws PermissionDeniedException
     * @throws Exception
     * @throws ValidationException
     */
    public function handle(Dispatcher $events, ThreadRepository $threads, PostValidator $validator, Censor $censor, Post $post)
    {
        $this->events = $events;

        $thread = $threads->findOrFail($this->threadId);

        $isFirst = empty($thread->last_posted_user_id);

        $isComment = (bool) Arr::get($this->data, 'attributes.isComment');

        $BlocksParser = new BlocksParser(collect(Arr::get($this->data, 'attributes.content')), $post);
        $content = $BlocksParser->parse();

        $isMod = false;
        foreach ($content->get('blocks') as $block) {
            if ($block['type'] == 'text' && isset($block['data']['isMod']) && $block['data']['isMod']) {
                $isMod = $block['data']['isMod'];
                break;
            }
        }

        if (! $isFirst) {
            // 非首帖，检查是否有权回复
            $this->assertCan($this->actor, 'reply', $thread);

            // 是否有权发布音频
            if (Arr::get($this->data, 'attributes.file_id', '')) {
                $this->assertCan($this->actor, 'createAudio');
            }

            // 引用回复
            if (! empty($this->replyPostId)) {
                // 不能只回复引用部分
                $pattern = '/<blockquote class="quoteCon">.*<\/blockquote>/';
                $replyContent = '';
                foreach ($content->get('blocks') as $block) {
                    if ($block['type'] == 'text' && $replyContent = preg_replace($pattern, '', $block['data']['value'])) {
                        break;
                    }
                }

                if (! $replyContent) {
                    throw new Exception('reply_content_cannot_null');
                }

                // 检查是否在同一主题下的
                $this->replyUserId = $post->where('id', $this->replyPostId)
                    ->where('thread_id', $thread->id)
                    ->value('user_id');

                if (! $this->replyUserId) {
                    throw new ModelNotFoundException;
                }
            }

        } else {
            //发布的是首帖时修改主题审核属性
            if ($isMod) {
                $thread->is_approved = Thread::UNAPPROVED;
                $thread->save();
            }

            //TODO 内容没有text块时的处理（没文字可能会影响分享）
//            if (! $content) {
//                switch ($thread->type) {
//                    case Thread::TYPE_OF_VIDEO:
//                        $content = '分享视频';
//                        break;
//                    case Thread::TYPE_OF_IMAGE:
//                        $content = '分享图片';
//                        break;
//                }
//            }

        }


        $post = $post->reply(
            $thread->id,
            $content,
            $this->actor->id,
            $this->ip,
            $this->port,
            $this->replyPostId,
            $this->replyUserId,
            $isFirst,
            $isComment,
            Arr::get($this->data, 'attributes.latitude', 0),
            Arr::get($this->data, 'attributes.longitude', 0)
        );

        // 存在审核敏感词时，将回复内容放入待审核
        if ($isMod) {
            $post->is_approved = 0;
        } else {
            $post->is_approved = 1;
        }

        $post->raise(new Created($post, $this->actor, $this->data));

        $this->events->dispatch(
            new Saving($post, $this->actor, $this->data)
        );

        $validator->valid($post->getAttributes());

        $post->save();

        // 记录触发的审核词
        if ($post->is_approved == 0 && $censor->wordMod) {
            $stopWords = new PostMod;
            $stopWords->stop_word = implode(',', array_unique($censor->wordMod));

            $post->stopWords()->save($stopWords);
        }

        $post->raise(new Saved($post, $this->actor, $this->data));

        // TODO: 通知相关用户，在给定的整个持续时间内，每位用户只能收到一个通知
        // $this->notifications->onePerUser(function () use ($post, $actor) {
        $this->dispatchEventsFor($post, $this->actor);
        // });

        return $post;
    }
}
