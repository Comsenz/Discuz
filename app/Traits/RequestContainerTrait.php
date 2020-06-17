<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Traits;

use Psr\Http\Message\ServerRequestInterface;

trait RequestContainerTrait
{
    protected function setSiteRequest(ServerRequestInterface $request)
    {
        app()->instance('request', $request);
        app()->alias('request', ServerRequestInterface::class);
    }
}
