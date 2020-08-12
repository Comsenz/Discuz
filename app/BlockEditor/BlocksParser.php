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

namespace App\BlockEditor;

use App\BlockEditor\Blocks\AttachmentBlock;
use App\BlockEditor\Blocks\AudioBlock;
use App\BlockEditor\Blocks\GoodsBlock;
use App\BlockEditor\Blocks\VideoBlock;
use App\BlockEditor\Blocks\VoteBlock;
use Illuminate\Support\Collection;
use App\BlockEditor\Exception\BlockInvalidException;
use App\BlockEditor\Blocks\BlockAbstract;
use App\BlockEditor\Blocks\TextBlock;
use App\BlockEditor\Blocks\PayBlock;
use App\BlockEditor\Blocks\ImageBlock;
use Illuminate\Support\Arr;

class BlocksParser
{
    public $data;

    public $post;

    /**
     * @var
     * 需要解析的block类型
     */
    public $parse_types;

    public function __construct(Collection $data, $post, $parse_types = [])
    {
        $this->data = $data;
        $this->post = $post;
        $this->parse_types = $parse_types;
    }

    public function parse()
    {
        if (!$this->data->get('blocks')) {
            throw new BlockInvalidException('block_invalid_empty');
        }
        $blocks = $this->parseBlocks($this->data->get('blocks'));
        return collect([$this->data, ['blocks' => $blocks]])->collapse();
    }

    /**
     * 获取块类型列表
     * @return array
     */
    public function BlocksTypeList()
    {
        $typeList = [];
        if (!empty($this->data->get('blocks'))) {
            foreach ($this->data->get('blocks') as $block) {
                $typeList[] = Arr::get($block, 'type');
                if (isset($block['data']['child'])) {
                    foreach ($block['data'] as $payChildBlock) {
                        $typeList[] = Arr::get($payChildBlock, 'type');
                    }
                }
            }
        }

        return array_unique($typeList);
    }

    /**
     * 获取指定类型的value
     * @param $type
     * @return array
     */
    public function BlocksValue($type)
    {
        $valueList = [];
        if (!empty($this->data->get('blocks'))) {
            foreach ($this->data->get('blocks') as $block) {
                //免费快
                if (Arr::get($block, 'type') == $type) {
                    $valueList[] = Arr::get($block, 'data.value');
                }
                //付费快
                if (isset($block['data']['child'])) {
                    foreach ($block['data']['child'] as $payChildBlock) {
                        if (Arr::get($payChildBlock, 'type') == $type) {
                            $valueList[] = Arr::get($payChildBlock, 'data.value');
                        }
                    }
                }
            }
        }

        return array_unique($valueList);
    }

    /**
     * @param $blocks
     * @param int $level json解析的最大层级数
     * @return mixed
     * @throws BlockInvalidException
     */
    private function parseBlocks($blocks, $level = 5)
    {
        if (!$level) {
            throw new BlockInvalidException('block_invalid_level');
        }
        $level--;
        if (!empty($blocks)) {
            foreach ($blocks as $key => &$value) {
                $type = Arr::get($value, 'type');
                $parser = $this->getBlockInstance($type);
                if (!isset($value['data']['value']) && $type != 'pay') {
                    throw new BlockInvalidException('block_invalid_key_not_exist');
                }
                $data = Arr::get($value, 'data');
                if (isset($data['child'])) {//子blocks解析
                    $data['child'] = $this->parseBlocks($data['child'], $level);
                }
                if (!empty($this->parse_types)) {
                    //如果填写了$parse_types 数组，则只解析相应的块。
                    if (!in_array($type, $this->parse_types)) {
                        continue;
                    }
                }
                $parser->setData($data);
                $parser->setPost($this->post);
                $value['data'] = array_merge($data, (array) $parser->parse());
            }
        }
        return $blocks;
    }

    private function getBlockInstance($type): BlockAbstract
    {
        switch ($type) {
            case 'text':
                $parser = TextBlock::getInstance();
                break;
            case 'pay':
                $parser = PayBlock::getInstance();
                break;
            case 'image':
                $parser = ImageBlock::getInstance();
                break;
            case 'attachment':
                $parser = AttachmentBlock::getInstance();
                break;
            case 'audio':
                $parser = AudioBlock::getInstance();
                break;
            case 'video':
                $parser = VideoBlock::getInstance();
                break;
            case 'goods':
                $parser = GoodsBlock::getInstance();
                break;
            case 'vote':
                $parser = VoteBlock::getInstance();
                break;
            default:
                //@TODO 编辑器 其他块处理
//                throw new BlockInvalidException('block_invalid');
                break;
        }
        return $parser;
    }
}

/*
{
    "blocks": [{
            "type": "pay",
            "data": {
                "payid": "xxx",
                "price": 100,
                "defaultBlock": "0",
                "child": [{
                    "type": "text",
                    "data": {
                        "value": "ooo  @yyyy #xxx# :oooo:  $oooooopoo$",
                        "userMentions": [{
                            "id": "1",
                            "name": "@yyyy"
                        },{
                            "id": "2",
                            "name": "@xxx"
                        }],
                        "topics": [{
                            "id": "1",
                            "name": "#xxx#"
                        },{
                            "id": "2",
                            "name": "#yyy#"
                        }],
                        "emoji": [{
                            "name": ":oooo:",
                            "url": "http://xxxx"
                        }]
                    }
                }]
            }
        },
        {
            "type": "text",
            "data": {
                "value": "Editor.js"
            }
        },
        {
            "type": "image",
            "data": {
                "value": [1]
            }
        },
        {
            "type": "attachment",
            "data": {
                "value": [1]
            }
        },
        {
            "type": "attachment",
            "data": {
                "value": [1]
            }
        },
        {
            "type": "audio",
            "data": {
                "value": [1]
            }
        },
        {
            "type": "video",
            "data": {
                "value": [1]
            }
        },
        {
            "type": "goods",
            "data": {
                "value": [1]
            }
        },
        {
            "type": "vote",
            "data": {
                "value": [1]
            }
        }
    ],
    "listBlock": "0"
}

 */
