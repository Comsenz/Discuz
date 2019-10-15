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
    global: {
      index: {
        comLoad: function (resolve) {
          require(['../view/global/indexView'], resolve)
        },
        metaInfo: {
          title: '后台首页'
        }
      }, 
    },
    ...tplConfig.template
  },

  /**
   * 不需要登陆的路径名称
   * @type {Array}
   */
  notNeedLogins: [
    "site/index", 
    "m_site/m_index",
    ...tplConfig.notNeedLogins
  ]
};
