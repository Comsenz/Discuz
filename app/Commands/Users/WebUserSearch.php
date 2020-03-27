<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Commands\Users;
use App\Api\Serializer\TokenSerializer;
use App\Models\SessionToken;
use App\Models\User;
use App\Repositories\UserRepository;
use Discuz\Http\DiscuzResponseFactory;
use Illuminate\Contracts\Bus\Dispatcher;
use Illuminate\Support\Arr;

class WebUserSearch
{
    /**
     * 二维码参数
     * @var string
     */
    public $scene_str;
    protected $bus;
    public $serializer = TokenSerializer::class;
    public $users;
    public function __construct(string $scene_str)
    {
        $this->scene_str = $scene_str;
    }

    public function handle(Dispatcher $bus,UserRepository $users)
    {
        $this->bus = $bus;
        $this->users = $users;
        $session = SessionToken::get($this->scene_str);
        $user_id = Arr::get($session, 'user_id');
        $user = User::where('id',$user_id)->first();
        if(isset($user->id) && $user->id != null){
            //老用户返回用户登录
            $data = [
                'token' => $session->token,
                'user_id' => $user_id
            ];
            return DiscuzResponseFactory::JsonResponse($data);

        }else{
            //新用户返回绑定页面
            $data = [
                'code' =>200,
                'data' => $session
            ];
            return DiscuzResponseFactory::JsonResponse($data);
        }
    }
}
