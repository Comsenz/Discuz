<?php


namespace App\Api\Controller\Users;


use App\Api\Serializer\UserSerializer;
use App\Models\User;
use Discuz\Api\Controller\AbstractCreateController;
use Psr\Http\Message\ServerRequestInterface;
use Tobscure\JsonApi\Document;
use Illuminate\Support\Arr;

class AddUsersController extends AbstractCreateController
{
    public $serializer = UserSerializer::class;

    public function data(ServerRequestInterface $request, Document $document)
    {   
        $username = Arr::get($request->getParsedBody(), 'username');
        $password = Arr::get($request->getParsedBody(), 'password');
        $password2 = Arr::get($request->getParsedBody(), 'password2');

        if(!preg_match('/^[a-zA-Z0-9]{4,16}$/',$username)) {
            return json_encode(["code"=>4,'msg'=>"用户名数字字母4-16位！"]); 
        }

        if(!preg_match('/^(?![0-9]+$)(?![a-z]+$)(?![A-Z]+$)[0-9A-Za-z]{6,16}$/',$password)) {
            return json_encode(["code"=>4,'msg'=>"密码6-16位且至少一位数字，一位大写字母，一位小写字母！"]); 
        }

        if($password!=$password2){
            return json_encode(["code"=>3,'msg'=>"两次密码不一致"]);
        }

        if(User::where('username',$username)->first()){
            return json_encode(["code"=>2,'msg'=>"用户已被注册"]);
        }
        
        User::create([
                'username' => "$username",
                'password' => User::setUserLoginPasswordAttr($password),
                'createtime'=> time()
            ]); 
        return json_encode(["code"=>1,'msg'=>"注册成功"]);
    }
}
