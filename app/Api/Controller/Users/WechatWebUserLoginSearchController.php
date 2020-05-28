<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Api\Controller\Users;

use App\Api\Serializer\TokenSerializer;
use App\Commands\Users\WebUserSearch;
use App\Exceptions\NoUserException;
use App\Exceptions\QrcodeImgException;
use Discuz\Api\Controller\AbstractResourceController;
use Illuminate\Contracts\Bus\Dispatcher;
use Illuminate\Support\Arr;
use Psr\Http\Message\ServerRequestInterface;
use Tobscure\JsonApi\Document;

class WechatWebUserLoginSearchController extends AbstractResourceController
{
    protected $bus;

    public $serializer = TokenSerializer::class;

    public function __construct(Dispatcher $bus)
    {
        $this->bus = $bus;
    }

    /**
     * @param ServerRequestInterface $request
     * @param Document $document
     * @return mixed
     * @throws NoUserException
     * @throws QrcodeImgException
     */
    protected function data(ServerRequestInterface $request, Document $document)
    {
        $data = $this->bus->dispatch(
            new WebUserSearch(Arr::get($request->getQueryParams(), 'scene_str'))
        );

        if (is_null($data['type'])) {
            throw new QrcodeImgException(trans('login.WebUser_img_payload_error'));
        } elseif ($data['type'] == 'bind') {
            throw (new NoUserException())->setToken($data['payload']);
        }

        return $data['payload'];
    }
}
