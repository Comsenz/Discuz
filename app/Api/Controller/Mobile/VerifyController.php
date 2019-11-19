<?php


namespace App\Api\Controller\Mobile;


use App\Api\Serializer\VerifyMobileSerializer;
use App\Commands\Sms\VerifyMobile;
use App\Exceptions\SmsCodeVerifyException;
use App\Repositories\MobileCodeRepository;
use Discuz\Api\Controller\AbstractResourceController;
use Illuminate\Contracts\Bus\Dispatcher;
use Illuminate\Support\Arr;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Tobscure\JsonApi\Document;

class VerifyController extends AbstractResourceController
{

    public $serializer = VerifyMobileSerializer::class;

    protected $mobileCodeRepository;
    protected $bus;

    public function __construct(MobileCodeRepository $mobileCodeRepository, Dispatcher $bus)
    {
        $this->mobileCodeRepository = $mobileCodeRepository;
        $this->bus = $bus;
    }

    /**
     * @param ServerRequestInterface $request
     * @param Document $document
     * @return array|mixed
     * @throws SmsCodeVerifyException
     */
    protected function data(ServerRequestInterface $request, Document $document)
    {
        $data = Arr::get($request->getParsedBody(), 'data.attributes');

        $mobile = Arr::get($data, 'mobile');
        $code = Arr::get($data, 'code');
        $type = Arr::get($data, 'type');

        $mobileCode = $this->mobileCodeRepository->getSmsCode($mobile, $type);
        if(!$mobileCode || $mobileCode->code !== $code) {
            throw new SmsCodeVerifyException();
        }

        //各种类型验证通过后，返回相关数据
        return $this->bus->dispatch(new VerifyMobile($this, $mobileCode, $request->getAttribute('actor')));
    }
}
