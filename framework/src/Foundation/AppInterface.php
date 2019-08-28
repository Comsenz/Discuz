<?php


namespace Discuz\Foundation;


use Psr\Http\Server\RequestHandlerInterface;

interface AppInterface
{
    public function getRequestHandler() : RequestHandlerInterface;

}
