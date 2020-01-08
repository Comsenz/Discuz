<?php


namespace App\Install\Controller;


use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Laminas\Diactoros\Response;
use Laminas\Diactoros\Stream;

class StreamController implements RequestHandlerInterface
{

    /**
     * @inheritDoc
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        header('Access-Control-Allow-Origin: *');
        header('Content-Type: text/event-stream');
        header('Cache-Control: no-cache');

        // output data of each row

//        echo 'data: ' . time() . "\n\n";
//        flush();
//
        exit;
    }

    protected function send() {
        // output data of each row
        for($i =0; $i < 5; $i++) {
            yield time();
        }
    }
}
