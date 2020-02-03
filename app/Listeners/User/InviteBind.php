<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Listeners\User;

use App\Events\Users\Registered;
use App\Models\Invite;
use App\Repositories\InviteRepository;
use Carbon\Carbon;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Support\Arr;

class InviteBind
{
    protected $InviteRepository;

    public function __construct(InviteRepository $InviteRepository)
    {
        $this->InviteRepository = $InviteRepository;
    }

    public function handle(Registered $event)
    {
        $code = Arr::get($event->data, 'code', '');

        if ($code) {
            $len = mb_strlen($code, 'utf-8');

            if ($len == 32) {
                //用户吗32位长度为管理员邀请
                $invite = $this->InviteRepository->verifyCode($code);

                if ($invite) {
                    $invite->to_user_id = $event->user->id;
                    $invite->save();
                }
            } else {
                $encrypter = app('encrypter');

                try {
                    $user_id = $encrypter->decryptString($code);
                } catch (DecryptException $e) {
                    throw new DecryptException();
                }
                //生成记录
                Invite::insert([
                    'group_id' => 0,
                    'code' => $code,
                    'user_id' => $user_id,
                    'to_user_id' => $event->user->id,
                    'created_at' => Carbon::now()->toDate(),
                    'updated_at' => Carbon::now()->toDate(),
                ]);
            }
        }
    }
}
