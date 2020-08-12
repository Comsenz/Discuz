<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\BlockEditor\Blocks;

use App\BlockEditor\Exception\BlockParseException;
use App\Models\Vote;

class VoteBlock extends BlockAbstract
{
    public $type = 'vote';

    public function parse()
    {
        $actor = app('request')->getAttribute('actor');
        $this->assertCan($actor, 'createVote');

        $this->data['value'] = array_unique($this->data['value']);
        $result = Vote::query()
            ->whereIn('id', $this->data['value'])
            ->where('thread_id', 0)
            ->count();
        if ($result != count($this->data['value'])) {
            throw new BlockParseException('block_vote_error_file_not_found');
        }
    }
}
