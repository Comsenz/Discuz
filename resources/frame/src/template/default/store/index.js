// vuex 入口文件

import Vue from "vue";
import Vuex from "vuex";
Vue.use(Vuex);

import siteModule from "./site/index";

//实例化vuex
const appStore = new Vuex.Store({
	modules: {
    site: siteModule
	}
});

//热加载模块
if (module.hot) {
  module.hot.accept(['./site/index'], () => {
    const newSiteModule = require('./site/index').default;

    // 加载新模块
    appStore.hotUpdate({
      modules: {
        site: newSiteModule
      }
    });

  })
}

export default appStore;