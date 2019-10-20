<?php
declare(strict_types=1);

/**
 *      Discuz & Tencent Cloud
 *      This is NOT a freeware, use is subject to license terms
 *
 *      Id: ImageUploadTool.php 28830 2019-10-18 18:11 chenkeke $
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