<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\BlockEditor\Blocks;

use App\BlockEditor\Parsers\AttatchParser;
use App\BlockEditor\Exception\BlockParseException;
use App\Models\Attachment;

class ImageBlock extends BlockAbstract
{
    public $type = 'image';

    public function parse()
    {
        $this->data['value'] = array_unique($this->data['value']);
        $actor = app('request')->getAttribute('actor');
        $result = AttatchParser::checkAttachExist($this->data['value'], $actor, Attachment::TYPE_OF_IMAGE);
        if (!$result) {
            throw new BlockParseException($this->type . ' file not exist');
        }
    }
}
