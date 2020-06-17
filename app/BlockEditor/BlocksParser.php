<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\BlockEditor;

use Illuminate\Support\Collection;
use App\BlockEditor\Exception\TestException;
use App\BlockEditor\Blocks\TextBlock;
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
        $blocks = $this->data->get('blocks');
        $data = [];
        if (!empty($blocks)) {
            foreach ($blocks as $key => $value) {
                $type = Arr::get($value, 'type');
                $parser = self::getBlockInstance($type);
                $value['data'] = $value['data'] + $parser->parse();
                array_push($data, $value);
            }
        }
        return $data;
    }

    private function getBlockInstance($type)
    {
        switch ($type) {
            case 'text':
                return TextBlock::getInstance();
                break;
            default:
                throw new TestException($type . ' not exist', 500);
        }
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
