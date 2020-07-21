<?php

/**
 * Copyright (C) 2020 Tencent Cloud.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
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
