<?php

/**
 * Copyright (C) 2020 Tencent Cloud.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
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
        if (Arr::get($res, 'Result', false) != self::NAME_ID_NUMBER_MATCH) {
            throw new FaceidException($res['Description']);
        }
        $this->actor->save();

        return $this->actor;
    }
}
