<?php

/**
 *      Discuz & Tencent Cloud
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: BatchCreateStopWord.php xxx 2019-10-14 15:20:00 LiuDongdong $
 */

namespace App\Commands\StopWord;

use App\Events\StopWord\Saving;
use App\Models\StopWord;
use App\Models\User;
use Discuz\Foundation\EventsDispatchTrait;
use Illuminate\Contracts\Events\Dispatcher as EventDispatcher;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Validation\Factory as Validator;

class BatchCreateStopWord
{
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
     * @param Collection $data The attributes of the new group.
     */
    public function __construct($actor, Collection $data)
    {
        // TODO: User $actorÅÅ
        $this->actor = $actor;
        $this->data = $data;
    }

    /**
     * @param EventDispatcher $events
     * @return bool
     */
    public function handle(EventDispatcher $events)
    {
        $this->events = $events;

        // TODO: 权限验证
        // $this->assertCan($this->actor, 'startDiscussion');

        $user_id = $this->actor->id;

        $this->data
            ->unique()
            ->map(function ($word) {
                return $this->parseWord($word);
            })
            ->filter()
            ->unique('find')
            ->each(function ($word) use ($user_id) {
                $word['user_id'] = $user_id;
                StopWord::updateOrCreate(['find' => $word['find']], $word);
            });

        return null;
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
