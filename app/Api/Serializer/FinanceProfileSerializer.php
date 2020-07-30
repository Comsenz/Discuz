<?php

/**
 * Copyright (C) 2020 Tencent Cloud.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

namespace App\Api\Serializer;

use Discuz\Api\Serializer\AbstractSerializer;
use Illuminate\Support\Arr;

class FinanceProfileSerializer extends AbstractSerializer
{
    protected $type = 'finance_statistic';

    /**
     * Get the default set of serialized attributes for a model.
     *
     * @param object|array $model
     * @return array
     */
    public function getDefaultAttributes($model)
    {
        return [
            'totalIncome'            => number_format(Arr::get($model, 'totalIncome', 0.00), 2),
            'totalWithdrawal'        => number_format(Arr::get($model, 'totalWithdrawal', 0.00), 2),
            'totalWallet'            => number_format(Arr::get($model, 'totalWallet', 0.00), 2),
            'totalProfit'            => number_format(Arr::get($model, 'totalProfit', 0.00), 2),
            'withdrawalProfit'       => number_format(Arr::get($model, 'withdrawalProfit', 0.00), 2),
            'orderRoyalty'           => number_format(Arr::get($model, 'orderRoyalty', 0.00), 2),
            'totalRegisterProfit'    => number_format(Arr::get($model, 'totalRegisterProfit', 0.00), 2),
            'orderCount'             => Arr::get($model, 'orderCount', 0),
        ];
    }

    public function getId($model)
    {
        return 1;
    }
}
