<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Commands\Users;

use App\Exceptions\NoUserException;
use App\Models\SessionToken;
use App\Models\User;
use App\Repositories\UserRepository;
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

    public $users;

    public function __construct(string $scene_str)
    {
        $this->scene_str = $scene_str;
    }

    public function handle(Dispatcher $bus, UserRepository $users)
    {
        $this->bus = $bus;
        $this->users = $users;
        $session = SessionToken::where('token',$this->scene_str)->first();
        $user_id = Arr::get($session, 'user_id');
        $user = User::where('id', $user_id)->first();
        if (isset($user->id) && $user->id != null) {
            $response = $this->bus->dispatch(
                new GenJwtToken($user->username)
            );
            return json_decode($response->getBody());
        } else {
            throw (new NoUserException())->setToken($session);
        }
    }
}
