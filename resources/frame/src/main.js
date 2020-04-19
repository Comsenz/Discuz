// The Vue build version to load with the `import` command
// (runtime-only or standalone) has been set in webpack.base.conf with an alias.

import Vue from 'vue';
import "babel-polyfill";
import 'amfe-flexible/index.js'
//将jquery 放入全局变量
import jQuery from "jquery";
window.$ = jQuery;
import "../config/languageConfig";

import 'vant/lib/index.css';             //引入vant样式
import './extend/viewBase/vantuiInit';   //引入vant组件
// import '@vant/touch-emulator';           //引入vant桌面配置

//import 'element-ui/lib/theme-chalk/index.css'; //引入element样式
//import './extend/viewBase/elementuiInit'; //引入element组件

//import Echarts from 'echarts'; //引入Echarts

import VueI18n from 'vue-i18n'
Vue.use(VueI18n);

const i18n = new VueI18n({
  locale: "zh", // 定义默认语言为中文
  messages: {
    zh: require("../static/languages/zh.json"),
    en: require("../static/languages/en.json")
  }
});

import '../static/css/reset.css'; //引入清除浏览器默认样式CSS
// import '../../frame/src/template/default/controllers/m_site/common/tcaptcha'; //引入腾讯验证码

import appConfigInit from "../config/appConfigInit";			//appConfig 对象进一步处理加工，如放在vue原型中
//import axiosHelper from "axiosHelper";							//ajax 请求封装
//import commonHelper from "commonHelper";						//公共函数封装

import browserDb from "webDbHelper";						//公共函数封装
import appStore from "./admin/store/index";							//vuex 初始化
import moment from 'moment';                  //导入文件 momnet时间转换
import utils from "./common/urlGet";         //获取url参数
import VueLazyload from 'vue-lazyload';       //图片懒加载
//import lrz from 'lrz';     //图片压缩


import wx from 'weixin-js-sdk';
Vue.prototype.$wx = wx;

import welinkH5 from '../static/js/hwh5-cloudonline';
Vue.prototype.$welinkH5 = welinkH5;

import filters from "./common/filters";   //过滤器
import commonHeader from './template/default/view/m_site/common/loginSignUpHeader/loginSignUpHeader.vue';
Vue.component('commonHeader', commonHeader);


/* start 设置引入的模板路径 start */
//import RConfig from "./admin/viewConfig/tpl";					//获取路由对象
import RConfig from "./template/default/viewConfig/tpl";					//获取路由对象

/* end 设置引入的模板路径 end */

import axios from 'axios';
Vue.prototype.axios = axios;
Vue.prototype.$moment = moment;//时间转换-赋值使用
Vue.config.devtools = false;
moment.locale('zh-cn');//时间转换-需要汉化

Vue.use(VueLazyload, {
  // loading: require('img/loading.png'),//加载中图片，一定要有，不然会一直重复加载占位图
  // error: require('img/error.png')  //加载失败图片
});
Vue.prototype.$utils = utils; //注册全局方法
//Vue.prototype.$echarts = Echarts; //后台财务统计echarts图标
let app = {};

app.bus = new Vue(); //后台财务统计echarts图标

// Vue.use(tacptcha)
//实例化根目录
// const appRouter = RConfig.init();
// const App = new Vue({
//   	router: appRouter,
//   	store: appStore,
//     moment: moment,
//   	template: '<router-view></router-view>'
// }).$mount('#app');

const appRouter = RConfig.init();

const keepAliveUrl = ['circle'];

// const noKeepAliveUrl = ['login-user','my-notice','modify-data','my-wallet','my-collection','my-follow','login-phone'];

const noKeepAliveUrl2 = ['details/:themeId', 'home-page/:userId', 'login-user'];

browserDb.setSItem('homeStatus', 1);
// const Authorization = browserDb.getLItem('Authorization');
// const tokenId = browserDb.getLItem('tokenId');

