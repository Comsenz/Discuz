/**
 * 语言包配置
 */
import Vue from 'vue';

const config = {
	default: '系统错误，请联系管理员'
};

Vue.prototype.getLang = function(key) {
	return config[key] ? config[key] : config['default'];
}