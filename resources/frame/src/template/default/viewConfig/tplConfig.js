/**
 * 模板配置
 */

export default {
  /**
   * [路由器模板配置]
   * @type {Object}
   *
   * site为模块名，index为页面名称，拼接后路径为site/index
   */
  template: {
    m_site:{
      index:{
        comLoad:function (resolve) {
          require(['../view/m_site/home/indexView'],resolve)
        },
        metaInfo:{
          title:"移动端首页"
        }
      },
      login:{
        comLoad:function (resolve) {
          require(['../view/m_site/login/loginView'],resolve)
        },
        metaInfo:{
          title:"移动端登录"
        }
      }
    },
    site: {
      index: {
        comLoad: function (resolve) {
          require(['../view/site/home/indexView'], resolve)
        },
        metaInfo: {
          title: '首页'
        }
      },

    }
  },

  /**
   * 不需要登陆的路径名称
   * @type {Array}
   */
  notNeedLogins: [
    "site/index",
    "m_site/index",
    "m_site/login"
  ]

};
