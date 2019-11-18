<?php


namespace App\Repositories;


use App\Models\MobileCode;

class MobileCodeRepository
{

    public function query() {
        return MobileCode::query();
    }


    public function getSmsCode($mobile, $type, $state = 0) {
        return $this->query()->where([
            [compact('mobile')],
            [compact('type')],
            [compact('state')]
        ])->first();
    }
}
