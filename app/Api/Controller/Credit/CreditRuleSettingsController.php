<?php


namespace App\Api\Controller\Credit;

use App\Repositories\CreditScoreRuleRepository;
use Discuz\Auth\AssertPermissionTrait;
use Discuz\Http\DiscuzResponseFactory;
use Illuminate\Contracts\Bus\Dispatcher;
use Illuminate\Support\Arr;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class CreditRuleSettingsController implements RequestHandlerInterface
{
    use AssertPermissionTrait;

    protected $repository;

    /**
     * @param Dispatcher $bus
     */
    public function __construct(CreditScoreRuleRepository $repository)
    {
        $this->repository = $repository;
    }


    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        //管理员权限检验
        $this->assertAdmin($request->getAttribute('actor'));
        $params = $request->getParsedBody();

        $cycleType = intval(Arr::get($params, 'cycle_type'));
        $intervalTime = intval(Arr::get($params, 'interval_time'));
        $rewardNum = intval(Arr::get($params, 'reward_num'));
        $score = intval(Arr::get($params, 'score'));
        $id = intval(Arr::get($params, 'id'));

        $rule = $this->repository->findOrFail($id);
        $rule->cycle_type = $cycleType;

        if(in_array($cycleType, [3,4])) {
            $rule->interval_time = $intervalTime;
        }
        $rule->reward_num = $rewardNum;
        $rule->score = $score;
        $rule->save();

        return DiscuzResponseFactory::EmptyResponse(204);
    }


}
