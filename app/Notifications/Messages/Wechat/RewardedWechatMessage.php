<?php

namespace App\Notifications\Messages\Wechat;

use App\Models\Order;
use Carbon\Carbon;
use Discuz\Notifications\Messages\SimpleMessage;
use Illuminate\Contracts\Routing\UrlGenerator;
use Illuminate\Support\Arr;

/**
 * 内容支付通知 - 微信
 *
 * @package App\Notifications\Messages\Wechat
 */
class RewardedWechatMessage extends SimpleMessage
{
    public $tplId = 31;

    protected $model;

    protected $actor;

    protected $data;

    /**
     * @var UrlGenerator
     */
    protected $url;

    public function __construct(UrlGenerator $url)
    {
        $this->url = $url;
    }

    public function setData(...$parameters)
    {
        [$firstData, $actor, $model, $data] = $parameters;
        // set parent tpl data
        $this->firstData = $firstData;

        $this->actor = $actor;
        $this->model = $model;
        $this->data = $data;

        $this->template();
    }

    public function template()
    {
        $build =  [
            'title' => $this->getTitle(),
            'content' => $this->getContent($this->data),
            'raw' => Arr::get($this->data, 'raw'),
        ];

        Arr::set($build, 'raw.tpl_id', $this->firstData->id);

        return $build;
    }

    protected function titleReplaceVars()
    {
        return [];
    }

    public function contentReplaceVars($data)
    {
        $message = Arr::get($data, 'message', '');
        $threadId = Arr::get($data, 'raw.thread_id', 0);
        $actualAmount = Arr::get($data, 'raw.actual_amount', 0); // 实际金额

        // 获取支付类型
        $orderName = Order::enumType(Arr::get($data, 'raw.type', 0), function ($args) {
            return $args['value'];
        });

        $actorName = Arr::get($data, 'raw.actor_username', '');  // 发送人姓名

        // 主题ID为空时跳转到首页
        if (empty($threadId)) {
            $threadUrl = $this->url->to('');
        } else {
            $threadUrl = $this->url->to('/topic/index?id=' . $threadId);
        }

        return [
            $actorName,
            $actualAmount,
            $this->strWords($message),
            $orderName, // 1：注册，2：打赏，3：付费主题，4：付费用户组
            Carbon::now()->toDateTimeString(),
            $threadUrl,
        ];
    }

}
