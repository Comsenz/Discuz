<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\BlockEditor\Blocks;

use App\BlockEditor\Parsers\AttatchParser;
use App\BlockEditor\Exception\BlockParseException;
use App\BlockEditor\Parsers\VideoParser;
use App\Models\Attachment;
use App\Models\ThreadVideo;

class AudioBlock extends BlockAbstract
{
    public $type = 'audio';

    public function parse()
    {
        $this->data['value'] = array_unique($this->data['value']);
        $actor = app('request')->getAttribute('actor');
        $result = VideoParser::checkVideoExist($this->data['value'], $actor, ThreadVideo::TYPE_OF_AUDIO);
        if (!$result) {
            throw new BlockParseException($this->type . ' video not exist');
        }
    }
}
