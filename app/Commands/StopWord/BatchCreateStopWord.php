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

namespace App\Commands\StopWord;

use App\Models\StopWord;
use App\Models\User;
use Discuz\Auth\AssertPermissionTrait;
use Discuz\Auth\Exception\NotAuthenticatedException;
use Discuz\Auth\Exception\PermissionDeniedException;
use Discuz\Foundation\EventsDispatchTrait;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;

class BatchCreateStopWord
{
    use AssertPermissionTrait;
    use EventsDispatchTrait;

    /**
     * The user performing the action.
     *
     * @var User
     */
    public $actor;

    /**
     * The attributes of the new stop word.
     *
     * @var array
     */
    public $data;

    /**
     * @param User $actor The user performing the action.
     * @param array $data The attributes of the new group.
     */
    public function __construct(User $actor, array $data)
    {
        $this->actor = $actor;
        $this->data = $data;
    }

    /**
     * @return Collection
     * @throws NotAuthenticatedException
     * @throws PermissionDeniedException
     */
    public function handle()
    {
        $this->assertRegistered($this->actor);
        $this->assertCan($this->actor, 'create');

        $overwrite = Arr::get($this->data, 'overwrite', false);

        return collect(Arr::get($this->data, 'words'))
            ->unique()
            ->map(function ($word) {
                return $this->parseWord($word);
            })
            ->filter()
            ->unique('find')
            ->when($overwrite, function ($collection) {
                return $collection->map(function ($word) {
                    $word['user_id'] = $this->actor->id;
                    $stopWord = StopWord::query()->updateOrCreate(['find' => $word['find']], $word);
                    if ($stopWord->wasRecentlyCreated) {
                        return 'created';
                    } elseif ($stopWord->wasChanged()) {
                        return 'updated';
                    } else {
                        return 'unique';
                    }
                });
            }, function ($collection) {
                return $collection->map(function ($word) {
                    $word['user_id'] = $this->actor->id;
                    $stopWord = StopWord::query()->firstOrCreate(['find' => $word['find']], $word);
                    if ($stopWord->wasRecentlyCreated) {
                        return 'created';
                    } elseif ($stopWord->wasChanged()) {
                        return 'updated';
                    } else {
                        return 'unique';
                    }
                });
            })
            ->countBy();
    }

    /**
     * 按规则解析一组敏感词
     * abc
     * abc=123
     * abc={MOD}|123
     * abc=123|{BANNED}
     *
     * @param $word
     * @return array
     */
    public function parseWord($word)
    {
        if (is_string($word)) {
            // 区分 find 与 replacement
            if (strpos($word, '=') === false) {
                $find = trim($word);
                $replacement = '**';
            } else {
                list($find, $replacement) = array_map('trim', explode('=', trim($word)));
                $replacement = trim($replacement) ?: '**';
            }

            // 空数据
            if (empty($find)) {
                return [];
            }

            // 区分 ugc username signature dialog
            if (strpos($replacement, '|') === false) {
                if (in_array($replacement, StopWord::$allowTypes)) {
                    $ugc = $replacement;
                    $replacement = '**';
                } else {
                    $ugc = StopWord::REPLACE;
                }

                $username = StopWord::IGNORE;
                $signature = StopWord::IGNORE;
                $dialog = StopWord::IGNORE;
            } else {
                list($ugc, $username, $signature, $dialog) = array_map('trim', explode('|', $replacement));

                if (! in_array($ugc, StopWord::$allowTypes)) {
                    $replacement = $ugc;
                    $ugc = StopWord::REPLACE;
                }

                if (! in_array($username, [StopWord::MOD, StopWord::BANNED])) {
                    $username = StopWord::IGNORE;
                }

                $signature = $signature === StopWord::BANNED ? StopWord::BANNED : StopWord::IGNORE;
                $dialog = $dialog === StopWord::BANNED ? StopWord::BANNED : StopWord::IGNORE;

                $replacement = strpos($replacement, '|') === false ? $replacement : '**';
            }

            // 返回一组可用数据
            return compact('ugc', 'username', 'signature', 'dialog', 'find', 'replacement');
        } else {
            return [];
        }
    }
}
