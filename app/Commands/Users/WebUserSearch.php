<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Commands\Users;

use App\Exceptions\NoUserException;
use App\Exceptions\QrcodeImgException;
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
        $session = SessionToken::get($this->scene_str);

        $data = [
            'type' => null,
            'payload' => null
        ];;
        if(!is_null($session)) {
            if($session->user_id) {
                $user = User::find($session->user_id);
                $response = $this->bus->dispatch(
                    new GenJwtToken(Arr::only($user->toArray(), 'username'))
                );

                $data['type'] = 'login';
                $data['payload'] = json_decode($response->getBody());
            } else {
                $data['type'] = 'bind';
                $data['payload'] = $session;
            }
        }

        return $data;
    }
}
