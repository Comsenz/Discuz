<?php
declare(strict_types=1);

/**
 *      Discuz & Tencent Cloud
 *      This is NOT a freeware, use is subject to license terms
 *
 *      Id: ListClassifyControllerer.php 28830 2019-09-25 11:13 chenkeke $
 */

namespace App\Api\Controller\Classify;

use App\Api\Serializer\ClassifySerializer;
use Discuz\Api\Controller\AbstractListController;
use App\Commands\Circle\CreateThread;
use Psr\Http\Message\ServerRequestInterface;
use Tobscure\JsonApi\Document;

class ListClassifyController extends AbstractListController
{
    public $serializer = ClassifySerializer::class;

    public function data(ServerRequestInterface $request, Document $document)
    {
        // TODO: Implement data() method.
        $res = $this->bus->dispatch(
            new CreateThread('aaa','bb',array('cc'))
        );
        dd($res);
        return $res;
    }
}