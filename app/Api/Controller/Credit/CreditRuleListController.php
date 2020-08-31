<?php


namespace App\Api\Controller\Credit;


use App\Api\Serializer\CreditScoreRuleSerializer;
use App\Models\CreditScoreRule;
use Discuz\Api\Controller\AbstractListController;
use Psr\Http\Message\ServerRequestInterface;
use Tobscure\JsonApi\Document;

class CreditRuleListController extends AbstractListController
{
    public $serializer = CreditScoreRuleSerializer::class;

    protected function data(ServerRequestInterface $request, Document $document)
    {
        return CreditScoreRule::all();
    }
}
