<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Api\Controller\Users;

use Discuz\Api\Controller\AbstractResourceController;
use Psr\Http\Message\ServerRequestInterface;
use Tobscure\JsonApi\Document;
use App\Api\Serializer\SessionSerializer;
use Illuminate\Support\Arr;
use Discuz\Contracts\Socialite\Factory;

class WelinkLoginController extends AbstractResourceController
{
    public $serializer = SessionSerializer::class;
    protected $socialite;
    public function __construct(Factory $socialite)
    {
        $this->socialite = $socialite;
    }
    /**
     * Get the data to be serialized and assigned to the response document.
     *
     * @param ServerRequestInterface $request
     * @param Document $document
     * @return mixed
     */
    protected function data(ServerRequestInterface $request, Document $document)
    {
       // $sessionId = Arr::get($request->getQueryParams(), 'sessionId', Str::random());
        $code = Arr::get($request->getQueryParams(), 'code');
        $request = $request->withAttribute('code', $code);
        $this->socialite->setRequest($request);
        $driver = $this->socialite->driver('welink');
        $user = $driver->user();
        dd($user);
        return ['sessionId' => '11'];
    }

}
