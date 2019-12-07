/**
 * 模板配置文件 base类
 * @type {[type]}
 */
var path = require('path');
import Vue from "vue";
import VueRouter from "vue-router";
import md5 from "js-md5";
import commonHelper from "commonHelper";
Vue.use(VueRouter);

const appConfig = Vue.prototype.appConfig;
const baseTpl = function (params) {
	this.Router = null;

	this.template = params.template ? params.template : null;

	this.publicCss = params.publicCss ? params.publicCss : [];
	this.publicJs = params.publicJs ? params.publicJs : [];
	this.styleCss = params.styleCss ? params.styleCss : {};

	this.needLogins = params.needLogins ? params.needLogins : [];
	this.ctype = "";
}

/**
 * 检查配置
 * @return {[type]} [description]
 */
baseTpl.prototype.checkConfig = function() {
	if(!this.template || !this.checkTemplate()) {
		console.error("template 模板配置错误！");

		return false;
	}

	return true;
};

/**
 * 检查模板配置
 * @return {[type]} [description]
 */
baseTpl.prototype.checkTemplate= function() {
	var pageNum = 0;

	for(var moduleName in this.template) {
		var moduleInfo = this.template[moduleName];

		for(var pageName in moduleInfo) {
			if(['js', 'css'].includes(pageName)) continue;

			var pageInfo = moduleInfo[pageName];

			if(!pageInfo.comLoad || typeof pageInfo.comLoad != "function") {
				console.error(moduleName+"模块， "+pageName+"页面，comLoad函数设置错误！");

				return false;
			}

			if(!pageInfo.metaInfo || !pageInfo.metaInfo.title) {
				console.error(moduleName+"模块， "+pageName+"页面，metainfo设置错误！");

				return false;
			}

			pageNum++;
		}
	}

	if(!pageNum) {
		console.error("最少应该有一个页面！");

		return false;
	}

	return true;
};

/**
 * 实例化路由
 * @param  {[array]} routes [路由配置]
 * @return {[object]}        [路由对象]
 */
baseTpl.prototype.getBaseRouter = function(routes) {

  if(this.Router) {
		return this.Router;
	} else {
		//实例化路由
		this.Router = new VueRouter({
			mode: 'history',
			routes: routes,
			base: appConfig.siteBasePath,
			scrollBehavior: function(to, from, savedPosition) {
				return {x: 0, y: 0};
			}
		});

		//each 结束后加载完成进度条
		var _this = this;
		this.Router.afterEach(function() {
			_this.progressEnd();
		})

		//基本信息加载
		this.progressStart();
		this.loadMetaInfo(this.Router);
		this.loadOtherSource(this.Router);

		return this.Router;
	}
}

baseTpl.prototype.mergeArr = function(one, two) {
	one = one ? one : [];
	two = two ? two : [];

	return [...one, ...two];
}

/**
 * 加载路由和模板页面
 * @return {[type]} [description]
 */
baseTpl.prototype.loadRouter = function() {
	if(!this.checkConfig()) return false;

	var routes = [],
		template = this.template,
		defaultView = null,
		_this = this;


  	for(var folder in template) {
	    var nowModules = template[folder];

	    for(var mName in nowModules) {
	    	if(['js', 'css'].includes(mName)) continue;

	      	var newChildrenList = [];
	      	for (var childrenName in nowModules[mName].children){
	        	var newChildren = {
			            name: nowModules[mName].children[childrenName].metaInfo.title,
			            path: (['m_site', 'admin_site'].includes(folder) ? '' : "/" + folder) + "/" + mName + "/" + childrenName,
			            component: nowModules[mName].children[childrenName]['comLoad'],
			            meta: {
			            	...nowModules[mName].children[childrenName]['metaInfo'],
							css: this.mergeArr(nowModules[mName].children[childrenName]['css'], nowModules['css']),
				            js: this.mergeArr(nowModules[mName].children[childrenName]['js'], nowModules['js']),
				            isMobile: folder == 'm_site'		            	
			            }
		          	};

	        	newChildrenList.push(newChildren);
	      	}

	    	var nowRouterInfo = {
					name: mName,
			        path: `${['m_site', 'admin_site'].includes(folder) ? '' : ('/'+folder )}${'/'+mName}`,
			        children:newChildrenList,
					component: nowModules[mName]["comLoad"],
					meta: {
						...nowModules[mName]["metaInfo"],
						css: this.mergeArr(nowModules[mName]["css"], nowModules['css']),
						js: this.mergeArr(nowModules[mName]["js"], nowModules['js']),
						isMobile: folder == 'm_site' 					
					}
					
				};

			routes.push(nowRouterInfo);
		}
	}

	//设置默认加载页面
	defaultView = {...{}, ...routes[0]};
	defaultView.path = "*";
	routes.push(defaultView);

  	return this.getBaseRouter(routes);
}

