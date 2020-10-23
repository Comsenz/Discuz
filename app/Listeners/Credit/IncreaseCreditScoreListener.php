<?php


namespace App\Listeners\Credit;


use App\Events\Credit\IncreaseCreditScore;
use App\Models\CreditScoreRule;
use App\Models\UserCreditScoreLog;
use App\Models\UserCreditScoreStatistics;
use Carbon\Carbon;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Database\ConnectionInterface;
use Psr\Log\LoggerInterface;

class IncreaseCreditScoreListener
{
    //加积分
    CONST PLUS = 1;
    //减积分
    CONST MINUS = 2;


    protected $bus;

    protected $db;

    protected $logger;

    public function __construct(Dispatcher $bus, ConnectionInterface $db, LoggerInterface $logger)
    {
        $this->bus = $bus;
        $this->db = $db;
        $this->logger = $logger;
    }

    /**
     * @param ConnectionInterface $db
     */
    public function handle(IncreaseCreditScore $event)
    {
        try {
            $uid = $event->actor->id;
            //查询积分规则
            $rule = CreditScoreRule::where('action', $event->action)->first();
            if($rule == null) {
                throw new \Exception('action is not found');
            }
            $rid = $rule->id;
            $type = self::PLUS;
            //不为true 则需要减积分
            if(!$event->isIncrease) {
                $type = self::MINUS;
            }

            if($type == self::PLUS) {
                //是否可以加积分
                $isCan = $this->isCanIncreaseCreditScore($uid, $rid, $rule);
                if(!$isCan) {
                    return;
                }
            }

            $data['uid'] = $uid;
            $data['rid'] = $rid;
            $data['cycle_type'] = $rule->cycle_type;
            $data['interval_time'] = $rule->interval_time;
            $data['reward_num'] = $rule->reward_num;
            $data['score'] = $rule->score;
            $data['type'] = $type;

            //记录当前用户规则积分日志
            UserCreditScoreLog::create($data);
            /** 积分统计表增加积分, 计算积分，并写入 */
            $static = UserCreditScoreStatistics::where('uid', $uid)->first();
            if($static == null) {
                //统计表中无数据，此时删除发帖统计积分等于0
                $score = $type == self::MINUS ? 0 : $rule->score;
                UserCreditScoreStatistics::create(['uid' => $uid, 'sum_score' => $score]);
            } else {
                $score = $static->sum_score + $rule->score;
                if($type == self::MINUS) {
                    //积分逻辑，不能为负数
                    $score = $static->sum_score - $rule->score;
                    if($score < 0) {
                        $score = 0;
                    }
                }

                $static->sum_score = $score;
                $static->save();
            }

        } catch (\Exception $e) {
            $this->logger->error("increase error:", ['msg'=>$e->getMessage()]);
        }
        return;
    }


    /**
     * 判断是否可以加积分
     * @param $uid
     * @param $rid
     * @param CreditScoreRule $rule
     * @return bool
     */
    protected function isCanIncreaseCreditScore($uid, $rid, CreditScoreRule $rule)
    {
        $isCan = false;
        $last = UserCreditScoreLog::query()
            ->where('uid', $uid)
            ->where('rid', $rid)
            ->latest()
            ->first();

        //未查到最后一次加积分记录，则为首次加
        if($last == null) {
            return true;
        }
        //管理员改了规则，重新计算
        if($last->cycle_type != $rule->cycle_type) {
            return  true;
        }
        switch ($rule->cycle_type) {
            //不限，随便加,直到达到奖励次数上限(如果有设置限制次数)
            case 0:
                if($rule->reward_num == 0 || UserCreditScoreLog::query()->where('uid', $uid)->where('rid', $rid)->count() <= $rule->reward_num) {
                    $isCan = true;
                }
                break;
            //一次，不限制奖励次数，一直加
            case  1:
                $isCan = true;
                break;
            //每天
            case 2:
                $tomorrow = Carbon::tomorrow()->toDateTime();
                $today = Carbon::today()->toDateTime();
                //查询当天当前用户当前规则下已经奖励了几次
                $count = UserCreditScoreLog::query()
                    ->where('uid', $uid)
                    ->where('rid', $rid)
                    ->where('created_at', '<', $tomorrow)
                    ->where('created_at', '>', $today)
                    ->count();
                //当天奖励次数小于设置次数可以再加
                if($count < $rule->reward_num) {
                    $isCan = true;
                    break;
                }
                break;
            //周期，3小时时间间隔,4分钟时间间隔
            default :
                //间隔时间，单位秒
                $intervalTime = $rule->interval_time;
                //最后一次记录的加记录时间戳
                $lastTime = Carbon::parse($last->created_at)->getTimestamp();
                //当前时间
                $now = Carbon::now()->getTimestamp();
                //已经过了一轮间隔，重新开始加积分
                if($now > $lastTime + $intervalTime) {
                    $isCan = true;
                    break;
                }
                //查询当前用户当前规则下已经奖励了几次
                $count = UserCreditScoreLog::query()
                    ->where('uid', $uid)
                    ->where('rid', $rid)
                    ->where('created_at', '>', Carbon::parse($now-$intervalTime)->toDateTime())
                    ->where('created_at', '<', Carbon::parse($now)->toDateTime())
                    ->count();
                //当天奖励次数小于设置次数可以再加
                if($count < $rule->reward_num) {
                    $isCan = true;
                    break;
                }
                break;


        }
        return $isCan;
    }




}
