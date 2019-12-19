/**
 * 语言包配置
 */
import Vue from 'vue';

const config = {
	default: '系统错误，请联系管理员',
  permission_denied:'没有权限，请联系站点管理员',
  validation_error:'权限名称不符合规则'
};

Vue.prototype.getLang = function(key) {
	return config[key] ? config[key] : config['default'];
}