const App = new Vue({
  router: appRouter,
  store: appStore,
  moment: moment,
  i18n,
  data: function () {
    return {
      keepAliveStatus: false,
      status: 0,
      siteInfoStat: '',
      safescripts: {},
      evalscripts: [],
      JSLOADED: [],
    }
  },
  created() {
    app.bus.$on('stat', (arg) => {
      this.siteInfoStat = arg;
    })

  },

  watch: {
    '$route': function (to, from) {
      // const Authorization = browserDb.getLItem('Authorization');
      // const tokenId = browserDb.getLItem('tokenId');

      if (!noKeepAliveUrl2.includes(from.name) && from.name !== null) {
        this.keepAliveStatus = false;
      } else if (keepAliveUrl.includes(to.name)) {
        this.keepAliveStatus = true;
      } else {
        this.keepAliveStatus = false;
        browserDb.setSItem('homeStatus', 2);
      }

    },
    'siteInfoStat': function (newVal, oldVal) {
      this.siteInfoStat = newVal;
      this.evalscript(this.siteInfoStat);
    }
  },
  methods: {
    evalscript(s) {
      if (s.indexOf('<script') == -1) return s;
      var p = /<script[^\>]*?>([^\x00]*?)<\/script>/ig;
      var arr = [];
      while (arr = p.exec(s)) {
        var p1 = /<script[^\>]*?src=\"([^\>]*?)\"[^\>]*?(reload=\"1\")?(?:charset=\"([\w\-]+?)\")?><\/script>/i;
        var arr1 = [];
        arr1 = p1.exec(arr[0]);
        if (arr1) {
          this.appendscript(arr1[1], '', arr1[2], arr1[3]);
        } else {
          p1 = /<script(.*?)>([^\x00]+?)<\/script>/i;
          arr1 = p1.exec(arr[0]);
          this.appendscript('', arr1[2], arr1[1].indexOf('reload=') != -1);
        }
      }
      return s;
    },

    //  var safescripts = {}, evalscripts = [];

    appendscript(src, text, reload, charset) {
      var id = this.hash(src + text);
      if (!reload && this.in_array(id, this.evalscripts)) return;
      if (reload && $('#' + id)[0]) {
        $('#' + id)[0].parentNode.removeChild($('#' + id)[0]);
      }

      this.evalscripts.push(id);
      var scriptNode = document.createElement("script");
      scriptNode.type = "text/javascript";
      scriptNode.id = id;
      scriptNode.charset = charset ? charset : (!document.charset ? document.characterSet : document.charset);
      try {
        if (src) {
          scriptNode.src = src;
          scriptNode.onloadDone = false;
          scriptNode.onload = () => {
            scriptNode.onloadDone = true;
            this.JSLOADED[src] = 1;
          };
          scriptNode.onreadystatechange = function () {
            if ((scriptNode.readyState == 'loaded' || scriptNode.readyState == 'complete') && !scriptNode.onloadDone) {
              scriptNode.onloadDone = true;
              this.JSLOADED[src] = 1;
            }
          };
        } else if (text) {
          scriptNode.text = text;
        }
        document.getElementsByTagName('head')[0].appendChild(scriptNode);
      } catch (e) { }
    },
    hash(string, length) {
      var length = length ? length : 32;
      var start = 0;
      var i = 0;
      var result = '';
      var filllen;
      filllen = length - string.length % length;
      for (i = 0; i < filllen; i++) {
        string += "0";
      }
      while (start < string.length) {
        result = this.stringxor(result, string.substr(start, length));
        start += length;
      }
      return result;
    },
    stringxor(s1, s2) {
      var s = '';
      var hash = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
      var max = Math.max(s1.length, s2.length);
      for (var i = 0; i < max; i++) {
        var k = s1.charCodeAt(i) ^ s2.charCodeAt(i);
        s += hash.charAt(k % 52);
      }
      return s;
    },
    in_array(needle, haystack) {
      if (typeof needle == 'string' || typeof needle == 'number') {
        for (var i in haystack) {
          if (haystack[i] == needle) {
            return true;
          }
        }
      }
      return false;
    },

  },
  template: '<div style="width: 100%;height: 100%"><keep-alive><router-view v-if="keepAliveStatus"></router-view></keep-alive><router-view v-if="!keepAliveStatus"></router-view></div>'
}).$mount('#app');

window.app = app;//实例化根目录
