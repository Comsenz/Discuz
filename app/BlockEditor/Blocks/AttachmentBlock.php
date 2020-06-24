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

class AttachmentBlock extends BlockAbstract
{
    use AssertPermissionTrait;

    public $type = 'attachment';

    public function parse()
    {
        $actor = app('request')->getAttribute('actor');
        $this->assertCan($actor, 'attachment.create.0');

        $this->data['value'] = array_unique($this->data['value']);
        $result = AttatchParser::checkAttachExist($this->data['value'], $actor, Attachment::TYPE_OF_FILE, $this->post);
        if (!$result) {
            throw new BlockParseException($this->type . ' file not exist');
        }
    }
}
