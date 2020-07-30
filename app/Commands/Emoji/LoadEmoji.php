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

namespace App\Commands\Emoji;

use App\Models\Emoji;
use App\Models\User;
use Discuz\Auth\AssertPermissionTrait;
use Discuz\Foundation\EventsDispatchTrait;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Support\Str;

class LoadEmoji
{
    use AssertPermissionTrait;
    use EventsDispatchTrait;

    const EMOJI_PATH_NAME = 'emoji';

    const LEFT_DELIMITER = ':';

    const RIGHT_DELIMITER = ':';

    /**
     * The user performing the action.
     *
     * @var User
     */
    public $actor;

    /**
     * @var
     */
    public $category;

    /**
     * @var
     */
    public $public_path;

    public $model;

    /**
     * LoadEmoji constructor.
     * @param User $actor
     * @param $category
     */
    public function __construct(User $actor, $category)
    {
        $this->actor = $actor;
        $this->category = $category;
        $this->public_path = public_path();
    }

    public function handle(Dispatcher $events)
    {
        $this->events = $events;

        $this->assertAdmin($this->actor);

        if ($this->category == 'all') {
            $emojies = $this->loadEmoji('emoji');

            if ($emojies) {
                foreach ($emojies as $category => $emojies_data) {
                    //删除
                    Emoji::where('category', '=', $category)->delete();

                    $data = [];

                    foreach ($emojies_data as $emojy) {
                        $code_name = self::LEFT_DELIMITER . substr(basename($emojy), 0, strrpos(basename($emojy), '.')) . self::RIGHT_DELIMITER;

                        $data[] = ['url' => $emojy, 'code' => $code_name, 'category' => $category, 'created_at'=>date('Y-m-d H:i:s', time()), 'updated_at'=>date('Y-m-d H:i:s', time())];
                    }
                    Emoji::insert($data);
                }
            }
        } else {
            $path = 'emoji' . DIRECTORY_SEPARATOR . $this->category;

            if (Str::contains($path, '..')) {
                throw new \Exception('invalid_emoji_path');
            }

            if (is_dir($path)) {

                //删除
                Emoji::where('category', '=', $this->category)->delete();

                $emojies = $this->loadEmoji($path);

                foreach ($emojies as $emojy) {
                    if (!is_string($emojy)) {
                        continue;
                    }

                    $code_name = self::LEFT_DELIMITER . substr(basename($emojy), 0, strrpos(basename($emojy), '.')) . self::RIGHT_DELIMITER;

                    $data[] = ['url' => $emojy, 'code' => $code_name, 'category' => $this->category, 'created_at' => date('Y-m-d H:i:s', time()), 'updated_at' => date('Y-m-d H:i:s', time())];
                }
                Emoji::insert($data);
            }
        }
    }

    /**
     * load emoji files return array
     * @param $path
     * @return array
     */
    private function loadEmoji($path)
    {
        $files = [];

        if (is_dir($path)) {
            $basename = basename($path);
            $dirs = opendir($path);

            if ($dirs) {
                while (($file = readdir($dirs)) !== false) {
                    if ($file !== '.' && $file !== '..') {
                        if (is_dir($path . DIRECTORY_SEPARATOR . $file)) {
                            $files[$file] = call_user_func_array([$this,'loadEmoji'], [$path . DIRECTORY_SEPARATOR . $file]);
                        } else {
                            preg_match('/\.(gif|jpg|png)/i', $file) && $files[] = $path . DIRECTORY_SEPARATOR . $file;
                        }
                    }
                }
                closedir($dirs);
            }
        }
        return $files;
    }
}
