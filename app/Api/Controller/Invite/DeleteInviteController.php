<?php


/**
 *      Discuz & Tencent Cloud
 *      This is NOT a freeware, use is subject to license terms
 *
 *      Id: DeleteInviteController.php 28830 2019-10-12 15:47 chenkeke $
 */

namespace App\Api\Controller\Invite;

use App\Commands\Invite\DeleteInvite;
use Discuz\Api\Controller\AbstractDeleteController;
use Psr\Http\Message\ServerRequestInterface;
use Illuminate\Contracts\Bus\Dispatcher;
use Illuminate\Support\Arr;

class DeleteInviteController extends AbstractDeleteController
{

    /**
     * @param Dispatcher $bus
     */
    public function __construct(Dispatcher $bus)
    {
        $this->bus = $bus;
    }

    /**
     * {@inheritdoc}
     */
    protected function delete(ServerRequestInterface $request)
    {
        $id = Arr::get($request->getQueryParams(), 'id');
        $actor = $request->getAttribute('actor');

        $this->bus->dispatch(
            new DeleteInvite($id, $actor)
        );
    }
}
