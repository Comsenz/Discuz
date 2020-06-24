<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\BlockEditor\Blocks;

use App\BlockEditor\Parsers\AttatchParser;
use App\BlockEditor\Exception\BlockParseException;
use App\Models\Attachment;
use Discuz\Auth\AssertPermissionTrait;

class ImageBlock extends BlockAbstract
{
    use AssertPermissionTrait;

    public $type = 'image';

    public function parse()
    {
        $actor = app('request')->getAttribute('actor');
        $this->assertCan($actor, 'attachment.create.1');

        $this->data['value'] = array_unique($this->data['value']);
        $result = AttatchParser::checkAttachExist($this->data['value'], $actor, Attachment::TYPE_OF_IMAGE, $this->post);
        if (!$result) {
            throw new BlockParseException('block_image_error_file_not_found');
        }
    }
}
