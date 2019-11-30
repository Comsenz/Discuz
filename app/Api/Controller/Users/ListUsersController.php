<?php
declare(strict_types=1);

namespace App\Api\Controller\Users;


use App\Api\Serializer\UserSerializer;
use App\Repositories\UserRepository;
use Discuz\Api\Controller\AbstractListController;
use Discuz\Http\UrlGenerator;
use Illuminate\Support\Str;
use Psr\Http\Message\ServerRequestInterface;
use Tobscure\JsonApi\Document;
use Illuminate\Support\Arr;

class ListUsersController extends AbstractListController
{

    public $serializer = UserSerializer::class;

    //默认排序
    public $sort = [];

    //能排序的字段
    public $sortFields = ['createdAt'];

    public $include = ['groups'];

    public $optionalInclude = ['wechat'];

    protected $users;
    protected $url;

    public function __construct(UserRepository $users, UrlGenerator $url)
    {
        $this->users = $users;
        $this->url = $url;
    }


    /**
     * @param ServerRequestInterface $request
     * @param Document $document
     * @return mixed
     * @throws \Tobscure\JsonApi\Exception\InvalidParameterException
     */
    protected function data(ServerRequestInterface $request, Document $document)
    {
        // 获取当前用户
        $actor = $request->getAttribute('actor');

        //权限控制
        $query = $this->users->query()->whereVisibleTo($actor);;

        $sorts = $this->extractSort($request);
        $offset = $this->extractOffset($request);
        $limit = $this->extractLimit($request);

        $include = $this->extractInclude($request);

        $filter = Arr::only($this->extractFilter($request), ['username', 'id', 'group_id', 'bind']);

        $this->applyFilters($query, $filter);

        //统计总数
        $count = $query->count();

        //分页
        $query->skip($offset)->take($limit);

        //排序
        foreach ($sorts as $sort => $direction) {
            $query->orderBy(Str::snake($sort), $direction);
        }

        $data = $query->get();

        //关联数据
        $data->load($include);

        //添加分页
        $document->addPaginationLinks(
            $this->url->route('users.list'),
            $request->getQueryParams(),
            $offset,
            $limit,
            $count
        );

        //设置meta
        $document->setMeta([
            'total' => $count,
            'size' => (int)$limit
        ]);

        return $data;

    }


    private function applyFilters($query, $filter)
    {
        //条件搜索
        if($username = Arr::get($filter, 'username')) {
            $query->where('username', 'like', '%'.$username.'%');
        }

        //uid 查找
        if($id = Arr::get($filter, 'id')) {
            $query->where('id', $id);
        }

        //用户组搜索
        if($group_id = Arr::get($filter, 'group_id')) {
            $query->join('group_user', 'users.id', '=', 'group_user.user_id')->whereIn('group_id', $group_id);
        }

        //是否绑定微信
        if($bind = Arr::get($filter, 'bind')) {
            in_array($bind, $this->optionalInclude) && $query->has($bind);
        }
    }
}
