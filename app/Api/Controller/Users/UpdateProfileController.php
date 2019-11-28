<?php

namespace App\Api\Controller\Users;


use App\Api\Serializer\UserProfileSerializer;
use App\Api\Serializer\UserSerializer;
use App\Repositories\UserRepository;
use App\Validators\UserValidator;
use Discuz\Api\Controller\AbstractResourceController;
use Discuz\Auth\AssertPermissionTrait;
use Psr\Http\Message\ServerRequestInterface;
use Tobscure\JsonApi\Document;
use Illuminate\Support\Arr;

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

    /**
     * @param ServerRequestInterface $request
     * @param Document $document
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model|mixed
     * @throws \Discuz\Auth\Exception\PermissionDeniedException
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function data(ServerRequestInterface $request, Document $document)
    {
        // 获取当前用户
        $actor = $request->getAttribute('actor');
        $id = Arr::get($request->getQueryParams(), 'id');

        $user = $this->users->findOrFail($id);

        $isSelf = $actor->id === $user->id;
        if(!$isSelf) {
            $this->assertCan($actor, 'edit', $user);
        }

        $validator = [];

        $body = $request->getParsedBody();
        $attributes = Arr::get($body, 'data.attributes');

        if($newPassword = Arr::get($attributes, 'newPassword')) {
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
            $this->assertCan($actor, 'edit.status', $user);
            $user->changeStatus($status);
        }

        $this->validator->valid($validator);

        $user->save();

        return $user;
    }
}
