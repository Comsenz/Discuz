<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\BlockEditor\Blocks;

use App\BlockEditor\Exception\BlockParseException;
use App\Models\PostGoods;

class GoodsBlock extends BlockAbstract
{
    public $type = 'goods';

    public function parse()
    {
        $actor = app('request')->getAttribute('actor');
        $this->data['value'] = array_unique($this->data['value']);

        $result = PostGoods::query()
            ->whereIn('id', $this->data['value'])
            ->where('user_id', $actor->id)
            ->where('post_id', 0)
            ->count();
        if ($result != count($this->data['value'])) {
            throw new BlockParseException('block_goods_error_file_not_found');
        }
    }
}
