/**
 * 公共方法
 */

import Vue from "vue";
import appFetch from "axiosHelper.js";
import appConfig from "../../config/appConfig";
import moment from "moment/moment";

const appCommonH = {};

/**
 * 接口错误处理
 * @param  {[type]} data  [接口错误信息]
 * @param  {[type]} state [是否返回detail]
 * @return {[type]}       [description]
 */
appCommonH.errorHandling = function (data,state) {
  var errorList = [];

  data.forEach((item)=>{
    var errorDetail = '';
    if (state){
      item.detail.forEach((datail)=>{
        errorDetail = errorDetail + datail
      });
      errorList.push({
        code:item.code,
        status:item.status,
        errorDetail:errorDetail
      })
    } else {
      errorList.push({
        code:item.code,
        status:item.status,
      })
    }
  });

  return errorList;
}

/**
 * [根据模块名称调用模块方法]
 * @param  {[type]} _this [description]
 * @param  {[type]} data  [description]
 * @return {[type]}       [description]
 */
appCommonH.apiCallBack = function(_this, data) {
    for(var key in data) {
      if(_this[key+"CallBack"]) {
          _this[key+"CallBack"](data[key]);
      }
    }
}

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
      isWeixin = u.match(/microMessenger/i) == 'micromessenger',
      isPc = u.match(/(phone|pad|pod|iPhone|iPod|ios|iPad|Android|Mobile|BlackBerry|IEMobile|MQQBrowser|JUC|Fennec|wOSBrowser|BrowserNG|WebOS|Symbian|Windows Phone)/i)
      // if(isWeixin == true){
      //   alert('weixin');
      // } else if(isAndroid == true || isiOS == true){
      //   alert('isAndroid');
      // } else {
      //   alert('isPc');
      // }
  return {
    isWeixin,isPhone,isPc
  }
}
/**
 * 是否是WeLink
 * @return {Boolean} [description]
 */
appCommonH.isWeLink = function(){
    var isWeLink = navigator.userAgent.indexOf('HuaWei-AnyOffice') > -1
    return {
        isWeLink
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


appCommonH.timeago = function(dateTimeStamp){

  // dateTimeStamp是一个时间毫秒，注意时间戳是秒的形式，在这个毫秒的基础上除以1000，就是十位数的时间戳。13位数的都是时间毫秒。

  var minute=1000*60;      //把分，时，天，周，半个月，一个月用毫秒表示

  var  hour=minute*60;

  var day=hour*24;

  var week=day*7;

  var halfamonth=day*15;

  var month=day*30;



  var  now=new Date().getTime();   //获取当前时间毫秒

  var diffValue=now - dateTimeStamp;//时间差



  if(diffValue<0){return;}

  var  minC=diffValue / minute;  //计算时间差的分，时，天，周，月

  var  hourC=diffValue / hour;

  var  dayC=diffValue / day;

  var  weekC=diffValue / week;

  var  monthC=diffValue / month;


  var result;
  if(monthC>=1){
      result="" + parseInt(monthC) + "月前";
  } else if(weekC>=1){
      result="" + parseInt(weekC) + "周前";
  } else if(dayC>=1){
      result=""+ parseInt(dayC) +"天前";
  } else if(hourC>=1){
      result=""+ parseInt(hourC) +"小时前";
  } else if(minC>=1){

    result=""+ parseInt(minC) +"分钟前";

  } else{

    result="刚刚";
  }
  return result;
}

/**
* 返回根据主题类型和是否付费显示图标
* @param {[type]} item [主题]
* */
appCommonH.titleIcon = function(item){
  let icon = '';

  if (parseInt(item.price) > 0){
    icon = 'iconmoney';
  } else if (item.type === 1){
    icon = 'iconchangwen'
  } else {
    icon = '';
  }

  return icon;
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
};

/**
 * 格式化日期
 * @param {[type]} data    [description]
 * @param {[type]} format [description]
 */
appCommonH.formatDate = function(data,format){
  return moment(data).format('YYYY-MM-DD HH:mm')
};

/**
 * 设置页面标题
 * @param {[type]} type [页面：(detail:详情，circle:首页)]
 * @param {[data]} data [主题数据]
 * @param {[title]} title [页面标题]
 * */
appCommonH.setPageTitle = function (type,data,title) {

  if (type === 'detail'){
    switch (data.readdata._data.type) {
      case 0:
        title = data.readdata.firstPost._data.content.slice(0,80) + ' - Powered by Discuz! Q';
        break;
      case 1:
        title = data.readdata._data.title + ' - Powered by Discuz! Q';
        break;
      case 2:
        title = data.readdata.firstPost._data.content.slice(0,80) + ' - Powered by Discuz! Q';
        break;
      default:
        title = '主题详情页 - Powered by Discuz! Q';
    }
  } else if (type === 'circle'){
    title =  data.readdata._data.set_site.site_name + ' - Powered by Discuz! Q';
  } else {
    title = title;
  }

  return document.title = title;
};



if(!Vue.prototype.appCommonH) {
	Vue.prototype.appCommonH = appCommonH;
}

export default appCommonH;
