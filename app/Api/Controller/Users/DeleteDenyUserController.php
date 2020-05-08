<?php


namespace App\Api\Controller\Users;


use App\Models\DenyUser;
use Discuz\Api\Controller\AbstractDeleteController;
use Discuz\Auth\AssertPermissionTrait;
use Illuminate\Support\Arr;
use Psr\Http\Message\ServerRequestInterface;

class DeleteDenyUserController extends AbstractDeleteController
{
    use AssertPermissionTrait;

    /**
     * @inheritDoc
     * @throws \Discuz\Auth\Exception\NotAuthenticatedException
     */
    protected function delete(ServerRequestInterface $request)
    {
        $actor = $request->getAttribute('actor');

        $this->assertRegistered($actor);

        $id = Arr::get($request->getQueryParams(), 'id');

        if($actor->deny) {
            DenyUser::query()->where([
                'user_id' => $actor->id,
                'deny_user_id' => $id
            ])->delete();
        }
    }
}
