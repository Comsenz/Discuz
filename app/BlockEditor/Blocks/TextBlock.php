<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */
namespace App\BlockEditor\Blocks;

use App\BlockEditor\Exception\BlockInvalidException;
use App\Censor\Censor;
use App\Models\Topic;
use App\Models\User;
use Discuz\SpecialChar\SpecialCharServer;
use Illuminate\Support\Arr;

class TextBlock extends BlockAbstract
{

    public $type = 'text';

    public function parse()
    {
        // 敏感词校验
        /** @var Censor $censor */
        $censor = app()->make(Censor::class);
        $this->data['value'] = $censor->checkText($this->data['value']);
        if ($censor->isMod) {
            $this->data['isMod'] = true;
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
                throw new BlockInvalidException('用户不存在');
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
                throw new BlockInvalidException('话题不存在');
            }
        }


        return $this->data;
    }

}
