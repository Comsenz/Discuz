/**
 * 模板配置文件 base类
 * @type {[type]}
 */
var path = require("path");
import Vue from "vue";
import VueRouter from "vue-router";
import commonHelper from "commonHelper";
Vue.use(VueRouter);

const appConfig = Vue.prototype.appConfig;
const baseTpl = function(params) {
  this.Router = null;

  this.template = params.template ? params.template : null;

  this.publicCss = params.publicCss ? params.publicCss : [];
  this.publicJs = params.publicJs ? params.publicJs : [];
  this.styleCss = params.styleCss ? params.styleCss : {};

  this.needLogins = params.needLogins ? params.needLogins : [];
  this.ctype = "";
};

/**
 * 检查配置
 * @return {[type]} [description]
 */
baseTpl.prototype.checkConfig = function() {
  if (!this.template || !this.checkTemplate()) {
    //template 模板配置错误
    console.error("template 模板配置错误！");

    return false;
  }

  return true;
};

/**
 * 检查模板配置
 * @return {[type]} [description]
 */
baseTpl.prototype.checkTemplate = function() {
  var pageNum = 0;

  for (var moduleName in this.template) {
    var moduleInfo = this.template[moduleName];

    for (var pageName in moduleInfo) {
      if (["js", "css"].includes(pageName)) continue;

      var pageInfo = moduleInfo[pageName];

      if (!pageInfo.comLoad || typeof pageInfo.comLoad != "function") {
        console.error(
          moduleName + "模块， " + pageName + "页面，comLoad函数设置错误！"
        );

        return false;
      }

      if (!pageInfo.metaInfo || !pageInfo.metaInfo.title) {
        console.error(
          moduleName + "模块， " + pageName + "页面，metainfo设置错误！"
        );

        return false;
      }

      pageNum++;
    }
  }

  if (!pageNum) {
    console.error("最少应该有一个页面！");

    return false;
  }

  return true;
};

/**
 * 实例化路由
 * @param  {[array]} routes [路由配置]
 * @return {[object]}        [路由对象]
 */
baseTpl.prototype.getBaseRouter = function(routes) {
  if (this.Router) {
    return this.Router;
  } else {
    //实例化路由
    this.Router = new VueRouter({
      mode: "history",
      routes: routes,
      base: appConfig.siteBasePath,
      scrollBehavior: function(to, from, savedPosition) {
        if (savedPosition) {
          return savedPosition;
        } else {
          return { x: 0, y: 0 };
        }
      }
    });

    //each 结束后加载完成进度条
    // var _this = this;
    this.Router.afterEach(function() {});

    //基本信息加载
    //this.progressStart();
    this.loadMetaInfo(this.Router);
    this.progressEnd();

    return this.Router;
  }
};

baseTpl.prototype.mergeArr = function(one, two) {
  one = one ? one : [];
  two = two ? two : [];

  return [...one, ...two];
};

/**
 * 支持的三大模块
 * @type {Array}
 */
baseTpl.prototype.modules = ["m_site", "p_site", "admin_site"];

/**
 * 加载路由和模板页面
 * @return {[type]} [description]
 */
baseTpl.prototype.loadRouter = function() {
  if (!this.checkConfig()) return false;

  var routes = [],
    template = this.template,
    defaultView = null,
    _this = this;

  for (var folder in template) {
    if (!this.modules.includes(folder)) continue;

    var nowModules = template[folder];
    for (var mName in nowModules) {
      if (["js", "css"].includes(mName)) continue;

      var newChildrenList = [];
      for (var childrenName in nowModules[mName].children) {
        var newChildren = {
          name: nowModules[mName].children[childrenName].metaInfo.title,
          path: "/" + mName + "/" + childrenName,
          component: nowModules[mName].children[childrenName]["comLoad"],
          meta: {
            ...nowModules[mName].children[childrenName]["metaInfo"],
            css: this.mergeArr(
              nowModules[mName].children[childrenName]["css"],
              nowModules["css"]
            ),
            js: this.mergeArr(
              nowModules[mName].children[childrenName]["js"],
              nowModules["js"]
            ),
            isMobile: folder === "m_site",
            isAdmin: folder === "admin_site"
          }
        };

        newChildrenList.push(newChildren);
      }

      if (newChildrenList.length) {
        var defaultChildren = {};

        defaultChildren.name = newChildrenList[0].name;
        defaultChildren.path = "";
        defaultChildren.component = newChildrenList[0].component;
        defaultChildren.meta = newChildrenList[0].meta;
        newChildrenList.unshift(defaultChildren);
      }

      var nowRouterInfo = {
        name: mName,
        path: "/" + mName,
        children: newChildrenList,
        component: nowModules[mName]["comLoad"],
        meta: {
          ...nowModules[mName]["metaInfo"],
          css: this.mergeArr(nowModules[mName]["css"], nowModules["css"]),
          js: this.mergeArr(nowModules[mName]["js"], nowModules["js"]),
          isMobile: folder === "m_site",
          isAdmin: folder === "admin_site"
        }
      };

      routes.push(nowRouterInfo);
    }
  }

  //设置默认加载页面
  defaultView = { ...{}, ...routes[0] };
  defaultView.path = "*";
  routes.push(defaultView);
  return this.getBaseRouter(routes);
};

/**
 * 路由访问时加载元信息
 * @return {[type]} [description]
 */
baseTpl.prototype.loadMetaInfo = function(Router) {
  Router.beforeEach(function(to, from, next) {
    if (to.meta.title) document.title = to.meta.title;
    if (to.meta.desc) document.desc = to.meta.desc;

    next();
  });
};

//资源列表
baseTpl.prototype.sourceArrs = {};

//资源加载总数
baseTpl.prototype.loadAllNum = 0;

//加载成功会掉方法
baseTpl.prototype.sourceCallBack = null;


/**
 * 获取页面类型
 * @param  {[type]} to [description]
 * @return {[type]}    [description]
 */
baseTpl.prototype.getClientClass = function(to) {
  return to.meta.isMobile ? "mobile" : "pc";
};

/**
 * 清除非当前类型客户端残留
 * @return {[type]} [description]
 */
baseTpl.prototype.clearOtherClientStyle = function() {
  var nowClient = commonHelper.isWeixin().isPhone ? "mobile" : "pc";
  if (this.ctype != nowClient) return false;

  var elem = document.querySelector(
    '[data-type="' + (this.ctype == "pc" ? "mobile" : "pc") + '"]'
  );
  if (elem) {
    elem.parentNode.removeChild(elem);
  }
  //手机转pc 端去掉rem 设置
  if (this.ctype == "pc") {
    document.documentElement.style = "";
  }
};

/**
 * 模块加载前，回调函数
 * @param  {[type]} Router [description]
 * @return {[type]}        [description]
 */
baseTpl.prototype.beforeEnterModule = function(Router) {};

/**
 * 结束显示进度条
 * @return {[type]} [description]
 */
baseTpl.prototype.progressEnd = function() {
  var elem = document.querySelector(".frame-loader");
  if (elem) {
    elem.parentNode.removeChild(elem);
  }
};

export default baseTpl;
