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

namespace App\Validators;

use Discuz\Foundation\AbstractValidator;

class OffIAccountAssetUploadValidator extends AbstractValidator
{
    /**
     * @return array
     */
    protected function getRules()
    {
        return [
            'title' => 'required',  // 标题
            'thumb_media_id' => 'required', // 图文消息的封面图片素材id（必须是永久 media_ID）
            'authod' => '', // 作者(微信文档起名错误)
            'digest' => '', // 图文消息的摘要，仅有单图文消息才有摘要，多图文此处为空
            'show_cover_pic' => '', // 是否显示封面，0为false，即不显示，1为true，即显示
            'show_cover' => 'required|in:0,1', // 官方未提及该字段，字面意思 是否显示封面
            'content' => 'required', // 图文消息的具体内容，支持HTML标签，必须少于2万字符，小于1M，且此处会去除JS
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
        return ['title', 'thumb_media_id', 'show_cover', 'content'];
    }
}
