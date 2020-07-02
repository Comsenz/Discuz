<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Validators;

use Discuz\Foundation\AbstractValidator;

class OffIAccountAssetUpdateValidator extends AbstractValidator
{
    /**
     * @return array
     */
    protected function getRules()
    {
        return [
            'title' => 'required',  // 标题
            'thumb_media_id' => 'required', // 图文消息的封面图片素材id（必须是永久 media_ID）
            'content' => 'required', // 图文消息的具体内容，支持HTML标签，必须少于2万字符，小于1M，且此处会去除JS
            'authod' => '', // 作者(微信文档起名错误)
            'digest' => '', // 图文消息的摘要，仅有单图文消息才有摘要，多图文此处为空
            'show_cover_pic' => '', // 是否显示封面，0为false，即不显示，1为true，即显示
            'show_cover' => 'in:0,1', // 官方未提及该字段，字面意思 是否显示封面
            'content_source_url' => '', // 图文消息的原文地址，即点击“阅读原文”后的URL
            'need_open_comment' => 'in:0,1', // 是否打开评论，0不打开，1打开
            'only_fans_can_comment' => 'in:0,1', // 是否粉丝才可评论，0所有人可评论，1粉丝才可评论
        ];
    }

    /**
     * @return array|string[]
     */
    protected function haveToFields()
    {
        return ['title', 'thumb_media_id', 'content'];
    }
}
