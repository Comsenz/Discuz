<?php


namespace App\Listeners\User;


use App\Events\Users\UserVerify;
use App\Repositories\MobileCodeRepository;

class MobileBind
{
    protected $mobileCode;

    public function __construct(MobileCodeRepository $mobileCode)
    {
        $this->mobileCode = $mobileCode;
    }

    public function handle(UserVerify $events)
    {
        if(isset($events->data['mobile'])) {
            $mobileCode = $this->mobileCode->getSmsCode($events->data['mobile'], 'bind', 1);

            if($mobileCode) {
                $events->user->mobile = $events->data['mobile'];
                $events->user->save();
            }
        }
    }
}
