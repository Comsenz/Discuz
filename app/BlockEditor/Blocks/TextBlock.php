<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */
namespace App\BlockEditor\Blocks;


class TextBlock extends BlockAbstract
{

    public $type = 'text';

    public function parse()
    {
        // TODO: Implement parse() method.
        return [
             'replace' => ['sss' => 'ss']
        ];
    }

}
