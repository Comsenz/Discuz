<?php


/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Commands\Thread\Notify;

use App\Models\ThreadVideo;
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
     * @return string
     */
    public function handle(Dispatcher $events, ThreadVideoRepository $threadVideo)
    {
        $this->events = $events;
        $log = app('log');
        $log->info('vod_notify', $this->data);

        $EventType = 'ProcedureStateChanged';
        $EventName = 'ProcedureStateChangeEvent';
        $EventRes  = 'MediaProcessResultSet';

        if (Arr::get($this->data, 'EventType') == $EventType &&
            Arr::get($this->data, $EventName.'.ErrCode') == '0') {
            $threadVideo = $threadVideo->findOrFail(Arr::get($this->data, $EventName.'.FileId'));

            if (Arr::get($this->data, $EventName.'.'.$EventRes.'Type') == 'Transcode') {
                if (Arr::get($this->data, $EventName.'.'.$EventRes.'TranscodeTask.ErrCode') == '0') {
                    //转码成功
                    $threadVideo->status = ThreadVideo::VIDEO_STATUS_SUCCESS;
                    $threadVideo->media_url = Arr::get($this->data, $EventName.'.'.$EventRes.'TranscodeTask.Output.Url');
                } else {
                    //转码失败
                    $threadVideo->status = ThreadVideo::VIDEO_STATUS_FAIL;
                    $threadVideo->reason = Arr::get($this->data, $EventName.'.'.$EventRes.'TranscodeTask.Message');
                }
            }

            if (Arr::get($this->data, $EventName.'.'.$EventRes.'Type') == 'CoverBySnapshot') {
                if (Arr::get($this->data, $EventName.'.'.$EventRes.'CoverBySnapshotTask.ErrCode') == '0') {
                    //截取封面图成功
                    $threadVideo->cover_url = Arr::get($this->data, $EventName.'.'.$EventRes.'CoverBySnapshotTask.Output.Url');
                }
            }

            $threadVideo->save();
        }
        //主题审核状态

        return 'success';
    }
}
