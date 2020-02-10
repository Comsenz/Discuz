// The Vue build version to load with the `import` command
// (runtime-only or standalone) has been set in webpack.base.conf with an alias.

import Vue from 'vue';
import "babel-polyfill";
import 'amfe-flexible/index.js'
//将jquery 放入全局变量
import jQuery from "jquery";
window.$ = jQuery;
import "../config/languageConfig";

import 'vant/lib/index.css'          //引入vant样式
import './extend/viewBase/vantuiInit';   //引入vant组件

import 'element-ui/lib/theme-chalk/index.css'; //引入element样式
import './extend/viewBase/elementuiInit'; //引入element组件

import '../static/css/reset.css'; //引入清除浏览器默认样式CSS

import appConfigInit from "../config/appConfigInit";			//appConfig 对象进一步处理加工，如放在vue原型中
import axiosHelper from "axiosHelper";							//ajax 请求封装
import commonHelper from "commonHelper";						//公共函数封装
import appStore from "./admin/store/index";							//vuex 初始化
import moment from 'moment';                  //导入文件 momnet时间转换
import utils from "./common/urlGet";         //获取url参数
import VueLazyload from 'vue-lazyload';       //图片懒加载
import lrz from 'lrz';     //图片压缩

import wx from 'weixin-js-sdk';
Vue.prototype.$wx = wx;


import filters from "./common/filters";   //过滤器
import commonHeader from './template/default/view/m_site/common/loginSignUpHeader/loginSignUpHeader.vue';
Vue.component('commonHeader', commonHeader);

/* start 设置引入的模板路径 start */
import RConfig from "./admin/viewConfig/tpl";					//获取路由对象
/* end 设置引入的模板路径 end */

import axios from 'axios';
Vue.prototype.axios = axios;
Vue.prototype.$moment = moment;//时间转换-赋值使用
Vue.config.devtools = true;
moment.locale('zh-cn');//时间转换-需要汉化

Vue.use(VueLazyload, {
  // loading: require('img/loading.png'),//加载中图片，一定要有，不然会一直重复加载占位图
  // error: require('img/error.png')  //加载失败图片
});
Vue.prototype.$utils = utils; //注册全局方法


//实例化根目录
// const appRouter = RConfig.init();
// const App = new Vue({
//   	router: appRouter,
//   	store: appStore,
//     moment: moment,
//   	template: '<router-view></router-view>'
// }).$mount('#app');

window.app = App;//实例化根目录
const appRouter = RConfig.init();

const urlData = ['circle'];

const App = new Vue({
  router: appRouter,
  store: appStore,
  moment: moment,
  data:function(){
    return {
      keepAliveStatus:false,
    }
  },
  watch: {
    '$route': function(to, from) {
      this.keepAliveStatus = urlData.includes(to.name);
    }
  },
  template:'<div style="width: 100%;height: 100%"><keep-alive><router-view v-if="keepAliveStatus"></router-view></keep-alive><keep-alive><router-view v-if="!keepAliveStatus"></router-view></keep-alive></div>'
}).$mount('#app');
window.app = App;
