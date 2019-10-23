<?php
declare(strict_types=1);

namespace App\Api\Controller\Users;


use App\Api\Serializer\UserPorfileSerializer;
use App\Models\User;
use App\Models\UserProfile;
use App\Models\UserWechat;
use Discuz\Api\Controller\AbstractResourceController;
use Psr\Http\Message\ServerRequestInterface;
use Tobscure\JsonApi\Document;
use Illuminate\Support\Arr;
use App\Validators\UserValidator;
use Discuz\Contracts\Search\Searcher;
use Discuz\Foundation\Application;
use Illuminate\Contracts\Bus\Dispatcher as BusDispatcher;


class UpdateUserProfileController extends AbstractResourceController
{
    public $serializer = UserPorfileSerializer::class;

    protected $userValidator;

    public function __construct(Application $app, BusDispatcher $bus, Searcher $searcher,UserValidator $userValidator){
        $this->userValidator = $userValidator;
        return parent::__construct( $app,  $bus, $searcher);
    }

    /**
     * @param ServerRequestInterface $request
     * @param Document $document
     */
    public function data(ServerRequestInterface $request, Document $document)
    {
        // dd($this->serialize);
        // TODO: Implement data() method.
        $id = Arr::get($request->getQueryParams(), 'id');
        $data = $request->getParsedBody();
        //验证    密码格式
        // dd($data);
        //验证权限
        if($data['user']['username']!=""){
            $this->userValidator->valid(['updatename' => Arr::get($data['user'], 'username')]);
        }
        if($data['user']!=""){
            $objuser = User::findOrFail($id);
            foreach($data['user'] as $k=>$v){
                if($k=="password"){
                    //权限
                    $objuser->$k = User::setUserPasswordAttr(Arr::get($data['user'], 'password'));
                }else{
                    $objuser->$k = $v;
                }
            }
            $user=$objuser->save();
        }
        if($data['profile']!=""){
            $profile = UserProfile::where('user_id',$id)->first();
            if($profile){
                $profile_id=$profile->id;
            }else{
                // dd($data['profile']);
                $userprofile=UserProfile::create(["user_id"=>$id]);
                $profile_id=$userprofile->id;
            }
            $objprofile = UserProfile::findOrFail($profile_id);
            foreach($data['profile'] as $k=>$v){
                if($k=="icon" && $v==1){
                    if(!empty($userprofile->icon)){
                        $objprofile->$k = "";
                    }
                }else{
                    $objprofile->$k = $v;
                } 
            }
            $profile=$objprofile->save();
        }
        if($data['is_wx']==1){
            $wx=UserWechat::where('id',$id)->delete();
        }
        $user= User::where('users.id',$id)
        ->leftjoin('user_wechats', 'user_wechats.id', '=', 'users.id')
        ->leftjoin('user_profiles', 'user_profiles.user_id', '=', 'users.id')
        ->select('users.id as id',"username","adminid","users.unionid","mobile","users.createtime as createtime","users.login_ip","nickname","user_profiles.sex","icon")
        ->first();
        // dd($user);
        return $user;
        
         
    }
}