<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Commands\Users;


use App\Models\User;

use App\Validators\UserValidator;
use Discuz\Auth\AssertPermissionTrait;
use Illuminate\Support\Arr;
use Discuz\Foundation\EventsDispatchTrait;
use App\Censor\Censor;


class RealUser
{
    use AssertPermissionTrait;
    /**
     * @var
     */
    public $app;

    protected $data;

    protected $actor;

    public function __construct(array $data, User $actor)
    {
        $this->actor = $actor;
        $this->data = $data;
    }

    public function handle(UserValidator $validator, Censor $censor)
    {
        $attributes = Arr::get($this->data, 'attributes', []);
        if (isset($attributes['identity'])) {
            $this->actor->identity = $attributes['identity'];
        }

        if (isset($attributes['realname'])) {
            $this->actor->realname = $attributes['realname'];
        }
        $validator->valid($this->actor->getDirty());

        $res = $censor->checkReal($attributes['identity'], $attributes['realname']);
        //判断身份证信息与姓名是否符合
        if ($res['Result'] == 0) {

            $this->actor->changeRealname($attributes['realname']);
            $this->actor->changeIdentity($attributes['identity']);
            $this->actor->saveOrFail();

        }
        return $this->actor;
    }

}
