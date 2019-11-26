<?php


namespace App\Api\Controller\Users;


use App\Api\Serializer\UserSerializer;
use App\Models\User;
use App\Repositories\UserRepository;
use Discuz\Api\Controller\AbstractResourceController;
use Discuz\Auth\AssertPermissionTrait;
use Illuminate\Support\Arr;
use Psr\Http\Message\ServerRequestInterface;
use Tobscure\JsonApi\Document;

class ProfileController extends AbstractResourceController
{

    use AssertPermissionTrait;

    public $serializer = UserSerializer::class;

    public $optionalInclude = ['wechat', 'groups'];

    protected $users;

    public function __construct(UserRepository $users)
    {
        $this->users = $users;
    }


    /**
     * @param ServerRequestInterface $request
     * @param Document $document
     * @return mixed
     * @throws \Discuz\Auth\Exception\PermissionDeniedException
     * @throws \Tobscure\JsonApi\Exception\InvalidParameterException
     */
    protected function data(ServerRequestInterface $request, Document $document)
    {
        $actor = $request->getAttribute('actor');

        $id = Arr::get($request->getQueryParams(), 'id');

        $user = $this->users->findOrFail($id);

        if($actor->id !== $user->id) {
            $this->assertCan($actor, 'profile', $user);
        }

        $include = $this->extractInclude($request);

        $user->load($include);

        return $user;
    }
}
