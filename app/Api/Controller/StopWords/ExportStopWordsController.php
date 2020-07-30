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

namespace App\Api\Controller\StopWords;

use App\Models\StopWord;
use Discuz\Auth\AssertPermissionTrait;
use Discuz\Http\DiscuzResponseFactory;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class ExportStopWordsController implements RequestHandlerInterface
{
    use AssertPermissionTrait;

    /**
     * {@inheritdoc}
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $this->assertAdmin($request->getAttribute('actor'));

        // 使用 LazyCollection
        $stopWords = StopWord::cursor()->map(function ($stopWord) {
            if ($stopWord->ugc == '{REPLACE}' && $stopWord->username == 'REPLACE') {
                $replacement = $stopWord->replacement;
            } else {
                $replacement = ($stopWord->ugc == '{REPLACE}' ? $stopWord->replacement : $stopWord->ugc)
                    . '|' . ($stopWord->username == '{REPLACE}' ? $stopWord->replacement : $stopWord->username);
            }

            return $stopWord->find . '=' . $replacement;
        });

        $filename = app()->config('excel.root') . DIRECTORY_SEPARATOR . 'stop-words.txt';

        file_put_contents($filename, '');

        foreach ($stopWords as $stopWord) {
            file_put_contents($filename, $stopWord . "\r\n", FILE_APPEND | LOCK_EX);
        }

        return DiscuzResponseFactory::FileResponse($filename, 200, [
            'Content-Disposition' => 'attachment;filename=' . basename($filename),
        ]);
    }
}
