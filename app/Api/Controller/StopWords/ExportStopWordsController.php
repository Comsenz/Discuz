<?php

namespace App\Api\Controller\StopWords;

use App\Models\StopWord;
use Discuz\Http\FileResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class ExportStopWordsController implements RequestHandlerInterface
{
    /**
     * {@inheritdoc}
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
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

        return new FileResponse($filename, 200 , [
            'Content-Disposition' => 'attachment;filename=' . basename($filename),
        ]);
    }
}
