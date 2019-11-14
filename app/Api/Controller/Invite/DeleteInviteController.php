<?php
declare(strict_types=1);

/**
 *      Discuz & Tencent Cloud
 *      This is NOT a freeware, use is subject to license terms
 *
 *      Id: DeleteInviteController.php 28830 2019-10-12 15:47 chenkeke $
 */

namespace App\Api\Controller\Invite;


use Discuz\Api\Controller\AbstractDeleteController;
use Psr\Http\Message\ServerRequestInterface;
use Tobscure\JsonApi\Document;

class DeleteInviteController extends AbstractDeleteController
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
