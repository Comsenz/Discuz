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

class VideoBlock extends BlockAbstract
{
    public $type = 'video';

    public function parse()
    {
        $actor = app('request')->getAttribute('actor');
        $result = VideoParser::checkVideoExist($this->data['value'], $actor, ThreadVideo::TYPE_OF_VIDEO);
        if (!$result) {
            throw new BlockParseException($this->type . ' video not exist');
        }
    }
}
