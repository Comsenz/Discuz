// 封装ajax请求，方便统一处理接口返回值

import Vue from "vue";
import axios from "axios";
import appConfig from "../../config/appConfig";
//需要统一处理的error
const erroCode = [-2];
const qs = require('qs');

//可以正常返回的状态码
var codes = [422];

// http response 拦截器
axios.interceptors.response.use(
  response => {
    return response;
  },
  error => {
   //  if(codes.includes(error.response.status)) {
   //    return error.response;
  	// } else {
   //      alert('wert');
   //      return Promise.reject(error)
  	// }
    return Promise.reject(error);

  }

)
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
const appFetch = function(params, options) {
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
		// console.log(appConfig.apis);
		params.baseURL = "/api";
		params.url = appConfig.apis[oldUrl];
	} else {
		params.baseURL = "/";
		params.url = appConfig.apiBaseUrl + appConfig.apis[oldUrl];
	}

	params.withCredentials = true;

	//设置默认header
	let defaultHeaders = {
		// 'Content-Type': 'application/x-www-form-urlencoded',
    'Content-Type': 'application/json',
    // 'Authorization': 'Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJhdWQiOiIiLCJqdGkiOiI1MGM3ZGQxNDFlMzAyMThhYjBiMDNmNGE1NDUyMWE1YWMzMmQxODQzN2U3MTg0OTM4NTE3ZmNhYzNkZGYwNWZmZTBkMGIwYTJhYmI1ZTlhMSIsImlhdCI6MTU3NDY2NzE4NCwibmJmIjoxNTc0NjY3MTg0LCJleHAiOjE1NzcyNTkxODQsInN1YiI6IjEiLCJzY29wZXMiOltudWxsXX0.FOz9DDw-WE0LoiEb0TeG9Y0xCW-SLNRJaYC91tmgJD-iGqXq7E-ijR9h2zwTk3hFG0uXOiyh9vZw6UeuxMHX_4jU1KejHWkgifgdXJtMR4LC3vNLkIaLZKouPzOU4q2gYYU7bIJHaeqh5we6kxG4w2vki6RGEw3oGNy9j5gP43yyIa3EvnUkwdugPElihh-5EDbcLC4S3ra3vgFDZ99ECC9DWVTKQXmVJdlSEqGEsxYJIsHRIs8J2tNhdTmI0YBUubPFzlE9jisInXtRjatYUuA2qGszkG5GCCE-eia0pTdzfF3qhwsrKAs7rr94c_AT55fcvXsGD55W_5LrHASeIw',
    'Authorization':this.browserDb.getLItem(key)
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

	return axios(params).then(data => {return data.data}, errors => {
      let requestError = errors.response;
      // alert(requestError.status +'3456');
      let children;
      switch (requestError.status) {
        case 422:
          children = requestError.data.errors
            .map(error => [error.detail, '<br/>'])
            .reduce((a, b) => a.concat(b), [])
            .slice(0, -1);
          break;
        case 400:

          break;

        case 401:
        case 403:
          children = 'permission_denied_message';
          break;

        case 404:
        case 410:
          children = 'not_found_message';
          break;

        case 429:
          children = 'rate_limit_exceeded_message';
          break;
        default:
          children = 'generic_message';
      }

      // console.log(children.toString());
      // let msg = children.toString().replace(/,/g,'');  //去掉字符串的逗号
      app.$toast({type: 'html', 'message': children.toString()});

      return Promise.reject(requestError.data);
  });
}

Vue.prototype.appFetch = appFetch;

export default appFetch;
