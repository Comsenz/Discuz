<?php
declare(strict_types=1);

namespace App\Api\Controller\Users;


use App\Api\Serializer\UserPorfileSerializer;
use Discuz\Api\Controller\AbstractResourceController;
use Psr\Http\Message\ServerRequestInterface;
use Tobscure\JsonApi\Document;
use Illuminate\Support\Arr;
use App\Commands\UserProfile\UpdateUserProfile;
use App\Commands\UserProfile\UserProfile;

class UpdateUserProfileController extends AbstractResourceController
{
    public $serializer = UserPorfileSerializer::class;


    /**
     * @param ServerRequestInterface $request
     * @param Document $document
     */
    public function data(ServerRequestInterface $request, Document $document)
    {
        // 获取当前用户
        $actor = $request->getAttribute('actor');
        $id = Arr::get($request->getQueryParams(), 'id');
        // 获取请求的参数
        $inputs = $request->getParsedBody();
        // 获取请求的IP
        $ipAddress = Arr::get($request->getServerParams(), 'REMOTE_ADDR', '127.0.0.1');
        $data = $this->bus->dispatch(
            new UpdateUserProfile($id,$actor, $inputs->toArray(), $ipAddress)
        );
        $data = $this->bus->dispatch(
            new UserProfile($id, $actor)
        );

        // dd($this->serialize);
        // TODO: Implement data() method.
//        $id = Arr::get($request->getQueryParams(), 'id');
//        $data = $request->getParsedBody();
//        //验证    密码格式
//        // dd($data);
//        //验证权限
//        if($data['user']['username']!=""){
//            $this->userValidator->valid(['updatename' => Arr::get($data['user'], 'username')]);
//        }
//        if($data['user']['password']!=""){
//            $this->userValidator->valid(['password' => Arr::get($data['user'], 'password')]);
//        }
//        if($data['user']!=""){
//            $objuser = User::findOrFail($id);
//            foreach($data['user'] as $k=>$v){
//                if($k=="password"){
//                    //权限
//                    $objuser->$k = User::setUserPasswordAttr(Arr::get($data['user'], 'password'));
//                }else{
//                    $objuser->$k = $v;
//                }
//            }
//            $user=$objuser->save();
//        }
//        if($data['profile']!=""){
//            $profile = UserProfile::where('user_id',$id)->first();
//            if($profile){
//                $profile_id=$profile->id;
//            }else{
//                // dd($data['profile']);
//                $userprofile=UserProfile::create(["user_id"=>$id]);
//                $profile_id=$userprofile->id;
//            }
//            $objprofile = UserProfile::findOrFail($profile_id);
//            foreach($data['profile'] as $k=>$v){
//                if($k=="icon" && $v==1){
//                    if(!empty($userprofile->icon)){
//                        $objprofile->$k = "";
//                    }
//                }else{
//                    $objprofile->$k = $v;
//                }
//            }
//            $profile=$objprofile->save();
//        }
//        if($data['is_wx']==1){
//            $wx=UserWechat::where('id',$id)->delete();
//        }
//        $user= User::where('users.id',$id)
//        ->leftjoin('user_wechats', 'user_wechats.id', '=', 'users.id')
//        ->leftjoin('user_profiles', 'user_profiles.user_id', '=', 'users.id')
//        ->select('users.id as id',"username","adminid","users.unionid","mobile","users.createtime as createtime","users.login_ip","nickname","user_profiles.sex","icon")
//        ->first();
//        // dd($user);
        return $data;
        
         
    }
}