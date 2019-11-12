// vuex 入口文件

import Vue from "vue";
import Vuex from "vuex";
Vue.use(Vuex);

import siteModule from "./site/index";
import loginModule from './login/index';
import appSiteModule from '../../template/default/store';

//实例化vuex
const appStore = new Vuex.Store({
	modules: {
    site: siteModule,
    login: loginModule,
    appSiteModule:appSiteModule
	}
});

//热加载模块
if (module.hot) {
  module.hot.accept(['./site/index', './login/index', '../../template/default/store'], () => {
    const newSiteModule = require('./site/index').default;
    const newLoginModule = require('./login/index').default;
    const newAppSiteModule = require( '../../template/default/store').default;

    // 加载新模块
    appStore.hotUpdate({
      modules: {
        site: newSiteModule,
        login: newLoginModule,
        appSiteModule: newAppSiteModule
      }
    });

  })
}

export default appStore;
