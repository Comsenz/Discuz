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

import Echarts from 'echarts'; //引入Echarts

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
Vue.prototype.$echarts = Echarts; //  


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

const keepAliveUrl = ['circle'];

const noKeepAliveUrl = ['login-user','my-notice','modify-data','my-wallet','my-collection','my-follow'];

const noKeepAliveUrl2 = ['details/:themeId'];


const App = new Vue({
  router: appRouter,
  store: appStore,
  moment: moment,
  data:function(){
    return {
      keepAliveStatus:false,
      status:0
    }
  },
  watch: {
    '$route': function(to, from) {
      console.log(from);
      console.log(from.name);
      console.log(to.name);

      if (noKeepAliveUrl.includes(from.name)) {
        this.keepAliveStatus = false;
      } else if (keepAliveUrl.includes(to.name)) {
        this.keepAliveStatus = true;
      } else {
        this.keepAliveStatus = false;
      }


      /*if (!noKeepAliveUrl2.includes(from.name)) {
        console.log(111111111);
        this.keepAliveStatus = false;
      } else if (keepAliveUrl.includes(to.name)) {
        console.log(22222222222);
        this.keepAliveStatus = true;
      } else {
        console.log(333333333333);
        this.keepAliveStatus = false;
      }*/



      // let name = this.$route.name;
      /*if (this.status !== 0){
        console.log('不等于0');

        if (!keepAliveUrl.includes(to.name)){
          this.keepAliveStatus = false;
          console.log('进入缓存的页面');

          if (name === 'circle') {
            // alert('要去首页');
            // this.keepAliveStatus = true;
            // this.$destroy();
            // this.status = 0;
          }
        }

        if (from.name === 'details/:themeId') {
          console.log('从详情页面回来');
          this.keepAliveStatus = true;
        }

      }


      //判断是不是第一次进入的是首页
      if (to.name === 'circle' && this.status === 0){
        this.status = 1;
        this.keepAliveStatus = true;
        console.log('第一次进首页缓存');
      }*/


    }
  },
  template:'<div style="width: 100%;height: 100%"><keep-alive><router-view v-if="keepAliveStatus"></router-view></keep-alive><router-view v-if="!keepAliveStatus"></router-view></div>'
}).$mount('#app');
window.app = App;
