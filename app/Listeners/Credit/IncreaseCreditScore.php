<?php


namespace App\Listeners\Credit;


use App\Models\CreditScoreRule;
use App\Models\UserCreditScoreLog;
use App\Models\UserCreditScoreStatistics;
use App\Events\Credit\IncreaseCreditScore as Event;
use Illuminate\Database\ConnectionInterface;
use Illuminate\Support\Facades\Log;

/**
 * Class IncreaseCreditScore
 * @package App\Listeners\Credit
 */
class IncreaseCreditScore
{

    protected $action;
    protected $actor;
    public function __construct(Event $event)
    {
        $this->action = $event->action;
        $this->actor = $event->actor;
    }

    /**
     * @param ConnectionInterface $db
     */
    public function handle(ConnectionInterface $db)
    {
        try {
            $uid = $this->actor->id;
            //查询积分规则
            $rule = CreditScoreRule::where('action', $this->action)->first();
            if($rule == null) {
                throw new \Exception('action is not found');
            }
            $rid = $rule->id;
            $db->beginTransaction();
            //记录当前用户规则积分日志
            UserCreditScoreLog::create(['uid' => $uid, 'rid' =>$rid]);

            /** 积分统计表增加积分, 计算积分，并写入 */
            $static = UserCreditScoreStatistics::where('uid', $uid)->first();
            if($static == null) {
                $score = $rule->score;
                UserCreditScoreStatistics::create(['uid' => $uid, 'sum_score' => $score]);
            } else {
                $score = $static->sum_score + $rule->score;
                $static->sum_score = $score;
                $static->save();
            }
            $db->rollBack();

        } catch (\Exception $e) {
            Log::error('log', ['test' => 'test']);
        }
        return;
    }
}
