<?php
/**
 *      Discuz & Tencent Cloud
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: QueryOrderController.php xxx 2019-10-18 00:00:00 zhouzhou $
 */

namespace App\Api\Controller\Trade;

use Discuz\Api\Controller\AbstractResourceController;
use App\Api\Serializer\PayOrderSerializer;
use Psr\Http\Message\ServerRequestInterface;
use Tobscure\JsonApi\Document;
use App\Commands\Trade\QueryOrder;
use Illuminate\Support\Arr;

class QueryOrderController extends AbstractResourceController
{
    /**
     * {@inheritdoc}
     */
    public $serializer = QueryOrderSerializer::class;

    /**
     * {@inheritdoc}
     */
    public function data(ServerRequestInterface $request, Document $document)
    {
    	// TODO: User $actor 用户模型
        // $actor = $request->getAttribute('actor');
        $actor = new \stdClass();
        $actor->id = 1;

        //订单编号
    	$order_sn = Arr::get($request->getQueryParams(), 'order_sn');

        return $this->bus->dispatch(
            new QueryOrder($actor, $request->getParsedBody())
        );
    }
}