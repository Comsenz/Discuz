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

namespace App\Console\Commands;

use Discuz\Console\AbstractCommand;
use Discuz\Foundation\Application;

class RsaCertGenerate extends AbstractCommand
{
    protected $app;

    protected $signature = 'rsa:gen';

    protected $description = '生成 OAUTH2 private.key 和 public.key';

    protected $dn = [
        'countryName' => 'GB',
        'stateOrProvinceName' => 'Somerset',
        'localityName' => 'Glastonbury',
        'organizationName' => 'The Brain Room Limited',
        'organizationalUnitName' => 'PHP Documentation Team',
        'commonName' => 'Wez Furlong',
        'emailAddress' => 'wez@example.com'
    ];

    public function __construct(string $name = null, Application $app)
    {
        parent::__construct($name);

        $this->app = $app;
    }

    /**
     * @inheritDoc
     */
    protected function handle()
    {
        $privateKey = openssl_pkey_new([
            'private_key_bits' => 2048
        ]);

        openssl_pkey_export($privateKey, $privateKeyOut);

        $publicKey = openssl_pkey_get_details($privateKey);

        $privateKeyPath = $this->app->storagePath().'/cert/private.key';
        $publicKeyPath = $this->app->storagePath().'/cert/public.key';

        file_put_contents($privateKeyPath, $privateKeyOut);
        file_put_contents($publicKeyPath, $publicKey['key']);

        chmod($privateKeyPath, 0600);
        chmod($publicKeyPath, 0600);

        $this->info("生成成功\nprivate key: {$privateKeyPath} \npublic key: {$publicKeyPath}");
    }
}
