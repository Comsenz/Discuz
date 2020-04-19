import frontTplConfig from "./tplConfig";
import baseTpl from "../../../extend/viewBase/baseTpl";

const defaultConfig = new baseTpl(frontTplConfig);

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
        frontTplConfig.beforeEnter(to, form, next);
	});

  Router.onError((error) => {
    const pattern = /Loading chunk (\d)+ failed/g;
    const isChunkLoadFailed = error.message.match(pattern);
    if(isChunkLoadFailed){
      location.reload();
    }

  });
}

export default defaultConfig;