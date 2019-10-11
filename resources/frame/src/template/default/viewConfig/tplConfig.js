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
    site: {
      index: {
        comLoad: function (resolve) {
          require(['../view/site/indexView'], resolve)
        },
        metaInfo: {
          title: 'pc首页'
        },
        css: [],
        js: ['/js/testselfjsload.js']
      },
      
    },
    m_site: {
      m_index: {
        comLoad: function (resolve) {
          require(['../view/m_site/indexView'], resolve)
        },
        metaInfo: {
          title: 'mobile首页'
        },
        css: [],
        js: []
      }
    }
  },

  /**
   * 每个页面都需要加载的js
   * @type {Array}
   */
  publicJs: ['/js/testplulicjsload.js'],

  /**
   * 需要登陆的路径名称
   * @type {Array}
   */
  needLogins: [
    "site/index", 
    "m_site/m_index"
  ]

};
