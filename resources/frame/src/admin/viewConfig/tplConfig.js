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

    //后台模块
    admin_site:{
      admin:{
        comLoad: function (resolve) {
          require(['../view/site/IndexView'], resolve)
        },
        children:{
          'home':{
            comLoad: function (resolve) {
              require(['../view/site/home/homeView'], resolve)
            },
            metaInfo: {
              title: '后台首页'
            }
          },
          'site-set':{
            comLoad: function (resolve) {
              require(['../view/site/global/siteSetView'], resolve)
            },
            metaInfo: {
              title: '站点设置'
            }
          },
          'sign-up':{
            comLoad: function (resolve) {
              require(['../view/site/global/signUpSetView'], resolve)
            },
            metaInfo: {
              title: '注册设置'
            }
          },
          'worth-mentioning-set': {
            comLoad: function (resolve) {
              require(['../view/site/global/worthMentioningSetView'], resolve)
            },
            metaInfo: {
              title: '第三方登录设置'
            }
          },
          'pay-set': {
            comLoad: function (resolve) {
              require(['../view/site/global/paySetView'], resolve)
            },
            metaInfo: {
              title: '支付设置'
            }
          },
          'tencent-cloud-set': {
            comLoad: function (resolve) {
              require(['../view/site/global/tencentCloudSetView'], resolve)
            },
            metaInfo: {
              title: '腾讯云设置'
            }
          },
          'annex-set':{
            comLoad: function (resolve) {
              require(['../view/site/global/annexSetView'], resolve)
            },
            metaInfo: {
              title: '附件设置'
            }
          },
          'content-filter-set':{
            comLoad: function (resolve) {
              require(['../view/site/global/contentFilteringSetView'], resolve)
            },
            metaInfo: {
              title: '内容过滤设置'
            }
          },
          'user-manage-set':{
            comLoad: function (resolve) {
              require(['../view/site/global/userManageSetView'], resolve)
            },
            metaInfo: {
              title: '后台用户管理'
            }
          },
          'role-manage-set':{
            comLoad: function (resolve) {
              require(['../view/site/global/roleManageSetView'], resolve)
            },
            metaInfo: {
              title: '后台角色管理'
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
