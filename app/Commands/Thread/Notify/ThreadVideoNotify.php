<?php


/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Commands\Thread\Notify;

use App\Censor\Censor;
use App\Models\ThreadVideo;
use App\Repositories\PostRepository;
use App\Repositories\ThreadRepository;
use App\Repositories\ThreadVideoRepository;
use Illuminate\Contracts\Events\Dispatcher;
use Discuz\Foundation\EventsDispatchTrait;
use Illuminate\Support\Arr;

class ThreadVideoNotify
{
    use EventsDispatchTrait;

    protected $data;

    /**
     *
     * @param $data
     */
    public function __construct(array $data)
    {
        $this->data = $data;
    }

    /**
     *
     * @param Dispatcher $events
     * @param ThreadVideoRepository $threadVideo
     * @param Censor $censor
     * @param PostRepository $posts
     * @param ThreadRepository $threads
     * @return string
     */
    public function handle(Dispatcher $events, ThreadVideoRepository $threadVideo, Censor $censor, PostRepository $posts, ThreadRepository $threads)
    {
        $this->events = $events;
        $log = app('log');
        $log->info('vod_notify', $this->data);

        $EventType = 'ProcedureStateChanged';
        $EventName = 'ProcedureStateChangeEvent';
        $EventRes  = 'MediaProcessResultSet';

        if (Arr::get($this->data, 'EventType') == $EventType &&
            Arr::get($this->data, $EventName.'.ErrCode') == '0') {
            $threadVideo = $threadVideo->findOrFailByFileId(Arr::get($this->data, $EventName.'.FileId'));

            foreach (Arr::get($this->data, $EventName.'.'.$EventRes) as $key => $value) {
                if (Arr::get($value, 'Type') == 'Transcode') {
                    if (Arr::get($value, 'TranscodeTask.ErrCode') == '0') {
                        //转码成功
                        $threadVideo->status = ThreadVideo::VIDEO_STATUS_SUCCESS;
                        $threadVideo->media_url = Arr::get($value, 'TranscodeTask.Output.Url');
                    } else {
                        //转码失败
                        $threadVideo->status = ThreadVideo::VIDEO_STATUS_FAIL;
                        $threadVideo->reason = Arr::get($value, 'TranscodeTask.Message');
                    }
                }


                if (Arr::get($value, 'Type') == 'CoverBySnapshot') {
                    if (Arr::get($value, 'CoverBySnapshotTask.ErrCode') == '0') {
                        //截取封面图成功
                        $threadVideo->cover_url = Arr::get($value, 'CoverBySnapshotTask.Output.CoverUrl');
                    }
                }
            }

            $threadVideo->save();

            //主题审核状态
            $post = $posts->query()->where('thread_id', $threadVideo->thread_id)->firstOrFail();
            $censor->checkText($post->content);
            if (!$censor->isMod && $threadVideo->status == ThreadVideo::VIDEO_STATUS_SUCCESS) {
                $thread = $threads->findOrFail($threadVideo->thread_id);
                $thread->is_approved = 1;
                $thread->save();
            }
        }
        return 'success';
    }
}
