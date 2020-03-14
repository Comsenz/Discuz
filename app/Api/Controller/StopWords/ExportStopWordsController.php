<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
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
        $actor = $request->getAttribute('actor');

        $this->assertAdmin($actor);

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

        if (file_exists($filename)) {
            unlink($filename);
        }

        foreach ($stopWords as $stopWord) {
            file_put_contents($filename, $stopWord . "\r\n", FILE_APPEND | LOCK_EX);
        }

        return DiscuzResponseFactory::FileResponse($filename, 200, [
            'Content-Disposition' => 'attachment;filename=' . basename($filename),
        ]);
    }
}
