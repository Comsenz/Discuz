<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Commands\Users;


use App\Exceptions\FaceidException;
use App\Models\User;

use App\Validators\UserValidator;
use Illuminate\Support\Arr;
use App\Censor\Censor;


class RealUser
{

    /*
     * 姓名和身份证号一致
     */
    const NAME_ID_NUMBER_MATCH = 0;

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

    /**
     * @param UserValidator $validator
     * @param Censor $censor
     * @return User
     * @throws \Illuminate\Validation\ValidationException
     */
    public function handle(UserValidator $validator, Censor $censor)
    {
        $attributes = Arr::get($this->data, 'attributes', []);

        $this->actor->changeRealname(Arr::get($attributes, 'realname', ''));
        $this->actor->changeIdentity(Arr::get($attributes, 'identity', ''));

        $validator->valid($this->actor->getDirty());

        $res = $censor->checkReal($attributes['identity'], $attributes['realname']);
        //判断身份证信息与姓名是否符合
        if(Arr::get($res, 'Result', false) != self::NAME_ID_NUMBER_MATCH){
            throw new FaceidException($res['Description']);
        }else{
            $this->actor->save();
        }

        return $this->actor;
    }
}
