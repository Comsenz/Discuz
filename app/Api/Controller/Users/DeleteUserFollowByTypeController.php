<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Api\Controller\Users;

use App\Api\Serializer\InfoSerializer;
use App\Commands\Users\DeleteUserFollow;
use Discuz\Api\Controller\AbstractDeleteController;
use Illuminate\Contracts\Bus\Dispatcher;
use Illuminate\Support\Arr;
use Psr\Http\Message\ServerRequestInterface;

class DeleteUserFollowByTypeController extends AbstractDeleteController
{
    public $serializer = InfoSerializer::class;

    /**
     * @var Dispatcher
     */
    protected $bus;

    /**
     * @param Dispatcher $bus
     */
    public function __construct(Dispatcher $bus)
    {
        $this->bus = $bus;
    }

    /**
     * {@inheritdoc}
     */
    public function delete(ServerRequestInterface $request)
    {
        $actor = $request->getAttribute('actor');
        $to_user_id = 0;
        $from_user_id = 0;

        $type = (int) Arr::get($request->getQueryParams(), 'type');
        if ($type == 1) {
            //删除我的关注
            $to_user_id = (int) Arr::get($request->getQueryParams(), 'id');
        } elseif ($type == 2) {
            //删除我的粉丝
            $from_user_id = (int) Arr::get($request->getQueryParams(), 'id');
        }

        $data = collect();
        $data->push($this->bus->dispatch(
            new DeleteUserFollow($actor, $to_user_id, $from_user_id)
        ));
        return $data;
    }
}
