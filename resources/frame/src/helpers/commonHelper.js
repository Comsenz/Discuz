/**
 * 公共方法
 */

import Vue from "vue";
import appFetch from "axiosHelper.js";
import appConfig from "../../config/appConfig";

const appCommonH = {};

/**
 * 根据参数对象和url拼接url
 * @param  {[type]} url    [不带参数的url]
 * @param  {[type]} urlObj [参数对象]
 * @return {[type]}        [description]
 */
appCommonH.getUrlStr = function(url, urlObj) {
  url += "?";

  for(var key in urlObj) {
    if(urlObj[key]) url += key+"="+urlObj[key]+"&";
  }

  return url.slice(0, -1);
}

/**
 * 是否是空对象
 * @param  {[type]}  data [description]
 * @return {Boolean}      [description]
 */
appCommonH.isEmptyObj = function(data) {
  if(!data) return true;
  
  return !Object.keys(data).length;
}

/**
 * 首字母大写
 * @param  {[type]} str [description]
 * @return {[type]}     [description]
 */
appCommonH.firstUppercase = function(str) {
	if(!str) return false;

	str = str.toString();

	return str.substr(0, 1).toUpperCase() + str.substr(1);
}

/**
 * 深层复制兑现
 * @param  {[type]} obj [description]
 * @return {[type]}     [description]
 */
appCommonH.copyObj = function(obj) {
  return JSON.parse(JSON.stringify(obj));
}

/**
 * for 循环方式复制对象
 * @param  {[type]} obj [description]
 * @return {[type]}     [description]
 */
appCommonH.copyObjFor = function(obj) {
  function deepCopy(obj){
    if(typeof obj != 'object'){
        return obj;
    }

    var newobj = {};
    if(Array.isArray(obj)) {
      newobj = []
    } else if(obj === null) {
      newobj = null;
    }

    if(newobj !== null) {
      for ( var attr in obj) {
          newobj[attr] = deepCopy(obj[attr]);
      }      
    }

    return newobj;
  }

  return deepCopy(obj);
}

/**
 * 是否是微信和是否是手机
 * @return {Boolean} [description]
 */
appCommonH.isWeixin = function(){
  var u = navigator.userAgent.toLowerCase(),
      isAndroid = u.indexOf('android') > -1 || u.indexOf('adr') > -1,
      isiOS = !!u.match(/\(i[^;]+;( u;)? cpu.+mac os x/),
      isPhone = isAndroid || isiOS,
      isWeixin = u.match(/microMessenger/i) == 'micromessenger' || u.match(/_sq_/i) == '_sq_'

  return {
    isWeixin, isPhone
  }
}

/**
 * 获取cookie
 * @param  {[type]} name [description]
 * @return {[type]}      [description]
 */
appCommonH.getCookie = function(name) {
  var arr, reg = new RegExp("(^| )" + name + "=([^;]*)(;|$)");
  if (arr = document.cookie.match(reg))
    return unescape(arr[2]);
  else
    return null;
}

/**
 * 计算时间差
 * @param {*} 
 */
appCommonH.getSpendTime = function (startTime, endTime) {
  startTime = startTime.replace(/\-/g, "/"); //兼容ios时间转换
  if (endTime != null) {
    endTime = endTime.replace(/\-/g, "/");
    var T = new Date(endTime).getTime() - new Date(startTime).getTime();
  }else{
    var T = new Date().getTime() - new Date(startTime).getTime();
  }
  
  var d = parseInt(T / 1000 / 60 / 60 / 24);
  var h = parseInt(T / 1000 / 60 / 60 % 24);
  var m = parseInt(T / 1000 / 60 % 60);

  if(d == 0 && h == 0 && m <= 0){
    return '一分钟以内'
  }
  if(d == 0 && h == 0){
    return m+'分钟'
  }
  if(d == 0){
    return h+'小时'+m+'分钟'
  }

  return d+'天'+h+'小时'+m+'分钟'
}

/**
 * 获取get参数
 * @param  {[type]} vm   [description]
 * @param  {[type]} name [description]
 * @return {[type]}      [description]
 */
appCommonH.query = function(name) {
  var reg = new RegExp("(^|&)" + name + "=([^&]*)(&|$)", "i"); 
  var r = window.location.search.substr(1).match(reg); 
  
  if (r != null) return unescape(r[2]); return null;
}

// 获取url中全部参数
appCommonH.getUrlParam = function (url) {
  var match = url.split('?')[1].split('#')[0];
  if (!match) return {};
  var matches = match.split('&');                      
  var obj = {};
  for (var i = 0; i < matches.length; i++) {
    var key = matches[i].split('=')[0];
    var value = matches[i].split('=')[1];
    obj[key] = value;
  }
  return obj;
}


/**
 * 获取当前页面所属客户端
 * @return {[type]} [description]
 */
appCommonH.getClientType = function(topath) {
  var appPath = topath.replace("/", "");

  if(appPath.substr(0, 2) == "m_") {
    return true;
  }

  return false;
}

/**
 * 获取当前站点风格
 * @param  {[type]} type [description]
 * @return {[type]}      [description]
 */
appCommonH.getWebStyle = function(type) {
  return appConfig[type] ? appConfig[type] : 'default';
}

/**
 * 获取指定时间指定格式
 * @param  {[type]} format [时间格式]
 * @param  {[type]} time   [使用时间，默认为当前时间]
 * @return {[type]}        [description]
 */
appCommonH.getStrTime = function(format, time) {
  var timeObj = time ? new Date(time) : new Date(),
      timeInfo = {};

  timeInfo["Y"] = timeObj.getFullYear();
  timeInfo["m"] = timeObj.getMonth() + 1;
  timeInfo["d"] = timeObj.getDate();
  timeInfo["H"] = timeObj.getHours();
  timeInfo["i"] = timeObj.getMinutes();
  timeInfo["s"] = timeObj.getSeconds();

  timeInfo["m"] = timeInfo["m"] >= 10 ? timeInfo["m"] : '0'+timeInfo["m"];
  timeInfo["d"] = timeInfo["d"] >= 10 ? timeInfo["d"] : '0'+timeInfo["d"];
  timeInfo["H"] = timeInfo["H"] >= 10 ? timeInfo["H"] : '0'+timeInfo["H"];
  timeInfo["i"] = timeInfo["i"] >= 10 ? timeInfo["i"] : '0'+timeInfo["i"];
  timeInfo["s"] = timeInfo["s"] >= 10 ? timeInfo["s"] : '0'+timeInfo["s"];

  for(var key in timeInfo) {
    format = format.replace(key, timeInfo[key]);
  }

  return format;
}


/**
 * 设置url参数
 * @param {[type]} url    [description]
 * @param {[type]} params [description]
 */
appCommonH.setGetUrl = function(url, params) {
  var paramsStr = "";

  for(var key in params) {
    paramsStr += key+"="+params[key]+"&";
  }

  if(paramsStr) {
    paramsStr = paramsStr.slice(0, -1);
  }

  return url+"?"+paramsStr;
}

/**
 * 关闭当前页面
 * @return {[type]} [description]
 */
appCommonH.closePage = function() {
  var browserName = navigator.appName;
  
  if (browserName == "Netscape") {
    window.open('', '_self', '');
    window.close();
  }

  if (browserName == "Microsoft Internet Explorer") {
    window.parent.opener = "whocares";
    window.parent.close();
  }  
}


if(!Vue.prototype.appCommonH) {
	Vue.prototype.appCommonH = appCommonH;
}

export default appCommonH;
