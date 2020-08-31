<?php


namespace App\Commands\Credit;


use App\Models\CreditScoreRule;
use App\Models\User;
use App\Models\UserCreditScoreLog;
use App\Models\UserCreditScoreStatistics;
use App\Repositories\CategoryRepository;
use App\Validators\CategoryValidator;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Database\ConnectionInterface;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class IncreaseCreditScore
{

    public $action;
    /**
     * The user performing the action.
     *
     * @var User
     */
    public $actor;

    /**
     * @param string $action
     * @param User $actor
     */
    public function __construct($action, User $actor)
    {
        $this->actor = $actor;
        $this->action = $action;
    }

    /**
     * @param Dispatcher $events
     * @param CategoryRepository $categories
     * @param CategoryValidator $validator
     * @return mixed
     * @throws ValidationException
     */
    public function handle(Dispatcher $events, ConnectionInterface $db)
    {
        try {
            $this->events = $events;
            $uid = $this->actor->id;
            //查询积分规则
            $rule = CreditScoreRule::where('action', $this->action)->first();
            if($rule == null) {
                throw new \Exception('action is not found');
            }
            $rid = $rule->id;
            $db->beginTransaction();
            //记录当前用户规则积分日志
            $data['uid'] = $uid;
            $data['rid'] = $rid;
            $log = UserCreditScoreLog::build(Arr::Only(
                $data,
                ['uid', 'rid']
            ));
            $log->save();

            /** 积分统计表增加积分, 计算积分，并写入 */
            $static = UserCreditScoreStatistics::where('uid', $this->actor)->first();
            if($static == null) {
                $score = $rule->score;
            } else {
                $score = $static->sum_score + $rule->score;
            }
            UserCreditScoreStatistics::updateOrCreate(['uid' => $uid, 'sum_score' => $score], ['uid' => $uid]);
            $db->rollBack();

        } catch (\Exception $e) {
            Log::error('log', ['test' => 'test']);
        }
        return;
    }

}