/**
 * 路由访问时加载元信息
 * @return {[type]} [description]
 */
baseTpl.prototype.loadMetaInfo = function(Router) {
	Router.beforeEach(function(to, from, next) {
		if(to.meta.title) document.title = to.meta.title;
		if(to.meta.desc) document.desc = to.meta.desc;

		next();
	});
}

//资源列表
baseTpl.prototype.sourceArrs = {};

//资源加载总数
baseTpl.prototype.loadAllNum = 0;

//加载成功会掉方法
baseTpl.prototype.sourceCallBack = null;

/**
 * 第三方资源加载
 * @param  {[type]} Router [description]
 * @return {[type]}        [description]
 */
baseTpl.prototype.loadOtherSource = function(Router) {
	var _this = this;

	Router.beforeEach(function(to, from, next) {
		_this.clearSource();

		var nowRoute = to.matched[0].path == "*" ? Router.options.routes[0] : to;
		var publicCss = _this.publicCss ? _this.publicCss : [],
			publicJs = _this.publicJs ? _this.publicJs : [],
			selfCss = nowRoute.meta.css ? nowRoute.meta.css : [],
			selfJs = nowRoute.meta.js ? nowRoute.meta.js : [];

		var sourceArrs = {
			css: [...publicCss, ...selfCss],
			js: [...publicJs, ...selfJs],
		};

		if(!sourceArrs.css.length && !sourceArrs.js.length) {
			next();
		} else {
			_this.sourceArrs = sourceArrs;
			_this.registerSource(to, next);
		}
	});
};

/**
 * 获取风格css
 * @param  {[type]} topath [description]
 * @return {[type]}        [description]
 */
baseTpl.prototype.getStyleCss = function(topath) {
	var styleCss = this.styleCss;

	if(!styleCss.path || !styleCss.baseName.length) {
		return [];
	}

	var styleCssPaths = [],
		mobilePrefix = commonHelper.getClientType(topath) ? "m." : "",
		type = commonHelper.getClientType(topath) ? "mstyle" : "style";

	var _this = this;
	styleCss.baseName.forEach(function(cssName) {
		styleCssPaths.push(styleCss.path+mobilePrefix+commonHelper.getWebStyle(type)+"."+cssName);
	});

	return styleCssPaths;
};

/**
 * 获取页面类型
 * @param  {[type]} to [description]
 * @return {[type]}    [description]
 */
baseTpl.prototype.getClientClass = function(to) {
	return to.meta.isMobile ? 'mobile' : 'pc';
}

/**
 * 注册要引入的css和js资源，注册后清空上一次的注册
 * @param  {[type]} sourceArr [列表]
 * @return {[type]}           [description]
 */
baseTpl.prototype.registerSource = function(to, next) {
	this.sourceCallBack = next;

	var nowClientClass = this.getClientClass(to);

	this.ctype = nowClientClass;
	this.loadCssSource(nowClientClass);
	this.loadJsSource(nowClientClass);
}

/**
 * 清空资源加载状态
 * @return {[type]} [description]
 */
baseTpl.prototype.clearSource = function() {
	this.sourceArrs = [];
	this.loadAllNum = 0;
	this.sourceCallBack = null;
}

/**
 * 加载css资源
 * @return {[type]}           [description]
 */
baseTpl.prototype.loadCssSource = function(clientClass) {
	var _this = this;

	this.sourceArrs.css.forEach(function(cssOne) {
		var allPath = appConfig.staticBaseUrl+cssOne,
			md5Key = md5(allPath);

		_this.loadAllNum++;
		if(!$('[data-id="'+md5Key+'"]').length) {
			var cssHtml = '<link data-id="'+md5Key+'" data-type="'+clientClass+'" href="'+allPath+'?v='+appConfig.sourceV+'" rel="stylesheet" type="text/css"/>';
			$("head").append(cssHtml);

			$('[data-id="'+md5Key+'"]').load(function() {
				_this.loadSourceSuccess(clientClass);
			});
		} else {
			setTimeout(function() {
				_this.loadSourceSuccess(clientClass);
			}, 100);
		}
	})
}

