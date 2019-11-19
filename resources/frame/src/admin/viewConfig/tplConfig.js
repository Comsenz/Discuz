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
              title: '管理中心首页',
              name:'controlCenter',
              attribution:'首页'
            }
          },
          'site-set':{
            comLoad: function (resolve) {
              require(['../view/site/global/siteSetView'], resolve)
            },
            metaInfo: {
              title: '站点设置',
              name:'siteSet',
              attribution:'全局'
            }
          },
          'sign-up-set':{
            comLoad: function (resolve) {
              require(['../view/site/global/signUpSetView'], resolve)
            },
            metaInfo: {
              title: '注册设置',
              name:'signUpSet',
              attribution:'全局'
            }
          },
          'worth-mentioning-set': {
            comLoad: function (resolve) {
              require(['../view/site/global/worthMentioningSetView'], resolve)
            },
            metaInfo: {
              title: '第三方登录设置',
              name:'worthMentioningSet',
              attribution:'全局'
            }
          },
          'pay-set': {
            comLoad: function (resolve) {
              require(['../view/site/global/paySetView'], resolve)
            },
            metaInfo: {
              title: '支付设置',
              name:'paySet',
              attribution:'全局'
            }
          },
          'tencent-cloud-set': {
            comLoad: function (resolve) {
              require(['../view/site/global/tencentCloudSetView'], resolve)
            },
            metaInfo: {
              title: '腾讯云设置',
              name:'tencentCloudSet',
              attribution:'全局'
            }
          },
          'annex-set':{
            comLoad: function (resolve) {
              require(['../view/site/global/annexSetView'], resolve)
            },
            metaInfo: {
              title: '附件设置',
              name:'annexSet',
              attribution:'全局'
            }
          },
          'content-filter-set':{
            comLoad: function (resolve) {
              require(['../view/site/global/contentFilteringSetView'], resolve)
            },
            metaInfo: {
              title: '内容过滤设置',
              name:'contentFilteringSet',
              attribution:'全局'
            }
          },
          'user-manage-set':{
            comLoad: function (resolve) {
              require(['../view/site/global/userManageSetView'], resolve)
            },
            metaInfo: {
              title: '后台用户管理',
              ame:'userManage',
              attribution:'全局'
            }
          },
          'role-manage-set':{
            comLoad: function (resolve) {
              require(['../view/site/global/roleManageSetView'], resolve)
            },
            metaInfo: {
              title: '后台角色管理',
              name:'roleManage',
              attribution:'全局'
            }
          },

          //内容分类
          'cont-class':{
            comLoad: function (resolve) {
              require(['../view/site/cont/contClassView'], resolve)
            },
            metaInfo: {
              title: '内容分类',
              name:'contClass',
              attribution:'内容'
            }
          },

          'cont-manage':{
            comLoad: function (resolve) {
              require(['../view/site/cont/contManageView'], resolve)
            },
            metaInfo: {
              title: '内容管理',
              name:'contManage',
              attribution:'内容',
              alias:'最新主题'
            }
          },
          'cont-manage/search':{
            comLoad: function (resolve) {
              require(['../view/site/cont/contManageSearchView'], resolve)
            },
            metaInfo: {
              title: '内容管理',
              name:'contManage',
              attribution:'内容',
              alias:'搜索'
            }
          },

          'cont-review':{
            comLoad: function (resolve) {
              require(['../view/site/cont/contReviewView'], resolve)
            },
            metaInfo: {
              title: '内容审核',
              name:'contReview',
              attribution:'内容',
              alias:'主题审核'
            }
          },
          'reply-review':{
            comLoad: function (resolve) {
              require(['../view/site/cont/replyReviewView'], resolve)
            },
            metaInfo: {
              title: '回复审核',
              name:'contReview',
              attribution:'内容',
              alias:'回复审核'
            }
          },

          'recycle-bin':{
            comLoad: function (resolve) {
              require(['../view/site/cont/recycleBinView'], resolve)
            },
            metaInfo: {
              title: '回收站',
              name:'recycleBin',
              attribution:'内容',
              alias:'主题'
            }
          },
          'recycle-bin-reply':{
            comLoad: function (resolve) {
              require(['../view/site/cont/recycleBinReplyView'], resolve)
            },
            metaInfo: {
              title: '回收站',
              name:'recycleBin',
              attribution:'内容',
              alias:'回帖'
            }
          }

        },
        metaInfo: {
          title: '后台架子'
        }
      },
      'admin/login':{
        comLoad:function (resolve) {
          require(['../view/site/login/loginView'],resolve)
        },
        metaInfo:{
          title:'后台登录'
        }
      }

    }
  },

  /**
   * 不需要登陆的路径名称
   * @type {Array}
   */
  notNeedLogins: [
    "admin/login",
    ...tplConfig.notNeedLogins
  ]
};
