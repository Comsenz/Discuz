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

namespace App\Api\Controller\Ucenter;

use App\Api\Serializer\TokenSerializer;
use App\Commands\Users\GenJwtToken;
use App\Exceptions\NoUserException;
use App\Models\SessionToken;
use App\Models\UserUcenter;
use App\Ucenter\Client;
use Discuz\Api\Controller\AbstractResourceController;
use Exception;
use Illuminate\Contracts\Bus\Dispatcher;
use Illuminate\Support\Arr;
use Psr\Http\Message\ServerRequestInterface;
use Tobscure\JsonApi\Document;

class LoginController extends AbstractResourceController
{
    protected $uc;

    protected $bus;

    protected $errors = [
        '-999' => 'uc_connect_error',
        '-1' => 'uc_user_check_username_failed',
        '-2' => 'uc_user_username_badword',
        '-3' => 'uc_user_username_exists',
        '-4' => 'uc_user_email_format_illegal',
        '-5' => 'uc_user_email_access_illegal',
        '-6' => 'uc_user_email_exists',
    ];

    public $serializer = TokenSerializer::class;

    public function __construct(Client $uc, Dispatcher $bus)
    {
        $this->uc = $uc;
        $this->bus = $bus;
    }

    /**
     * @inheritDoc
     * @throws Exception
     */
    protected function data(ServerRequestInterface $request, Document $document)
    {
        $this->uc->setRequest($request);
        $attributes = Arr::get($request->getParsedBody(), 'data.attributes');
        $username = Arr::get($attributes, 'username');
        $password = Arr::get($attributes, 'password');
        $questionid = Arr::get($attributes, 'questionid');
        $answer = Arr::get($attributes, 'answer');
        $ucResult = $this->uc->uc_user_login($username, $password, 0, 1, $questionid, $answer);

        if($ucResult && $ucResult[0] > 0) {
            $uc_user = UserUcenter::where('ucenter_id', $ucResult[0])->first();

            if(!is_null($uc_user)) {
                if(!is_null($uc_user->user)) {
                    $response = $this->bus->dispatch(
                        new GenJwtToken(['username' => $uc_user->user->username])
                    );

                    return json_decode($response->getBody());
                } else {
                    throw new Exception('no_user');
                }
            }
            $token = SessionToken::generate('ucenter', $ucResult);
            $token->save();
            $noUserException = new NoUserException();
            $noUserException->setToken($token);
            $noUserException->setUser(['uid' => $ucResult[0], 'username' => $ucResult[1]]);
            throw $noUserException;

        }

        $ucResult = $ucResult ? $ucResult : [-999];

        throw new Exception($this->errors[$ucResult[0]]);
    }
}
