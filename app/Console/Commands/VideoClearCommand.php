<?php

/**
 * Copyright (C) 2020 Tencent Cloud.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

namespace App\Console\Commands;

use App\Repositories\ThreadVideoRepository;
use Carbon\Carbon;
use Discuz\Console\AbstractCommand;
use Discuz\Foundation\Application;
use Discuz\Qcloud\QcloudTrait;

class VideoClearCommand extends AbstractCommand
{
    use QcloudTrait;

    protected $signature = 'clear:video';

    protected $description = '清理未发布的主题视频';

    protected $app;

    protected $threadVideo;

    /**
     * AvatarCleanCommand constructor.
     * @param Application $app
     * @param ThreadVideoRepository $threadVideo
     */
    public function __construct(Application $app, ThreadVideoRepository $threadVideo)
    {
        parent::__construct();

        $this->app = $app;
        $this->threadVideo = $threadVideo;
    }

    public function handle()
    {
        //清理前天的未发布主题视频数据
        $threadVideos = $this->threadVideo->query()
            ->where('thread_id', '0')
            ->where('created_at', '<', Carbon::yesterday())
            ->get();

        foreach ($threadVideos as $threadVideo) {
            //云点播删除
            $this->deleteVodMedia($threadVideo->file_id);
            //数据库删除
            $threadVideo->delete();
        }

        $this->info('清理未发布主题视频数量：'. count($threadVideos));
    }
}
