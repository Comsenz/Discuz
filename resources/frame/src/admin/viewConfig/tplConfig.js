/**
 * 模板配置
 */
import tplConfig from "../../template/default/viewConfig/tplConfig";         //获取配置信息

export default {
  /**
   * [路由器模板配置]
   * @type {Object}
   *
   * site为模块名，index为页面名称，拼接后路径为site/index
   */
  template: {
    ...tplConfig.template,
    admin:{
      // path:'/',
      // children:[
      //   {
      //     index:{
      //       comLoad: function (resolve) {
      //         require(['../view/site/IndexView'], resolve)
      //       },
      //       metaInfo: {
      //         title: '后台架子'
      //       }
      //     },
      //   },
      //   {
      //     home:{
      //       comLoad: function (resolve) {
      //         require(['../view/site/home/homeView'], resolve)
      //       },
      //       metaInfo: {
      //         title: '首页'
      //       }
      //     },
      //   }
      // ],

      index:{
        comLoad: function (resolve) {
          require(['../view/site/IndexView'], resolve)
        },
        children:{
          'home':{
            comLoad: function (resolve) {
              require(['../view/site/home/homeView'], resolve)
            },
            metaInfo: {
              title: '后台首页1'
            }
          },
          'login':{
            comLoad:function (resolve) {
              require(['../view/site/login/loginView'],resolve)
            },
            metaInfo:{
              title:'后台登录'
            }
          }
        },
        metaInfo: {
          title: '后台架子'
        }
      }

    }
  },

  /**
   * 不需要登陆的路径名称
   * @type {Array}
   */
  notNeedLogins: [
    "site/index",
    // 'login/loginView',
    ...tplConfig.notNeedLogins
  ]
};
