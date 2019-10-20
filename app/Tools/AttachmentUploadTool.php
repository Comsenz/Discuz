<?php
declare(strict_types=1);

/**
 *      Discuz & Tencent Cloud
 *      This is NOT a freeware, use is subject to license terms
 *
 *      Id: AttachmentUploadTool.php 28830 2019-10-10 12:00 chenkeke $
 */

namespace App\Tools;

use Discuz\Foundation\AbstractUploadTool;

class AttachmentUploadTool extends AbstractUploadTool
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