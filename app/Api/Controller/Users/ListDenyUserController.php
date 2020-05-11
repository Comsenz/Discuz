<?php


namespace App\Api\Controller\Users;


use App\Api\Serializer\UserSerializer;
use App\Repositories\UserRepository;
use Discuz\Api\Controller\AbstractListController;
use Discuz\Auth\AssertPermissionTrait;
use Illuminate\Contracts\Routing\UrlGenerator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Psr\Http\Message\ServerRequestInterface;
use Tobscure\JsonApi\Document;

class ListDenyUserController extends AbstractListController
{

    use AssertPermissionTrait;

    public $serializer = UserSerializer::class;

    /**
     * @var UserRepository
     */
    protected $users;

    /**
     * @var UrlGenerator
     */
    protected $url;

    /**
     * @var int|null
     */
    protected $userCount;

    /**
     * @param UserRepository $users
     * @param UrlGenerator $url
     */
    public function __construct(UserRepository $users, UrlGenerator $url)
    {
        $this->users = $users;
        $this->url = $url;
    }

    /**
     * @inheritDoc
     * @throws \Tobscure\JsonApi\Exception\InvalidParameterException
     * @throws \Discuz\Auth\Exception\NotAuthenticatedException
     */
    protected function data(ServerRequestInterface $request, Document $document)
    {
        $actor = $request->getAttribute('actor');

        $this->assertRegistered($actor);

        $query = $request->getQueryParams();
        $id = Arr::get($query, 'id');

        Arr::forget($query, 'id');

        $sort = $this->extractSort($request);

        $limit = $this->extractLimit($request);
        $offset = $this->extractOffset($request);

        $users = $this->search($actor, $sort, $limit, $offset);

        $document->addPaginationLinks(
            $this->url->to('/api/users/'.$id.'/deny'),
            $query,
            $offset,
            $limit,
            $this->userCount
        );

        $document->setMeta([
            'total' => $this->userCount,
            'pageCount' => ceil($this->userCount / $limit),
        ]);

        return $users;
    }


    /**
     * @param $actor
     * @param $sort
     * @param int|null $limit
     * @param int $offset
     *
     * @return Collection
     */
    public function search($actor, $sort, $limit = null, $offset = 0)
    {
        $query = $this->users->query();

        $query->leftJoin('deny_users', 'id', '=', 'deny_user_id')->where('user_id', $actor->id);

        $query->selectRaw('*, true as denyStatus');

        $this->userCount = $limit > 0 ? $query->count() : null;

        $query->skip($offset)->take($limit);

        foreach ((array) $sort as $field => $order) {
            $query->orderBy(Str::snake($field), $order);
        }

        return $query->get();
    }
}