/**
 * 加载js资源
 * @return {[type]} [description]
 */
baseTpl.prototype.loadJsSource = function(clientClass) {
	var _this = this;

	this.sourceArrs.js.forEach(function(jsPath) {
		var allPath = appConfig.staticBaseUrl+jsPath,
			md5Key = md5(allPath);

		_this.loadAllNum++;
		if(!$('[data-id="'+md5Key+'"]').length) {
			var jsScript = document.createElement("script");
			jsScript.src = allPath+'?v='+appConfig.sourceV;
			jsScript.setAttribute("data-id", md5Key);
			jsScript.setAttribute("data-type", clientClass);
			jsScript.type = "text/javascript";
			jsScript.async = false;
			jsScript.setAttribute("data-path", jsPath);
			jsScript.onload = function() {
				_this.loadSourceSuccess(clientClass);
			};

			document.getElementsByTagName("body")[0].appendChild(jsScript);
		} else {
			setTimeout(function() {
				_this.loadSourceSuccess(clientClass);
			}, 100);
		}
	})
}

/**
 * 加载资源成功会掉方法
 * @return {[type]} [description]
 */
baseTpl.prototype.loadSourceSuccess = function(clientClass) {
	this.loadAllNum--;

	if(!this.loadAllNum) {
		this.sourceCallBack();
	}
}

/**
 * 清除非当前类型客户端残留
 * @return {[type]} [description]
 */
baseTpl.prototype.clearOtherClientStyle = function() {
	var nowClient = commonHelper.isWeixin().isPhone ? "mobile" : "pc";
	if(this.ctype != nowClient) return false;

	$('[data-type="'+(this.ctype == "pc" ? "mobile" : "pc")+'"]').remove();
	//手机转pc 端去掉rem 设置
	if(this.ctype == "pc") {
		document.documentElement.style = "";
	}
}

/**
 * 模块加载前，回调函数
 * @param  {[type]} Router [description]
 * @return {[type]}        [description]
 */
baseTpl.prototype.beforeEnterModule = function(Router) {

}

//进度条选择器
baseTpl.prototype.progressSelector = "progress_loading";

/**
 * 开始显示进度条
 * @return {[type]} [description]
 */
baseTpl.prototype.progressStart = function() {
	if(!$("#"+this.progressSelector).length) {
		var progressHtml = '<div id="'+this.progressSelector+'">'
								+'<div></div>'
						   +'</div>';

		$("body").append(progressHtml)
	}

	this.clearProgress();
	$("#"+this.progressSelector).show();
	this.progressNum = 0;

	this.progressChange(98, 3000);
}

/**
 * 结束显示进度条
 * @return {[type]} [description]
 */
baseTpl.prototype.progressEnd = function() {
	if($(".frame-loader").length) {
		$(".frame-loader").remove();
	}

	this.progressChange(100, 300);
}

//周期循环句柄
baseTpl.prototype.progressClearBar = null;

//进度具体数值
baseTpl.prototype.progressNum = 0;

/**
 * 进度条改变事件
 * @param  {[type]} endnum [结束进度]
 * @param  {[type]} time   [所用时间]
 * @return {[type]}        [description]
 */
baseTpl.prototype.progressChange = function(endnum, time) {
	var stepTime = 20,
		stepNum = (endnum - this.progressNum) / (time / stepTime),
		_this = this;


	clearInterval(_this.progressClearBar);
	_this.progressClearBar = setInterval(function() {
		$("#"+_this.progressSelector).find("div").width(_this.progressNum+"%");

		if(_this.progressNum >= endnum) {
			clearInterval(_this.progressClearBar);

			if(_this.progressNum >= 100) {
				$("#"+_this.progressSelector).hide();
				_this.clearProgress();
				_this.clearOtherClientStyle();
			}

			return false;
		}

		_this.progressNum += stepNum;
	}, stepTime);
}

/**
 * 清空进度条进度
 * @return {[type]} [description]
 */
baseTpl.prototype.clearProgress = function() {
	$("#"+this.progressSelector).find("div").width("0%");
}

export default baseTpl;
