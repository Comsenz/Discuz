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
use Illuminate\Encryption\Encrypter;

class KeyGenerate extends AbstractCommand
{
    protected $app;

    public function __construct(Application $app)
    {
        $this->app = $app;

        parent::__construct();
    }

    /**
     * 命令行的名称及签名。
     *
     * @var string
     */
    protected $signature = 'key:generate';

    /**
     * 命令行的描述
     *
     * @var string
     */
    protected $description = '生成站点唯一key，用于HASH';

    /**
     * Fire the command.
     */
    protected function handle()
    {
        $key = $this->generateRandomKey();

        file_put_contents(base_path('config/config.php'), preg_replace('/\'key\'.*,\n/m', "'key' => '{$key}',\n", file_get_contents(base_path('config/config.php'))));

        $this->info('站点唯一key为：'.$key);
    }

    /**
     * Generate a random key for the application.
     *
     * @return string
     */
    protected function generateRandomKey()
    {
        return 'base64:'.base64_encode(
            Encrypter::generateKey($this->app->config('cipher'))
        );
    }
}
