/**
 * 模板实例化和个性化设置
 */

import pcMobileChange from "../../template/default/config/pcMobileConfig";	//获取手机和移动端切换配置

import Vue from 'vue';
import baseTpl from "../../extend/viewBase/baseTpl";
import tplConfig from "../viewConfig/tplConfig";
import commonHelper from "commonHelper";
import "../config/elementuiInit";						//初始化饿了么组件
import "systemCommon.scss";


//实例化当前模板
const defaultConfig = new baseTpl(tplConfig);

/**
 * 初始化模板
 * @return {[type]} [description]
 */
defaultConfig.init = function() {
	var Router = this.loadRouter();
	this.beforeEnterModule(Router);

	return Router;
};

/**
 * 根据配置，判断如果是手机环境，并且是pc页面，跳转对应手机页面，已经跳转一个页面后则不再进行跳转
 * @return {[type]} [description]
 */
defaultConfig.pcToMobile = function(Router, to) {
	var nowPage = to.matched && to.matched.length && to.matched[0];
	if(!nowPage) return false;

	var eventIsPhone = commonHelper.isWeixin().isPhone,
		nowPath = nowPage.path,
		pageIsPhone = nowPath.indexOf("m_") !== -1;
		
	if(eventIsPhone !== pageIsPhone) {
		var nowKey = pageIsPhone ? "mobilePath" : "pcPath",
			jumpKey = pageIsPhone ? "pcPath" : "mobilePath",
			isJumped = false;

		pcMobileChange.forEach(function(oneChange) {
			if(isJumped) return true;

			if(oneChange[nowKey] == nowPath) {
				switch(oneChange.type) {
					case 1:
						Router.replace({path: oneChange[jumpKey], query: to.query});
						break;
					case 2:
						oneChange.changeFunc(Router, oneChange[jumpKey], to);
						break;
				}

				isJumped = true;
			}
		});
	}
}

/**
 * [模块加载前回调函数]
 * @param {[type]} Router [description]
 */
defaultConfig.beforeEnterModule = function(Router) {
	var _this = this;
		
	/**
	 * 获取用户信息，判断当前模块是否需要登陆，如果需要，直接跳转登陆页
	 * @param  {[type]} to    [目标模块]
	 * @param  {[type]} from  [来源模块]
	 * @param  {[type]} next) [执行下一步方法]
	 * @return {[type]}       [description]
	 */
	Router.beforeEach(function(to, from, next) {
		_this.pcToMobile(Router, to);
		next();
	});
}

export default defaultConfig;
