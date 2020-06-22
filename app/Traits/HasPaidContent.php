<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Traits;

use App\BlockEditor\Formater\PaidCheck;
use App\Models\Attachment;
use App\Models\Post;
use App\Models\Order;
use App\Models\Thread;
use App\Models\ThreadVideo;
use App\Models\User;
use Illuminate\Support\Str;
use App\BlockEditor\Formater\PostFormater;

/**
 * @package App\Traits
 */
trait HasPaidContent
{
    /**
     * @var array
     */
    protected $threads = [];

    /**
     * @var array
     */
    protected $orders = [];

    /**
     * @param Post|Attachment|ThreadVideo $model
     */
    public function paidContent($model)
    {
        /** @var User $actor */
        $actor = $this->actor;

        // 作者本人 或 管理员 不处理（新增类型时请保证 $model->user_id 存在）
        if ($actor->id === $model->user_id || $actor->isAdmin()) {
            $content = $model->getAttribute('content');
            $model->content = json_decode($content, true);
           return;
        }

        Thread::setStateUser($actor);

        if ($model instanceof Post) {
            $model->content = PostFormater::pure($model);
        } elseif ($model instanceof Attachment) {
            $model = PostFormater::checkAttachment($model);
            $status = PaidCheck::idPaid($model->type_id, $model->pay_blocks);
            if ($status) {
                $model->setAttribute('paid', true);
            } else {
                $this->blurImage($model);
                $model->setAttribute('paid', false);
            }
        } elseif ($model instanceof ThreadVideo) {

            $model = PostFormater::checkVodeo($model);
            $status = PaidCheck::idPaid($model->post_id, $model->pay_blocks);
            if ($status) {
                $model->setAttribute('paid', true);
            } else {
                $model->file_id = '';
                $model->media_url = '';
                $model->setAttribute('paid', false);
            }
        }
    }


    /**
     * 付费块包含图片为付费时返回模糊图片
     *
     * @param Attachment $attachment
     */
    public function blurImage(Attachment $attachment)
    {
        if (
            $attachment->type === Attachment::TYPE_OF_IMAGE
        ) {

            $attachment->setAttribute('blur', true);

            $parts = explode('.', $attachment->attachment);
            $parts[0] = md5($parts[0]);

            $attachment->attachment = implode('_blur.', $parts);
        }
    }

}
