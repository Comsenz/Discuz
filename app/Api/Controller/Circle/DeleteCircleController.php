<?php
declare(strict_types=1);

/**
 *      Discuz & Tencent Cloud
 *      This is NOT a freeware, use is subject to license terms
 *
 *      1: DeleteCircleController.php 28830 2019-09-26 10:05 chenkeke $
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

    public function data(ServerRequestInterface $request, Document $document)
    {
        // TODO: Implement data() method.
    }
}