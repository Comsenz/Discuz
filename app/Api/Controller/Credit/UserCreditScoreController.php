<?php


namespace App\Api\Controller\Credit;

use App\Api\Serializer\UserCreditScoreSerializer;
use App\Models\UserCreditScoreStatistics;
use Discuz\Api\Controller\AbstractResourceController;
use Psr\Http\Message\ServerRequestInterface;
use Tobscure\JsonApi\Document;

class UserCreditScoreController extends AbstractResourceController
{

    public $serializer = UserCreditScoreSerializer::class;

    /**
     * @inheritDoc
     */
    protected function data(ServerRequestInterface $request, Document $document)
    {
        //当前登录用户信息
        $actor = $request->getAttribute('actor');

        return UserCreditScoreStatistics::where('uid', 1)->first();
    }

}
