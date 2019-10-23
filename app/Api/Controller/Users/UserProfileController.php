<?php
declare(strict_types=1);

namespace App\Api\Controller\Users;


use App\Api\Serializer\UserPorfileSerializer;
use App\Models\User;
use App\Models\CircleUser;
use Discuz\Api\Controller\AbstractResourceController;
use Psr\Http\Message\ServerRequestInterface;
use Tobscure\JsonApi\Document;
use Illuminate\Support\Arr;

class UserProfileController extends AbstractResourceController
{
    public $serializer = UserPorfileSerializer::class;

    public function data(ServerRequestInterface $request, Document $document)
    {
        // TODO: Implement data() method.
       
        $id = Arr::get($request->getQueryParams(), 'id');

        $user= User::where('users.id',$id)
        ->leftjoin('user_wechats', 'user_wechats.id', '=', 'users.id')
        ->leftjoin('user_profiles', 'user_profiles.user_id', '=', 'users.id')
        ->select('users.id as id',"username","adminid","users.unionid","mobile","users.createtime as createtime","users.login_ip","nickname","user_profiles.sex","icon")
        ->first();
        // dd($user);
        return $user;
    }
}
