<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Exports;

class UsersExport extends Export
{
    public $columnMap;

    public function __construct($filename, $data)
    {
        parent::__construct($filename, $data);

        $this->columnMap = trans('usersExport');
    }


}
