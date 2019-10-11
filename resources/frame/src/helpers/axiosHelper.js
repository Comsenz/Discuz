// 封装ajax请求，方便统一处理接口返回值

import Vue from "vue";
import axios from "axios";
import appConfig from "../../config/appConfig";

//需要统一处理的error
const erroCode = [-2];
const qs = require('qs');


/**
 * 根据api key 获取api 地址
 * @param  {[type]} key [description]
 * @return {[type]}     [description]
 */
const getApi = function(key){
    var uri = appConfig.apis[key];
    if(!uri) {
    	return "";
    }
  
   	return appConfig.apiBaseUrl + uri;
}

/**
 * ajax 调用方法
 * @param  {[type]} params  [除了url参数的意义外，其他参数和axios参数一致]
 * @param  {[type]} success [请求成功回调方法]
 * @param  {[type]} error   [请求失败回调方法]
 * @return {[type]}         [description]
 */
const appFetch = function(params, success, error) {
	var oldUrl = params.url;
	
	if(params === undefined) {
		console.error("必须传递参数");
		return false;
	}

	if(!appConfig.apis[oldUrl]) {
		console.log("接口key："+oldUrl+" 未发现");

		return false;
	}

	//如果是本地请求，就走接口代理
	if(process.env.NODE_ENV === 'development') {
		params.baseURL = "/api";
		params.url = appConfig.apis[oldUrl];
	} else {
		params.baseURL = "/";
		params.url = appConfig.apiBaseUrl + appConfig.apis[oldUrl];
	}
	
	params.withCredentials = true;

	//设置默认header
	let defaultHeaders = {
		'Content-Type': 'application/x-www-form-urlencoded',
		//'HTTP_X_REQUESTED_WITH': 'XMLHttpRequest'
	};
	if(params.headers) {
		params.headers = {
			...defaultHeaders,
			...params.headers
		};		
	} else {
		params.headers = defaultHeaders;
	}

	//get 方式需要把参数传给params
	if(params.method.toLowerCase() == 'get') {
		params.params = params.data;

		delete params.data
	}

	return axios(params).then(function(response) {
		let res = response.data || {};
		res.code = +res.code;

		if(res.code == 10042) {
			//统一判断判断，需要登录
			// Vue.authH.toLogin();

			return;
		} else if(res.e == 10044) {
			//统一提示错误信息
			Vue.prototype.$message({
				type: "error",
				message: res.msg,
				showClose: true
			});
		} else {
			success(response.data);
		}
	})
	.catch(function(err) {
		console.error(err, 'API '+oldUrl);
		error && error(err);
	});
}

Vue.prototype.appFetch = appFetch;

export default appFetch;
