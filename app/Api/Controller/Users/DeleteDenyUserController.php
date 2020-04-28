<?php


namespace App\Api\Controller\Users;


use App\Models\DenyUser;
use Discuz\Api\Controller\AbstractDeleteController;
use Illuminate\Support\Arr;
use Psr\Http\Message\ServerRequestInterface;

class DeleteDenyUserController extends AbstractDeleteController
{

    /**
     * @inheritDoc
     */
    protected function delete(ServerRequestInterface $request)
    {

        $actor = $request->getAttribute('actor');

        $id = Arr::get($request->getQueryParams(), 'id');

        if($actor->deny) {
            DenyUser::query()->where([
                'user_id' => $actor->id,
                'deny_user_id' => $id
            ])->delete();
        }
    }
}
