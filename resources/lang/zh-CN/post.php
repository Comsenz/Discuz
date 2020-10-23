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

/**
 * post_[type]_*   类型错误
 * not_not_*       拒绝/不允许
 */
return [
    'post_not_found' => '未查询到该条回复',
    'post_not_comment' => '不能回复，回复回帖的内容', // 点评

    // goods商品解析
    'post_goods_not_found_address' => '未匹配到地址格式',
    'post_goods_does_not_resolve' => '域名地址不在解析范围内',
    'post_goods_not_found_regex' => '未匹配到地址信息',
    'post_goods_fail_url' => '匹配到解析地址错误',
    'post_goods_not_found_enum' => '暂不支持解析该地址内容',
    'post_goods_http_client_fail' => '请求地址失败',
    'post_goods_frequently_fail' => '因频繁解析，请在1分钟后重新尝试解析',

    'post_question_missing_parameter' => '问答缺失参数',
    'post_question_edit_fail_answered' => '问答帖已回答后不允许修改',
    'post_question_payment_amount_fail' => '问答支付金额异常',
    'post_question_ask_yourself_fail' => '不能向自己提问',
    'post_question_ask_be_user_permission_denied' => '被提问用户没有权限回答',
];
