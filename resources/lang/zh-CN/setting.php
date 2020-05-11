<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

return [
    // default
    'set_error' => '设值失败',

    'min_greater_than_max' => '单次提现最小金额 不能大于 最大金额',
    'min_greater_than_limit' => '单次提现最小金额 不能大于 单日提现总金额',
    'min_exceed_5000' => '单次提现最小金额 不能超过 5000',

    'max_greater_than_limit' => '单次提现最大金额 不能大于 单日提现总金额',
    'max_exceed_5000' => '单次提现最大金额 不能超过 5000',

    'limit_exceed_5000' => '单日提现总金额 不能超过 5000',

    'setting_fill_register_reason' => '注册时的注册原因必须必填',

    // 附件
    'support_img_ext_php_format_error' => '图片扩展名不允许设置.php后缀',
    'support_file_ext_php_format_error' => '附件扩展名不允许设置.php后缀',

    /**
     * qclond
     */
    // - captcha - try
    'tencent_captcha_key_error' => '腾讯云验证码配置错误',
    'tencent_captcha_InternalError' => '腾讯云验证码内部错误',
    'tencent_captcha_MissingParameter' => '腾讯云验证码缺少参数错误',
    'tencent_captcha_UnauthorizedOperation.ErrAuth' => '腾讯云验证码鉴权失败',
    'tencent_captcha_UnauthorizedOperation.Unauthorized' => '验证码在腾讯云未开通权限',
    'tencent_captcha_AuthFailure.SecretIdNotFound' => '腾讯云SecretId不存在，请输入正确的密钥',
    'tencent_captcha_AuthFailure.SignatureFailure' => '腾讯云SecretKey不存在，请输入正确的密钥',
    // - captcha - message
    'tencent_captcha_code_6' => '验证码错误(6):长度不匹配',
    'tencent_captcha_code_7' => '验证码错误(7):答案不匹配/Randstr参数不匹配',
    'tencent_captcha_code_8' => '验证码错误(8):签名超时',
    'tencent_captcha_code_9' => '验证码错误(9):签名重放',  // 验证码ticket&randstr签名重复使用会提示
    'tencent_captcha_code_10' => '验证码错误(10):签名序列',
    'tencent_captcha_code_11' => '验证码错误(11):cooking信息不合法',
    'tencent_captcha_code_12' => '验证码错误(12):签名长度错误',
    'tencent_captcha_code_13' => '验证码错误(13):match ip不匹配',
    'tencent_captcha_code_15' => '验证码错误(15):的签名解密失败',
    'tencent_captcha_code_16' => '验证码错误(16):强校验appId错误',
    'tencent_captcha_code_17' => '验证码错误(17):系统命令不匹配',
    'tencent_captcha_code_18' => '验证码错误(18):号码不匹配',
    'tencent_captcha_code_19' => '验证码错误(19):重定向验证',
    'tencent_captcha_code_20' => '验证码错误(20):操作使用pt免验证码校验错误',
    'tencent_captcha_code_21' => '验证码错误(21):diff 差别，验证错误',
    'tencent_captcha_code_22' => '验证码错误(22):类型与拉取时不一致',
    'tencent_captcha_code_23' => '验证码错误(23):验证类型错误',
    'tencent_captcha_code_24' => '验证码错误(24):非法请求包',
    'tencent_captcha_code_25' => '验证码错误(25):策略拦截',
    'tencent_captcha_code_26' => '验证码错误(26):腾讯系统内部错误',
    'tencent_captcha_code_100' => '验证码错误(100):的配置参数错误',
    'tencent_captcha_unknown_error' => '验证码未知错误:腾讯云未知错误',
    'tencent_captcha_unknown_CaptchaCode' => '验证码未知错误:腾讯云验证码未知CaptchaCode',
];
