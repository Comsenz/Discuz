<?php

/**
 *      Discuz & Tencent Cloud
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: BatchCreateStopWord.php xxx 2019-10-14 15:20:00 LiuDongdong $
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
        $this->assertCan($this->actor, 'stopWord.createStopWord');

        $overwrite = Arr::get($this->data, 'overwrite', false);

        $result = collect(Arr::get($this->data, 'words'))
            ->unique()
            ->map(function ($word) {
                return $this->parseWord($word);
            })
            ->filter()
            ->unique('find')
            ->when($overwrite, function ($collection) {
                return $collection->map(function ($word) {
                    $word['user_id'] = $this->actor->id;
                    $stopWord = StopWord::updateOrCreate(['find' => $word['find']], $word);
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
                    $stopWord = StopWord::firstOrCreate(['find' => $word['find']], $word);
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

        return $result;
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

            // 区分 ugc 与 username
            $method = ['{MOD}', '{BANNED}', '{REPLACE}'];
            if (strpos($replacement, '|') === false) {
                if (in_array($replacement, $method)) {
                    $ugc = $username = $replacement;
                    $replacement = '**';
                } else {
                    $ugc = $username = '{REPLACE}';
                }
            } else {
                list($ugc, $username) = array_map('trim', explode('|', $replacement));

                if (!in_array($ugc, $method)) {
                    $replacement = $ugc;
                    $ugc = '{REPLACE}';
                }

                if (!in_array($username, $method)) {
                    $replacement = $username;
                    $username = '{REPLACE}';
                }

                $replacement = strpos($replacement, '|') === false ? $replacement : '**';
            }

            // 返回一组可用数据
            return compact('ugc', 'username', 'find', 'replacement');
        } else {
            return [];
        }
    }
}
