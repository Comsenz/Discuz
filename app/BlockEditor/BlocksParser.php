<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\BlockEditor;

use Illuminate\Support\Collection;
use App\BlockEditor\Exception\TestException;
use App\BlockEditor\Blocks\TextBlock;
use App\BlockEditor\Blocks\PayBlock;
use Illuminate\Support\Arr;

class BlocksParser
{
    public $data;

    public function __construct(Collection $data)
    {
        $this->data = $data;
    }

    public function parse()
    {
        $blocks = $this->parseBlocks($this->data->get('blocks'));
        return collect([$this->data, ['blocks' => $blocks]])->collapse();
    }

    private function parseBlocks($blocks)
    {
        if (!empty($blocks)) {
            foreach ($blocks as $key => &$value) {
                $type = Arr::get($value, 'type');
                $parser = $this->getBlockInstance($type);
                if (isset($value['data']['child'])) {//子blocks解析
                    $value['data']['child'] = $this->parseBlocks($value['data']['child']);
                }
                $value['data'] += (array) $parser->parse();
            }
        }
        return $blocks;
    }

    private function getBlockInstance($type)
    {
        $parser = '';
        switch ($type) {
            case 'text':
                $parser = TextBlock::getInstance();
                break;
            case 'pay':
                $parser = PayBlock::getInstance();
                break;
            default:
                throw new TestException($type . ' not exist', 500);
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
						"metion": [{
							"id": "1",
							"name": "@yyyy"
						}],
						"topic": [{
							"id": "1",
							"name": "#xxx#"
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
