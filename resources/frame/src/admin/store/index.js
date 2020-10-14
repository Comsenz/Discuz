// vuex 入口文件

import Vue from "vue";
import Vuex from "vuex";
import siteModule from "./site/index";
import loginModule from './login/index';
import adminModule from './admin/index';

Vue.use(Vuex);

//实例化vuex
const appStore = new Vuex.Store({
	modules: {
    site: siteModule,
    login: loginModule,
    admin:adminModule
	}
});

//热加载模块
if (module.hot) {
  module.hot.accept(['./site/index', './login/index', './admin/index'], () => {
    const newSiteModule = require('./site/index').default;
    const newLoginModule = require('./login/index').default;
    const newAmindModule = require('./admin/index').default;

    // 加载新模块
    appStore.hotUpdate({
      modules: {
        site: newSiteModule,
        login: newLoginModule,
        adminModule:newAmindModule
      }
    });

  })
}

export default appStore;
