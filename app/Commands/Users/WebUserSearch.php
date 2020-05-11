<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Commands\Users;

use App\Events\Users\Logind;
use App\Models\SessionToken;
use App\Models\User;
use App\Repositories\UserRepository;
use Illuminate\Contracts\Bus\Dispatcher;
use Illuminate\Support\Arr;
use Illuminate\Contracts\Events\Dispatcher as Events;

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

    public function handle(Dispatcher $bus, UserRepository $users, Events $events)
    {
        $this->bus = $bus;
        $this->users = $users;
        $session = SessionToken::get($this->scene_str, 'wechat');

        $data = [
            'type' => null,
            'payload' => null
        ];;
        if(!is_null($session)) {
            if($session->user_id) {
                $response = $this->bus->dispatch(
                    new GenJwtToken(Arr::only($session->user->toArray(), 'username'))
                );

                if($response->getStatusCode() === 200) {
                    $events->dispatch(new Logind($session->user));
                }
                $data['type'] = 'login';
                $data['payload'] = json_decode($response->getBody());
            } elseif($session->payload) {
                $data['type'] = 'bind';
                $data['payload'] = $session;
            }
        }

        return $data;
    }
}
