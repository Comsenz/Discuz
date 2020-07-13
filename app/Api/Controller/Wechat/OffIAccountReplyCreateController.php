<?php


namespace App\Api\Controller\Wechat;

use App\Api\Serializer\OffIAccountReplySerializer;
use App\Models\WechatOffiaccountReply;
use Discuz\Api\Controller\AbstractCreateController;
use Discuz\Auth\AssertPermissionTrait;
use Discuz\Auth\Exception\PermissionDeniedException;
use Illuminate\Contracts\Bus\Dispatcher;
use Illuminate\Support\Arr;
use Psr\Http\Message\ServerRequestInterface;
use Tobscure\JsonApi\Document;
use Illuminate\Validation\Factory as Validator;
use Illuminate\Validation\ValidationException;

class OffIAccountReplyCreateController extends AbstractCreateController
{
    use AssertPermissionTrait;

    /**
     * {@inheritdoc}
     */
    public $serializer = OffIAccountReplySerializer::class;

    /**
     * @var Dispatcher
     */
    protected $bus;

    /**
     * @var Validator
     */
    protected $validator;

    /**
     * @param Dispatcher $bus
     * @param Validator $validator
     */
    public function __construct(Dispatcher $bus, Validator $validator)
    {
        $this->bus = $bus;
        $this->validator = $validator;
    }

    /**
     * @param ServerRequestInterface $request
     * @param Document $document
     * @return mixed|void
     * @throws ValidationException
     * @throws PermissionDeniedException
     */
    protected function data(ServerRequestInterface $request, Document $document)
    {
        $this->assertAdmin($request->getAttribute('actor'));

        $type = Arr::get($this->extractFilter($request), 'type');
        $attributes = Arr::get($request->getParsedBody(), 'data.attributes');

        /**
         * 验证参数
         */
        $build = array_merge($attributes, ['type' => $type]);
        $validatorInfo = $this->validator->make($build, [
            'keyword' => 'required',
            'type' => [
                'in:0,1,2',
                // 当0被关注回复1消息回复 都只允许有一条数据
                function ($attribute, $value, $fail) {
                    if ($value != 2) {
                        // exists data
                        if ($bool = WechatOffiaccountReply::where('type', $value)->exists()) {
                            $fail(trans('wechat.wechat_only_one_message_fail'));
                        }
                    }
                }
            ],
        ]);

        if ($validatorInfo->fails()) {
            throw new ValidationException($validatorInfo);
        }

        $reply = WechatOffiaccountReply::build($build);

        $reply->save();

        return $reply;
    }
}
