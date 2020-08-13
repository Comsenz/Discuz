<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */
namespace App\BlockEditor\Blocks;

use App\BlockEditor\Exception\BlockInvalidException;
use App\Censor\Censor;
use App\Models\Post;
use App\Models\PostMod;
use App\Models\Topic;
use App\Models\User;
use Discuz\Auth\AssertPermissionTrait;
use Discuz\SpecialChar\SpecialCharServer;
use Illuminate\Support\Arr;

class TextBlock extends BlockAbstract
{
    use AssertPermissionTrait;

    public $type = 'text';

    public function parse()
    {
        $actor = app('request')->getAttribute('actor');
        $this->assertCan($actor, 'createThread');

        // 敏感词校验
        /** @var Censor $censor */
        $censor = app()->make(Censor::class);
        $this->data['value'] = $censor->checkText($this->data['value']);
        if ($censor->isMod) {
            $this->data['isMod'] = true;
            // 记录触发的审核词
            if ($censor->wordMod) {
                $stopWords = new PostMod;
                $stopWords->stop_word = implode(',', array_unique($censor->wordMod));

                $this->post->stopWords()->save($stopWords);
            }
        }

        //转义、过滤内容
        /**  @var SpecialCharServer $special  */
        $special = app()->make(SpecialCharServer::class);
        $this->data['value'] = $special->purify($this->data['value'], 'textBlockConfig');

        //解析@
        if (isset($this->data['userMentions'])) {
            $userIds = [];
            foreach ($this->data['userMentions'] as $user) {
                $userIds[] = Arr::get($user, 'id');
            }

            $userCount = User::whereIn('id', $userIds)->count();
            if (count($userIds) != $userCount) {
                throw new BlockInvalidException('block_text_error_user_not_found');
            }
        }

        //解析#
        if (isset($this->data['topics'])) {
            $topicIds = [];
            foreach ($this->data['topics'] as $topic) {
                $topicIds[] = Arr::get($topic, 'id');
            }

            $topicCount = Topic::whereIn('id', $topicIds)->count();
            if (count($topicIds) != $topicCount) {
                throw new BlockInvalidException('block_text_error_topic_not_found');
            }
        }


        return $this->data;
    }

}
