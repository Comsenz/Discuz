<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Listeners\Attachment;

use App\Censor\Censor;
use App\Events\Attachment\Saving;
use App\Models\Attachment;
use App\Settings\SettingsRepository;
use Illuminate\Support\Str;
use Psr\Http\Message\ServerRequestInterface;

class CheckImage
{
    /**
     * @var array
     */
    public $data;

    /**
     * @var SettingsRepository
     */
    public $settings;

    /**
     * @var Censor
     */
    public $censor;

    /**
     * @param ServerRequestInterface $request
     * @param SettingsRepository $settings
     * @param Censor $censor
     */
    public function __construct(ServerRequestInterface $request, SettingsRepository $settings, Censor $censor)
    {
        $this->data = $request->getParsedBody();
        $this->settings = $settings;
        $this->censor = $censor;
    }

    public function handle(Saving $event)
    {
        $uploader = $event->uploader;
        $file = $uploader->file;

        // 检测敏感图
        if (Str::startsWith($file->getMimeType(), 'image/')) {
            $isRemote = $uploader->isRemote();

            $image = $isRemote
                ? $uploader->getUrl()
                : storage_path('app/' . $file->hashName($uploader->getPath()));

            $this->censor->checkImage($image, $isRemote);

            if ($this->censor->isMod) {
                $event->attachment->is_approved = Attachment::UNAPPROVED;
            }
        }
    }
}
