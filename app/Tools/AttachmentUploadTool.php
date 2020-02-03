<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
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
