<?php
declare(strict_types=1);

namespace App\Api\Controller\Users;


use App\Api\Serializer\UserSerializer;
use App\Models\User;
use App\Models\CircleUser;
use Discuz\Api\Controller\AbstractListController;
use Psr\Http\Message\ServerRequestInterface;
use Tobscure\JsonApi\Document;
use Illuminate\Support\Arr;

class ListUsersController extends AbstractListController
{
    public $serializer = UserSerializer::class;

    public function data(ServerRequestInterface $request, Document $document)
    {
        // TODO: Implement data() method.
        $data = $request->getParsedBody();
        // $data=json_decode(json_encode($data),true);
        $num=20;
        $offset=(Arr::get($data, 'page') - 1)*$num;
        unset($data['page']);
        $is_wx=Arr::get($data, 'is_wx');
        unset($data['is_wx']);
        //管理 or 圈子
        $arr=[];
        foreach($data as $k=>$v){
            if($v){
                array_push($arr,["$k",'like',"%$v%"]);
            }
        }
        if($is_wx==1){
            $users = User::where($arr)
            ->join('user_wechat', 'user_wechat.id', '=', 'users.id')
            ->orderBy('adminid','asc')
            ->offset($offset)->limit($num)->get();
        }else{
            $users = User::where($arr)->orderBy('adminid','asc')->offset($offset)->limit($num)->get();
        }
        return $users;
    }
}
