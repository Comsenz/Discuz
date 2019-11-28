<?php


namespace App\Api\Controller\Users;


use App\Api\Serializer\UserSerializer;
use App\Repositories\UserRepository;
use App\User\AvatarUploader;
use Discuz\Api\Controller\AbstractResourceController;
use Discuz\Auth\AssertPermissionTrait;
use Illuminate\Support\Arr;
use Psr\Http\Message\ServerRequestInterface;
use Tobscure\JsonApi\Document;

class DeleteAvatarController extends AbstractResourceController
{

    use AssertPermissionTrait;

    public $serializer = UserSerializer::class;

    protected $users;
    protected $avatarUploader;

    public function __construct(UserRepository $users, AvatarUploader $avatarUploader)
    {
        $this->users = $users;
        $this->avatarUploader = $avatarUploader;
    }


    /**
     * @param ServerRequestInterface $request
     * @param Document $document
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model|mixed
     * @throws \Discuz\Auth\Exception\PermissionDeniedException
     */
    protected function data(ServerRequestInterface $request, Document $document)
    {
        $actor = $request->getAttribute('actor');
        $id = Arr::get($request->getQueryParams(), 'id');

        $user = $this->users->findOrFail($id);
        $this->assertCan($actor, 'edit', $user);

        $this->avatarUploader->remove($user);

        $user->save();

        return $user;
    }
}
