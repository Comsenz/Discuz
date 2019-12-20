<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Api\Controller\Circle;

use Discuz\Api\Controller\AbstractDeleteController;
use Psr\Http\Message\ServerRequestInterface;
use Tobscure\JsonApi\Document;

class DeleteCircleController extends AbstractDeleteController
{
    /**
     * @param ServerRequestInterface $request
     */
    public function delete(ServerRequestInterface $request)
    {
    }

    protected function data(ServerRequestInterface $request, Document $document)
    {
        // TODO: Implement data() method.
    }
}
