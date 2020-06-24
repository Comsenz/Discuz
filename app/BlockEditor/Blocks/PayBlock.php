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
            throw new BlockParseException('block_parse_error_need_child');
        }
        if (isset($this->data['price']) && is_numeric($this->data['price'])) {
            $this->data['price'] = sprintf('%.2f', (float) $this->data['price']);
        } else {
            throw new BlockParseException('block_pay_error_price');
        }

        $child_types = array_column($this->data['child'], 'type');
        if (in_array('pay', $child_types)) {
            throw new BlockParseException('block_pay_error_child_pay');
        }
        $this->data['blockPayid'] = PayParser::checkPayID($this->data);

        $this->data['freeWords']    = isset($this->data['freeWords']) ? (int) $this->data['freeWords'] : 0;
        $this->data['defaultBlock'] = isset($this->data['defaultBlock']) ? (int) $this->data['defaultBlock'] : 0;
        $this->data['status']       = false;

        return $this->data;
    }

}
