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
use Discuz\Qcloud\QcloudTrait;
use Illuminate\Contracts\Events\Dispatcher;
use Discuz\Foundation\EventsDispatchTrait;
use Illuminate\Support\Arr;

class ThreadVideoNotify
{
    use EventsDispatchTrait;
    use QcloudTrait;

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
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function handle(Dispatcher $events, ThreadVideoRepository $threadVideo, Censor $censor, PostRepository $posts, ThreadRepository $threads)
    {
        $this->events = $events;
        $log = app('log');
        $log->info('vod_notify', $this->data);

        //只处理视频处理类型的通知
        if (Arr::get($this->data, 'EventType') != 'ProcedureStateChanged') {
            return 'pass';
        }
        $taskDetail  = $this->describeTaskDetail(Arr::get($this->data, 'ProcedureStateChangeEvent.TaskId'));
        if ($taskDetail && $taskDetail->TaskType == 'Procedure' && $taskDetail->Status == 'FINISH') {
            if ($taskDetail->ProcedureTask->Status == 'FINISH') {
                $threadVideo = $threadVideo->findOrFailByFileId($taskDetail->ProcedureTask->FileId);

                foreach ($taskDetail->ProcedureTask->MediaProcessResultSet as $key => $value) {
                    if ($value->Type == 'Transcode') {
                        if ($value->TranscodeTask->ErrCode == 0) {
                            //转码成功
                            $threadVideo->status = ThreadVideo::VIDEO_STATUS_SUCCESS;
                            $threadVideo->media_url = $value->TranscodeTask->Output->Url;
                        } else {
                            //转码失败
                            $threadVideo->status = ThreadVideo::VIDEO_STATUS_FAIL;
                            $threadVideo->reason = $value->TranscodeTask->Message;
                        }
                    }


                    if ($value->Type == 'CoverBySnapshot') {
                        if ($value->CoverBySnapshotTask->ErrCode == 0) {
                            //截取封面图成功
                            $threadVideo->cover_url = $value->CoverBySnapshotTask->Output->CoverUrl;
                        }
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
