<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */
namespace App\BlockEditor\Blocks;

use App\Censor\Censor;

class TextBlock extends BlockAbstract
{

    public $type = 'text';

    public function parse()
    {
        // 敏感词校验
        /** @var Censor $censor */
        $censor = app()->make(Censor::class);
        $this->data['value'] = $censor->checkText($this->data['value']);

        //解析内容blockquote span
        /**  html  */
        $this->data['value'] = htmlspecialchars($this->data['value']);

        return $this->data;
    }
}
