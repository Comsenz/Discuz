<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */
namespace App\BlockEditor\Blocks;


class PayBlock extends BlockAbstract
{

    public $type = 'pay';

    public function parse()
    {
        // TODO: Implement parse() method.
        return [
            'd'=> 's'
        ];
    }

}
