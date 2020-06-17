<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */
namespace App\BlockEditor\Blocks;

use App\BlockEditor\Parsers\AttatchParser;
use App\BlockEditor\Exception\BlockParseException;

class ImageBlock extends BlockAbstract
{
    public $type = 'image';

    public function parse()
    {
        $actor = app('request')->getAttribute('actor');
        $result = AttatchParser::checkImageAttachExist($this->data['value'], $actor);
        if (!$result) {
           throw new BlockParseException($this->type . ' file not exist');
        }
    }

}
