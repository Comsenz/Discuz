<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Api\Controller\Users;

use App\Api\Serializer\UserSerializer;
use App\Repositories\UserRepository;
use Discuz\Api\Controller\AbstractListController;
use Discuz\Auth\AssertPermissionTrait;
use Discuz\Http\UrlGenerator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Psr\Http\Message\ServerRequestInterface;
use Tobscure\JsonApi\Document;

class ListUsersController extends AbstractListController
{
    use AssertPermissionTrait;

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

        $this->assertCan($actor, 'viewUserList');

        $filter = Arr::only($this->extractFilter($request), ['id', 'username', 'mobile', 'group_id', 'bind']);
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
            'size' => (int) $limit,
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

    /**
     * @param Builder $query
     * @param array $filter
     */
    private function applyFilters(Builder $query, array $filter)
    {
        // 用户 id
        if ($id = Arr::get($filter, 'id')) {
            $query->where('id', $id);
        }

        // 用户名
        if ($username = Arr::get($filter, 'username')) {
            // 多个用户名用逗号隔开
            $username = explode(',', $username);

            $query->where(function ($query) use ($username) {
                foreach ($username as $name) {
                    // 用户名前后存在星号（*）则使用模糊查询
                    if (Str::startsWith($name, '*') || Str::endsWith($name, '*')) {
                        $name = Str::replaceLast('*', '%', Str::replaceFirst('*', '%', $name));

                        $query->orWhere('username', 'like', $name);
                    } else {
                        $query->orWhere('username', $name);
                    }
                }
            });
        }

        // 手机号
        if ($mobile = Arr::get($filter, 'mobile')) {
            $query->where('mobile', $mobile);
        }

        // 用户组
        if ($group_id = Arr::get($filter, 'group_id')) {
            $query->join('group_user', 'users.id', '=', 'group_user.user_id')
                ->whereIn('group_id', $group_id);
        }

        // 是否绑定微信
        if ($bind = Arr::get($filter, 'bind')) {
            in_array($bind, $this->optionalInclude) && $query->has($bind);
        }
    }
}
