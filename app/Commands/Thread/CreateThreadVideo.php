<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Commands\Thread;

use App\Models\ThreadVideo;
use App\Models\User;
use App\Repositories\ThreadRepository;
use App\Settings\SettingsRepository;
use Carbon\Carbon;
use Discuz\Auth\AssertPermissionTrait;
use Discuz\Auth\Exception\PermissionDeniedException;
use Discuz\Foundation\EventsDispatchTrait;
use Illuminate\Contracts\Events\Dispatcher as EventDispatcher;
use Illuminate\Support\Arr;
use GuzzleHttp\Client as HttpClient;

class CreateThreadVideo
{
    use AssertPermissionTrait;
    use EventsDispatchTrait;

    const API_URL = 'vod.tencentcloudapi.com';

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
     * The id of the thread.
     *
     * @var int
     */
    public $threadId;

    /**
     * CreateThread constructor.
     * @param User $actor
     * @param $threadId
     * @param array $data
     */
    public function __construct(User $actor, $threadId, array $data)
    {
        $this->actor = $actor;
        $this->data = $data;
        $this->threadId = $threadId;
    }

    /**
     * @param EventDispatcher $events
     * @param SettingsRepository $settings
     * @param ThreadRepository $thread
     * @param ThreadVideo $threadVideo
     * @return ThreadVideo
     * @throws PermissionDeniedException
     */
    public function handle(EventDispatcher $events, SettingsRepository $settings, ThreadRepository $thread, ThreadVideo $threadVideo)
    {
        $this->events = $events;

        $thread = $thread->findOrFail($this->threadId);

        $threadVideo->user_id   = $this->actor->id;
        $threadVideo->thread_id = $thread->id;
        $threadVideo->status    = $threadVideo::VIDEO_STATUS_TRANSCODING;
        $threadVideo->file_name = Arr::get($this->data, 'attributes.file_name');
        $threadVideo->file_id   = Arr::get($this->data, 'attributes.file_id');
        $threadVideo->media_url = Arr::get($this->data, 'attributes.media_url')?:'';
        $threadVideo->cover_url = Arr::get($this->data, 'attributes.cover_url')?:'';

        $threadVideo->save();
        //调取腾讯云点播转码API
        $SecretId = $settings->get('qcloud_secret_id', 'qcloud');
        $secretKey = $settings->get('qcloud_secret_key', 'qcloud');
        $qcloudVodTranscode = $settings->get('qcloud_vod_transcode', 'qcloud');

        $param = [
            'Action' => 'ProcessMedia',
            'FileId' => $threadVideo->file_id,
            'MediaProcessTask.CoverBySnapshotTaskSet.0.Definition' => '10',
            'MediaProcessTask.CoverBySnapshotTaskSet.0.PositionType' => 'Time',
            'MediaProcessTask.CoverBySnapshotTaskSet.0.PositionValue' => '0',
            'MediaProcessTask.TranscodeTaskSet.0.Definition' => $qcloudVodTranscode,
            'Nonce' => rand(),
            'SecretId' => $SecretId,
            'Timestamp' => Carbon::now()->timestamp,
            'Version' => '2018-07-17',
        ];

        $paramStr = http_build_query($param);
        $srcStr = 'GET'.self::API_URL.'/?'.$paramStr;
        $signStr = base64_encode(hash_hmac('sha1', $srcStr, $secretKey, true));
        $param['Signature'] = $signStr;


        $client = new HttpClient([
            'base_uri' => self::API_URL,
            'timeout'  =>  15
        ]);
        $res = $client->request('GET', 'https://'.self::API_URL.'/', ['query' => $param]);

        $res->getBody()->getContents();

        return $threadVideo;
    }
}
