// The Vue build version to load with the `import` command
// (runtime-only or standalone) has been set in webpack.base.conf with an alias.

import Vue from 'vue';
import "babel-polyfill";
import 'amfe-flexible/index.js'
Vue.config.devtools = true;
//将jquery 放入全局变量
import jQuery from "jquery";
window.$ = jQuery;

import 'vant/lib/index.css'          //引入vant样式
import './template/default/less/m_site/modules/publicIndex.less' //引入公共样式
import './template/default/less/m_site/modules/publicIndexB.less' //引入B公共样式
import './extend/viewBase/vantuiInit';   //引入vant组件
import '../static/js/rem'   //引入Rem配置

import 'element-ui/lib/theme-chalk/index.css'; //引入element样式
import './extend/viewBase/elementuiInit'; //引入element组件
import './admin/scss/modules/element-variables.scss'  //引入主题样式

import '../static/css/reset.css'; //引入清除浏览器默认样式CSS

import appConfigInit from "../config/appConfigInit";			//appConfig 对象进一步处理加工，如放在vue原型中
import axiosHelper from "axiosHelper";							//ajax 请求封装
import commonHelper from "commonHelper";						//公共函数封装
import appStore from "./admin/store/index";							//vuex 初始化

import commonHeader from './template/default/view/m_site/common/loginSignUpHeader/loginSignUpHeader.vue';
Vue.component('commonHeader', commonHeader);

/* start 设置引入的模板路径 start */
import RConfig from "./admin/viewConfig/tpl";					//获取路由对象
/* end 设置引入的模板路径 end */

import axios from 'axios';
Vue.prototype.axios = axios;


//实例化根目录
const appRouter = RConfig.init();
const App = new Vue({
  	router: appRouter,
  	store: appStore,
  	template: '<router-view></router-view>'
}).$mount('#app');
