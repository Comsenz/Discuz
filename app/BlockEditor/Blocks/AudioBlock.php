<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\BlockEditor\Blocks;

use App\BlockEditor\Parsers\AttatchParser;
use App\BlockEditor\Exception\BlockParseException;
use App\BlockEditor\Parsers\VideoParser;
use App\Models\ThreadVideo;
use Discuz\Auth\AssertPermissionTrait;

class AudioBlock extends BlockAbstract
{
    use AssertPermissionTrait;

    public $type = 'audio';

    public function parse()
    {
        $actor = app('request')->getAttribute('actor');
        $this->assertCan($actor, 'createAudio');

        $this->data['value'] = array_unique($this->data['value']);
        $result = VideoParser::checkVideoExist($this->data['value'], $actor, ThreadVideo::TYPE_OF_AUDIO, $this->post);
        if (!$result) {
            throw new BlockParseException('block_audio_error_file_not_found');
        }
    }
}