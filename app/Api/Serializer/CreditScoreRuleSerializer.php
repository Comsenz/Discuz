<?php


namespace App\Api\Serializer;

use App\Models\CreditScoreRule;
use Discuz\Api\Serializer\AbstractSerializer;

class CreditScoreRuleSerializer extends AbstractSerializer
{

    protected $type = 'credit_score_rule';

    /**
     * @inheritDoc
     * @param CreditScoreRule $model
     */
    protected function getDefaultAttributes($model)
    {
        return [
            'rule'              => $model->rule,
            'action'       => $model->action,
            'cycle_type'              => $model->cycle_type,
            'interval_time'              => $model->interval_time,
            'reward_num'          => $model->reward_num,
            'score'      => (int) $model->score,
        ];
    }
}
