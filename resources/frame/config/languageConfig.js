/**
 * 语言包配置
 */
import Vue from 'vue';

const config = {
	default: '系统错误，请联系管理员',
  access_denied:'拒绝访问',
  category_not_found:'请选择分类',
  censor_not_passed:'抱歉，您填写的内容包含不良信息',
  model_not_found:'模型未找到',
  route_not_found:'路由未找到',
  no_bind_user:'未绑定用户',
  thread_count_fail:'主题数操作错误',
  thread_behavior_fail:'主题状态异常',
  thread_action_fail:'主题操作异常',
  upload_error:'上传图片失败',
  scale_sum_not_10:'分成比例相加必须为 10',
  cannot_delete_category_with_threads:'无法删除存在主题的分类',
  permission_denied:'没有权限，请联系站点管理员',
  validation_error:'验证错误',
  upload_time_not_up:'上传头像频繁',
  order_post_not_found:'订单主题不存在',
  order_type_not_found:'订单类型有误',
  order_create_failure:'订单创建失败',
  status_cash_freeze:'钱包已冻结提现',
  available_amount_error:'钱包可用金额不足',
  operate_type_error:'操作类型不存在',
  wallet_status_error:'钱包状态有误',
  operate_forbidden:'非法操作',
  login_failed:'帐号或密码错误',
  login_failures_times_toplimit:'密码错误次数达到5次，请15分钟后再次尝试',

};

Vue.prototype.getLang = function(key) {
	return config[key] ? config[key] : config['default'];
}
