<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Api\Controller\Statistic;

use Psr\Http\Server\RequestHandlerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use GuzzleHttp\Client;

class MiniProgramStatController implements RequestHandlerInterface
{
    private function getHttpClient()
    {
        return new Client();
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $body = $request->getBody();
        $client = $this->getHttpClient();
        return $client->post("https://h5.udrig.com/app/wx/v1", [
            'body' => $body,
            'headers' => [
                'Content-Type' => 'application/json'
            ]
        ]);
    }
}
