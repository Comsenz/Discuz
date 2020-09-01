<?php


namespace App\Api\Serializer;


use App\Models\UserCreditScoreStatistics;
use Discuz\Api\Serializer\AbstractSerializer;

class UserCreditScoreSerializer extends AbstractSerializer
{

    protected $type = 'user_credit_score_stat';

    /**
     * @inheritDoc
     * @param UserCreditScoreStatistics $model
     */
    protected function getDefaultAttributes($model)
    {
        return [
            'uid'              => $model->uid,
            'score'       => $model->sum_score,
        ];
    }
}
