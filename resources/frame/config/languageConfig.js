/**
 * 语言包配置
 */
import Vue from 'vue';

const config = {
	default: '系统错误，请联系管理员',
  access_denied:'拒绝访问',
  category_not_found:'请选择分类',
  censor_not_passed:'抱歉，您填写的内容包含不良信息',
  model_not_found:'您访问的内容不存在或已被删除',
  route_not_found:'路由未找到',
  no_bind_user:'未绑定用户',
  thread_count_fail:'主题数操作错误',
  thread_behavior_fail:'主题状态异常',
  thread_action_fail:'主题操作异常',
  reply_content_cannot_null:'内容不能为空',
  upload_error:'上传图片失败',
  scale_sum_not_10:'分成比例相加必须为 10',
  cannot_delete_category_with_threads:'无法删除存在主题的分类',
  permission_denied:'没有权限，请联系站点管理员',
  validation_error:'验证错误',
  user_update_error:'修改信息失败',
  upload_time_not_up:'上传头像频繁，一天仅允许上传一次头像',
  order_post_not_found:'订单主题不存在',
  order_type_not_found:'订单类型有误',
  order_create_failure:'订单创建失败',
  status_cash_freeze:'钱包已冻结提现',
  available_amount_error:'钱包可用金额不足',
  operate_type_error:'操作类型不存在',
  wallet_status_error:'钱包状态有误',
  file_type_not_allow:'文件类型不允许',
  file_size_not_allow:'文件大小不允许',
  sms_verify_error:'验证码错误',
  operate_forbidden:'非法操作',
  login_failed:'帐号或密码错误',
  login_failures_times_toplimit:'密码错误次数达到5次，请15分钟后再次尝试',
  site_closed:"站点已关闭！",
  ban_user:"您的账号被禁用，无法访问本站点",
  register_validate:'账号审核中,请审核通过尝试',
  mobile_is_already_bind:'手机已经绑定',
  setting_fill_register_reason:'注册时的注册原因必须必填',
  faceid_error:'身份信息验证不通过'


};

Vue.prototype.getLang = function(key) {
	return config[key] ? config[key] : config['default'];
}
