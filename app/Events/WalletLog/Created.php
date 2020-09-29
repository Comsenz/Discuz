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

namespace App\Events\WalletLog;

use App\Models\User;
use App\Models\UserWalletLog;

class Created
{
    /**
     * @var UserWalletLog
     */
    public $userWalletLog;

    /**
     * @var User
     */
    public $actor;

    /**
     * @var array
     */
    public $data;

    /**
     * Created constructor.
     *
     * @param UserWalletLog $userWalletLog
     * @param null $actor
     * @param array $data
     */
    public function __construct(UserWalletLog $userWalletLog, $actor = null, array $data = [])
    {
        $this->userWalletLog = $userWalletLog;
        $this->actor = $actor;
        $this->data = $data;
    }
}
