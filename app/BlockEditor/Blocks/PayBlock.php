<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */
namespace App\BlockEditor\Blocks;

use App\BlockEditor\Exception\BlockParseException;
use App\BlockEditor\Parsers\PayParser;

class PayBlock extends BlockAbstract
{
    public $type = 'pay';

    public function parse()
    {
        if (empty($this->data['child'])) {
            throw new BlockParseException('至少包含一个子块', 500);
        }
        if (isset($this->data['price']) && is_numeric($this->data['price'])) {
            $this->data['price'] = sprintf('%.2f', (float) $this->data['price']);
        } else {
            throw new BlockParseException('未正确设置付费价格', 500);
        }

        $child_types = array_column($this->data['child'], 'type');
        if (in_array('pay', $child_types)) {
            throw new BlockParseException('付费直接块子块不能包含付费块');
        }
        $this->data['blockPayid'] = PayParser::checkPayID($this->data);

        $this->data['freeWords'] = isset($this->data['freeWords']) ? (int) $this->data['freeWords'] : 0;
        $this->data['defaultBlock'] = isset($this->data['defaultBlock']) ? (int) $this->data['defaultBlock'] : 0;
        $this->data['status'] = 0;

        return $this->data;
    }

}
