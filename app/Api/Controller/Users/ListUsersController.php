<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Api\Controller\Users;

use App\Api\Serializer\UserSerializer;
use App\Repositories\UserRepository;
use App\Traits\UserTrait;
use Discuz\Api\Controller\AbstractListController;
use Discuz\Auth\AssertPermissionTrait;
use Discuz\Http\UrlGenerator;
use Discuz\Qcloud\QcloudTrait;
use GuzzleHttp\Client;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Tobscure\JsonApi\Document;

class ListUsersController extends AbstractListController
{
    use AssertPermissionTrait;
    use UserTrait;
    use QcloudTrait;

    /**
     * {@inheritdoc}
     */
    public $serializer = UserSerializer::class;

    /**
     * {@inheritdoc}
     */
    public $include = ['groups'];

    /**
     * {@inheritdoc}
     */
    public $optionalInclude = ['wechat'];

    /**
     * {@inheritdoc}
     */
    public $sortFields = ['createdAt'];

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
     * {@inheritdoc}
     */
    protected function data(ServerRequestInterface $request, Document $document)
    {
        $actor = $request->getAttribute('actor');

        try {
            $a = $this->report(['url' => 'alsdjflk'])->then(function (ResponseInterface $response) {
                $data = json_decode($response->getBody()->getContents(), true);
                dump($data);
//                $this->setting->set('site_id', Arr::get($data, 'site_id'));
//                $this->setting->set('site_secret', Arr::get($data, 'site_secret'));
            }, function ($a) {
                dd($a);
            })->wait();
        } catch (\Exception $e) {
        }
        dd('ok', $a);

        $this->assertCan($actor, 'viewUserList');

        $filter = Arr::only($this->extractFilter($request), ['id', 'username', 'mobile', 'group_id', 'wechat', 'status']);
        $sort = $this->extractSort($request);

        $limit = $this->extractLimit($request);
        $offset = $this->extractOffset($request);
        $include = $this->extractInclude($request);

        $users = $this->search($actor, $filter, $sort, $limit, $offset);

        $users->load($include);

        $document->addPaginationLinks(
            $this->url->route('users.list'),
            $request->getQueryParams(),
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
     * @param $filter
     * @param $sort
     * @param int|null $limit
     * @param int $offset
     *
     * @return Collection
     */
    public function search($actor, $filter, $sort, $limit = null, $offset = 0)
    {
        $query = $this->users->query()->whereVisibleTo($actor);

        $this->applyFilters($query, $filter);

        $this->userCount = $limit > 0 ? $query->count() : null;

        $query->skip($offset)->take($limit);

        foreach ((array) $sort as $field => $order) {
            $query->orderBy(Str::snake($field), $order);
        }

        return $query->get();
    }
}
