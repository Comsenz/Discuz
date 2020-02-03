<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Settings;

use Illuminate\Contracts\Filesystem\Factory;

class SiteRevManifest
{
    const SITE_MANIFEST = 'site-manifest.json';

    protected $file;

    public function __construct(Factory $file)
    {
        $this->file = $file;
    }

    public function put($key, $value)
    {
        $site_manifest = $this->getSiteManifest();

        $data = [$key => hash('crc32b', serialize($value))];

        $site_manifest  = array_merge($site_manifest, $data);

        $this->file->disk('public')->put(self::SITE_MANIFEST, json_encode($site_manifest));
    }

    /**
     * @return mixed
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    protected function getSiteManifest()
    {
        $file = $this->file->disk('public');
        if ($file->exists(self::SITE_MANIFEST)) {
            return json_decode($file->get(self::SITE_MANIFEST), true);
        }
        return [];
    }
}
