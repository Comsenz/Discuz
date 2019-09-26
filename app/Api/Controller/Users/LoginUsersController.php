<?php


namespace App\Api\Controller\Users;


use App\Api\Serializer\UserSerializer;
use App\Models\User;
use Discuz\Api\Controller\AbstractResourceController;
use Psr\Http\Message\ServerRequestInterface;
use Tobscure\JsonApi\Document;
use Illuminate\Support\Arr;

class LoginUsersController extends AbstractResourceController
{
    public $serializer = UserSerializer::class;

    public function data(ServerRequestInterface $request, Document $document)
    {
        // TODO: Implement data() method.
        
        $username = Arr::get($request->getParsedBody(), 'username');
        $password = Arr::get($request->getParsedBody(), 'password');
        $checked = Arr::get($request->getParsedBody(), 'checked');

        if(!preg_match('/^[a-zA-Z0-9]{4,16}$/',$username)) {
            return json_encode(["code"=>2,'msg'=>"用户名不存在！"]); 
        }

        $data=[
            'username' => $username,
            'password' => User::setUserLoginPasswordAttr($password)
        ];
        $user=User::where($data)->first();
        if($user){
            // var_dump($user);
            $id=$user->id;
            $username=$user->username;
            var_dump($id);
            // session(['id'=>$id,'username'=>$username]);
            if($checked==1){
            //    Cookie::queue('user',json_encode(['username'=>$username,'password'=>$password]));  
            }
            return json_encode(["code"=>1,'msg'=>"登录成功"]);
        }else{
            return json_encode(["code"=>2,'msg'=>"用户名或密码错误"]);
        }

    }
}