<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\BlockEditor\Blocks;

abstract class BlockAbstract
{
    public static $instance;

    public $type;//block类型

    public $data;

    public static function getInstance()
    {

        if (!static::$instance instanceof static) {

            static::$instance = new static();
        }
        return static::$instance;
    }

    abstract public function parse();

    public function setData($data)
    {
        $this->data = $data;
    }
}
