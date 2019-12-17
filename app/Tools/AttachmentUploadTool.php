<?php

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
     * @var array
     */
    protected $fileType = [
        'txt',
        'doc',
        'docx',
        'xls',
        'xlsx',
        'ppt',
        'pptx',
        'pdf',
        'jpg',
        'jpeg',
        'png',
        'tiff',
        'swf',
        'mp3',
        'mp4',
        'rar',
        'zip',
    ];

    /**
     * @var int
     */
    protected $fileSize = 5 * 1024 * 1024;
}
