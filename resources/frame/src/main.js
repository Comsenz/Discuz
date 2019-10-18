// The Vue build version to load with the `import` command
// (runtime-only or standalone) has been set in webpack.base.conf with an alias.

import Vue from 'vue';
import "babel-polyfill";

//将jquery 放入全局变量
import jQuery from "jquery";
window.$ = jQuery;

import "weui";
import appConfigInit from "../config/appConfigInit";			//appConfig 对象进一步处理加工，如放在vue原型中
import axiosHelper from "axiosHelper";							//ajax 请求封装
import commonHelper from "commonHelper";						//公共函数封装
import appStore from "./admin/store/index";							//vuex 初始化

/* start 设置引入的模板路径 start */
import RConfig from "./admin/viewConfig/tpl";					//获取路由对象
/* end 设置引入的模板路径 end */

//实例化根目录
const appRouter = RConfig.init();
const App = new Vue({
  	router: appRouter,
  	store: appStore,
  	template: '<router-view></router-view>'
}).$mount('#app');
