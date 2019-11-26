<?php
declare(strict_types=1);

namespace App\Api\Controller\Users;


use App\Api\Serializer\UserSerializer;
use App\Repositories\UserRepository;
use App\Validators\UserValidator;
use Discuz\Api\Controller\AbstractResourceController;
use Discuz\Auth\AssertPermissionTrait;
use Psr\Http\Message\ServerRequestInterface;
use Tobscure\JsonApi\Document;
use Illuminate\Support\Arr;
use App\Commands\UserProfile\UpdateUserProfile;
use App\Commands\UserProfile\UserProfile;

class UpdateProfileController extends AbstractResourceController
{
    use AssertPermissionTrait;

    public $serializer = UserSerializer::class;

    protected $users;

    protected $validator;

    public function __construct(UserRepository $users, UserValidator $validator)
    {
        $this->users = $users;
        $this->validator = $validator;
    }

    protected function data(ServerRequestInterface $request, Document $document)
    {
        // 获取当前用户
        $actor = $request->getAttribute('actor');
        $id = Arr::get($request->getQueryParams(), 'id');

        $user = $this->users->findOrFail($id);

        $isSelf = $actor->id === $user->id;
        $canEdit = $actor->can('edit', $user);

        $validator = [];

        $body = $request->getParsedBody();
        $attributes = Arr::get($body, 'data.attributes');

        if($newPassword = Arr::get($attributes, 'newPassword')) {
            $this->assertPermission($canEdit);
            if($isSelf) {
                $verifyPwd = $user->checkPassword(Arr::get($attributes, 'password'));
                $this->assertPermission($verifyPwd);

                $this->validator->setUser($user);
                $validator['password_confirmation'] = Arr::get($attributes, 'password_confirmation');
            }
            $user->changePassword($newPassword);
            $validator['password'] = $newPassword;
        }

        if($mobile = Arr::get($body, 'data.attributes.mobile')) {
            $user->changeMobile($mobile);
        }

        if($status = Arr::get($body, 'data.attributes.status')) {
            $user->changeStatus($status);
        }

        $this->validator->valid($validator);

        $user->save();

        return $user;

        // 获取请求的参数
//        $inputs = $request->getParsedBody();
        // 获取请求的IP
//        $ipAddress = Arr::get($request->getServerParams(), 'REMOTE_ADDR', '127.0.0.1');
//        $data = $this->bus->dispatch(
//            new UpdateUserProfile($id,$actor, $inputs->toArray(), $ipAddress)
//        );
//        $data = $this->bus->dispatch(
//            new UserProfile($id, $actor)
//        );

    }
}
