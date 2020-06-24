<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\BlockEditor;

use App\BlockEditor\Blocks\AttachmentBlock;
use App\BlockEditor\Blocks\AudioBlock;
use App\BlockEditor\Blocks\GoodsBlock;
use App\BlockEditor\Blocks\VideoBlock;
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
                if (isset($value['data']['child'])) {//子blocks解析
                    $value['data']['child'] = $this->parseBlocks($value['data']['child'], $level);
                }
                if (!empty($this->parse_types)) {
                    //如果填写了$parse_types 数组，则只解析相应的块。
                    if (!in_array($type, $this->parse_types)) {
                        continue;
                    }
                }
                $parser->setData($value['data']);
                $parser->setPost($this->post);
                $value['data'] = array_merge($value['data'], (array) $parser->parse());
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
            default:
                throw new BlockInvalidException('block_invalid');
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
                        }],
                        "goods": [{
                            "id": "1",
                            "name": "$oooooopoo$"
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
                "style": "1",
                "value": [
                    1, 2, 3, 4, 5, 6, 7, 8, 9, 10
                ]
            }
        }
    ],
    "listBlock": "0"
}

 */

