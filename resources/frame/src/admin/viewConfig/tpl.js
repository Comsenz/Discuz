/**
 * 模板实例化和个性化设置
 */
import Vue from 'vue';
import baseTpl from "../../extend/viewBase/baseTpl";
import tplConfig from "../viewConfig/tplConfig";
import commonHelper from "commonHelper";
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
 * [模块加载前回调函数]
 * @param {[type]} Router [description]
 */
defaultConfig.beforeEnterModule = function(Router) {
	var _this = this;

	/**
	 * 获取用户信息，判断当前模块是否需要登录，如果需要，直接跳转登录页
	 * @param  {[type]} to    [目标模块]
	 * @param  {[type]} from  [来源模块]
	 * @param  {[type]} next) [执行下一步方法]
	 * @return {[type]}       [description]
	 */
	Router.beforeEach(function(to, form, next) {
		tplConfig.beforeEnter(to, form, next);
	});

  Router.onError((error) => {
    const pattern = /Loading chunk (\d)+ failed/g;
    const isChunkLoadFailed = error.message.match(pattern);
    if(isChunkLoadFailed){
      location.reload();
      // const targetPath = $router.history.pending.fullPath;
      // $router.replace(targetPath);
    }

  });
}

export default defaultConfig;
