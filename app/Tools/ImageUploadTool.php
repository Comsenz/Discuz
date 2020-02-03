<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Tools;

use Discuz\Foundation\AbstractUploadTool;

class ImageUploadTool extends AbstractUploadTool
{
    /**
     * @var type
     */
    protected $fileType = [];

    /**
     * @var type
     */
    protected $fileSize = 5*1024*1024;
}
