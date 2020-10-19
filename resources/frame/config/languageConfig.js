/**
 * 语言包配置
 */
import Vue from 'vue';

const config = {
  default: '系统错误，请联系管理员',
  access_denied: '拒绝访问',
  category_not_found: '请选择分类',
  censor_not_passed: '抱歉，您填写的内容包含不良信息',
  model_not_found: '您访问的内容不存在或已被删除',
  route_not_found: '路由未找到',
  no_bind_user: '未绑定用户',
  thread_count_fail: '主题数操作错误',
  thread_behavior_fail: '主题状态异常',
  thread_action_fail: '主题操作异常',
  upload_error: '上传图片失败',
  scale_sum_not_10: '分成比例相加必须为 10',
  cannot_delete_category_with_threads: '无法删除存在主题的分类',
  permission_denied: '没有权限，请联系站点管理员',
  validation_error: '验证错误',
  user_update_error: '修改信息失败',
  order_post_not_found: '订单主题不存在',
  order_type_not_found: '订单类型有误',
  order_create_failure: '订单创建失败',
  status_cash_freeze: '钱包已冻结提现',
  available_amount_error: '钱包可用金额不足',
  operate_type_error: '操作类型不存在',
  wallet_status_error: '钱包状态有误',
  file_type_not_allow: '文件类型不允许',
  file_size_not_allow: '文件大小不允许',
  sms_verify_error: '验证码错误',
  operate_forbidden: '非法操作',
  login_failed: '帐号或密码错误',
  login_failures_times_toplimit: '密码错误次数达到5次，请15分钟后再次尝试',
  site_closed: "站点已关闭！",
  ban_user: "您的帐号被禁用，无法访问本站点",
  register_validate: '帐号审核中，请审核通过后尝试',
  mobile_is_already_bind: '手机已经绑定',
  setting_fill_register_reason: '注册时的注册原因必须必填',
  faceid_error: '身份信息验证不通过',
  invalid_emoji_path: '无效的表情目录',
  notification_is_missing_template_config: '微信推送信息不全',
  tencent_qcloud_close_current: '腾讯云Api配置未开启',
  tencent_secret_key_error: '腾讯云Secretid或SecretKey不正确',
  tencent_vod_transcode_error: '腾讯云云点播转码模板未设置',
  tencent_vod_subappid_error: '腾讯云云点播子应用错误',
  pay_password_failures_times_toplimit: '您输入的密码错误次数已超限，请点击忘记密码找回或次日后重试',
  offIAccount_server_config_signature_failed: '公众号服务器配置：签名验证失败',
  qcloud_vod_cover_template_not_found: '腾讯云云点播截图模板不存在',
  tencent_vod_error : '腾讯云云点播配置错误',
  tencent_qcloud_sms_app_error : '腾讯云短信配置错误',
  tencent_vod_taskflow_gif_error : '动图封面任务流名称错误',
  uninitialized_pay_password: '未设置支付密码',
};

Vue.prototype.getLang = function (key) {
  return config[key] ? config[key] : config['default'];
}
