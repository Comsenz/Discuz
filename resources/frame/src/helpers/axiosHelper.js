// 封装ajax请求，方便统一处理接口返回值

import Vue from "vue";
import axios from "axios";
import appConfig from "../../config/appConfig";
import browserDb from 'webDbHelper';
import appCommonH from "./commonHelper";

const Router = Vue.prototype.$rconfig;

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
    return error.response;
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
 * 解析数据
 * @param  {[type]} data [description]
 * @return {[type]}      [description]
 */
const analyzingData = function(data, included) {
  included  = included ? included : [];

  var newIncludes = {};
  included.forEach(function(nowOne) {
    newIncludes[nowOne.type+nowOne.id] = nowOne;
  })

  /**
   * 获取一条数据真实数据
   * @return {[type]} [description]
   */
    function getOneData(nowData) {
      var result = {};

      //没有值时返回空对象
      if(!nowData) return result;

      if(!nowData.attributes) {
        nowData = newIncludes[nowData.type+nowData.id];
      }
      // else{
      //   result._data = nowData.attributes;
      // }

      result._data = nowData.attributes;
      if(nowData.id) result._data.id = nowData.id; //有id时将id加入_data

      if(nowData.relationships) {
        var relationObj = {};

        for(var relationKey in nowData.relationships) {
          relationObj[relationKey] = getData(nowData.relationships[relationKey].data);
        }

        result = {...result, ...relationObj};
      }

      return result;
    }

    /**
     * 获取一条数据和一组数据
     * @param  {[type]} nowData [description]
     * @return {[type]}         [description]
     */
    function getData(nowData) {
      var result = {};

      if(nowData instanceof Array) {
        result = [];

        nowData.forEach(function(nowOne) {
          result.push(getOneData(nowOne));
        });
      } else {
        result = getOneData(nowData);
      }

      return result;
    }

  return getData(data, included);
}


/**
 * ajax 调用方法
 * @param  {[type]} params  [除了url参数的意义外，其他参数和axios参数一致]
 * @param  {[type]} success [请求成功回调方法]
 * @param  {[type]} error   [请求失败回调方法]
 * @return {[type]}         [description]
 */
const appFetch = function(params, options) {
  if(params === undefined) {
    console.error("必须传递参数");
    return false;
  }
  var oldUrl = params.url;
  var apiUrl = appConfig.apis[oldUrl];

  //是不是标准接口
  params.standard = params.standard !== undefined ? params.standard : true;



  params.method = params.method ? params.method : 'get';
  // if(!apiUrl) {
  //   apiUrl = "/api/" + oldUrl;
    // return false;
  // }

  /**
    * @param {[type]} splice [接收url后面拼接]
    * @param splice:'/2019120310255349505652',
    */
  if (params.splice){
    apiUrl = apiUrl + params.splice;
  }

  //如果是本地请求，就走接口代理
  if(process.env.NODE_ENV === 'development') {
    params.baseURL = "/api";
    params.url = apiUrl;
  } else {
    params.baseURL = "/";
    params.url = appConfig.apiBaseUrl + apiUrl;
  }

  params.withCredentials = true;
  var authVal = browserDb.getLItem('Authorization');

  //统一不需要toke的路由列表
  let requireAuth = [
    'login-user',
    'login-phone',
    'wx-login-bd',
    'wx-sign-up-bd',
    'sign-up',
    'bind-phone',
    'retrieve-pwd',
    'admin/login',
    'supplier-all-back'
  ];

// && !requireAuth.includes(window.location.pathname)


  let defaultHeaders;
  if(authVal != '' && authVal != null && !requireAuth.includes(window.location.pathname)){
    defaultHeaders = {
      'Content-Type': 'application/json',
      'Authorization':'Bearer ' + authVal
    };
  } else {
    defaultHeaders = {
      'Content-Type': 'application/json',
      'Authorization':''
    };
  }

  //设置默认header
  if(params.headers) {
    params.headers = {
      ...defaultHeaders,
      ...params.headers
    };
  } else {
    params.headers = defaultHeaders;
  }

  //get 方式需要把参数传给params
  if(params.method.toLowerCase() == 'get'&& params.data) {
    params.params = params.data ? params.data : params.params;

    //如果传递include，处理成字符串
    if(params.params.include && params.params.include instanceof Array) {
      params.params.include = params.params.include.join(',');
    }

    //如果需要传递对象
    ['filter', 'page', 'filed'].forEach(function(pName) {
      if(params.params[pName] && params.params[pName] instanceof Object) {
        var addObject = {};

        Object.keys(params.params[pName]).forEach(function(nowKey) {
          addObject[pName+'['+nowKey+']'] = params.params[pName][nowKey];
        });

        delete params.params[pName];
        params.params = {...params.params, ...addObject}
      }
    })

    if(params.data) delete params.data
  }

  return axios(params).then(data => {
    if(data.status >= 200 && data.status < 300) {
      if(params.standard) {
        if(data.data.meta && data.data.meta instanceof Array) {
          data.data.meta.forEach(function(error) {
            error.code = error.code ? Vue.prototype.getLang(error.code) : Vue.prototype.getLang(error.message);
          })
        }

        //处理后的结构数据
        if(data.data.data) {
          data.data.readdata = analyzingData(data.data.data, data.data.included);
        }
      }

      return data.data;
    } else {

      if (data.data.errors[0].code === 'access_denied'){
        //拒绝访问需要跳转到登录页面
        let isWeixin = appCommonH.isWeixin().isWeixin;

        if (isWeixin){
          browserDb.setLItem('Authorization','');
          getNewToken().then(res=>{
            Router.init().replace({path:'/supplier-all-back',query:{url:Router.init().history.current.path}});
          })
        }else {
          localStorage.clear();
          Router.init().push({path:'/login-user'})
        }

      }

      data.data.rawData = appCommonH.copyObj(data.data.errors);

      data.data.errors.forEach(function(error) {
        error.code = Vue.prototype.getLang(error.code);
      });

      if (data.data.rawData[0].code === 'access_denied' && appCommonH.isWeixin().isWeixin){
        //为什么注释delete，因为删除后上面的判断没有errors，导致报错。也导致接口请求走catch
        // delete data.data.errors;
      }

      return data.data;
    }
  });
}


/**
 * 拉取新token
 * @param  {[type]} data [description]
 * @return {[type]}      [description]
 */
const getNewToken = function (router) {
  let that = this;


  return appFetch({
    url:'access',
    method:'post',
    data:{
      "data": {
        "attributes": {
          'grant_type':'refresh_token',
          'refresh_token':browserDb.getLItem('refreshToken')
        }
      }
    }
  }).then(res=>{
    let token = res.data.attributes.access_token;
    // let tokenId = res.data.id;
    let refreshToken = res.data.attributes.refresh_token;
    browserDb.setLItem('Authorization', token);
    // browserDb.setLItem('tokenId', tokenId);
    browserDb.setLItem('refreshToken',refreshToken);
  }).catch(err=>{
    browserDb.removeLItem('Authorization');
    browserDb.removeLItem('refreshToken');
    browserDb.removeLItem('tokenId');
  })
}

Vue.prototype.appFetch = appFetch;

export default appFetch;
