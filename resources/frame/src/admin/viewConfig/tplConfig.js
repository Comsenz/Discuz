/**
 * 模板配置
 */
import tplConfig from "../../template/default/viewConfig/tplConfig";         //获取配置信息
import webDb from '../../helpers/webDbHelper';
import appFetch from '../../helpers/axiosHelper';

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
      js: [],
      css: ['../../../static/css/reset.css'],
      admin:{
        comLoad: function (resolve) {
          require(['../view/site/IndexView'], resolve)
        },
        children:{
          //全局
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
              require(['../view/site/global/worthMentioningSet/worthMentioningSetView'], resolve)
            },
            metaInfo: {
              title: '微信设置',
              name:'worthMentioningSet',
              attribution:'全局'
            }
          },
          'worth-mentioning-config/h5wx':{
            comLoad: function (resolve) {
              require(['../view/site/global/worthMentioningSet/worthMentioningConfigH5WxView'], resolve)
            },
            metaInfo: {
              title: '微信设置',
              name:'worthMentioningSet',
              attribution:'全局'
            }
          },
          'worth-mentioning-config/applets':{
            comLoad: function (resolve) {
              require(['../view/site/global/worthMentioningSet/worthMentioningConfigAppletsView'], resolve)
            },
            metaInfo: {
              title: '微信设置',
              name:'worthMentioningSet',
              attribution:'全局'
            }
          },
          'worth-mentioning-config/pcwx':{
            comLoad: function (resolve) {
              require(['../view/site/global/worthMentioningSet/worthMentioningConfigPcWxView'], resolve)
            },
            metaInfo: {
              title: '微信设置',
              name:'worthMentioningSet',
              attribution:'全局'
            }
          },

          'pay-set': {
            comLoad: function (resolve) {
              require(['../view/site/global/paySet/paySetView'], resolve)
            },
            metaInfo: {
              title: '支付设置',
              name:'paySet',
              attribution:'全局'
            }
          },
          'pay-config/wx':{
            comLoad: function (resolve) {
              require(['../view/site/global/paySet/payConfigWxView'], resolve)
            },
            metaInfo: {
              title: '支付设置',
              name:'paySet',
              attribution:'全局'
            }
          },

          'notice-set': {
            comLoad: function (resolve) {
              require(['../view/site/global/notice/noticeSetView'], resolve)
            },
            metaInfo: {
              title: '通知设置',
              name:'noticeSet',
              attribution:'全局'
            }
          },

          'notice-configure': {
            comLoad: function (resolve) {
              require(['../view/site/global/notice/noticeConfigureView'], resolve)
            },
            metaInfo: {
              title: '通知设置',
              name:'noticeSet',
              attribution:'全局'
            }
          },
          'tencent-cloud-set': {
            comLoad: function (resolve) {
              require(['../view/site/global/tencentCloudConfig/tencentCloudSetView'], resolve)
            },
            metaInfo: {
              title: '腾讯云设置',
              name:'tencentCloudSet',
              attribution:'全局'
            }
          },
          'tencent-cloud-config/cloud':{
            comLoad: function (resolve) {
              require(['../view/site/global/tencentCloudConfig/tencentCloudConfigCloudView'], resolve)
            },
            metaInfo: {
              title: '腾讯云设置',
              name:'tencentCloudSet',
              attribution:'全局'
            }
          },
          'tencent-cloud-config/sms':{
            comLoad: function (resolve) {
              require(['../view/site/global/tencentCloudConfig/tencentCloudConfigSmsView'], resolve)
            },
            metaInfo: {
              title: '腾讯云设置',
              name:'tencentCloudSet',
              attribution:'全局'
            }
          },
          'tencent-cloud-config/cos':{
            comLoad: function (resolve) {
              require(['../view/site/global/tencentCloudConfig/tencentCloudConfigCosView'], resolve)
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
              require(['../view/site/global/contentFilteringSet/contentFilteringSetView'], resolve)
            },
            metaInfo: {
              title: '内容过滤设置',
              name:'contentFilteringSet',
              attribution:'全局'
            }
          },
          'add-sensitive-words':{
            comLoad: function (resolve) {
              require(['../view/site/global/contentFilteringSet/addSensitiveWordsView'], resolve)
            },
            metaInfo: {
              title: '内容过滤设置',
              name:'contentFilteringSet',
              attribution:'全局'
            }
          },
          /*'user-manage-set':{
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
              require(['../view/site/global/roleManagesSet/roleManageSetView'], resolve)
            },
            metaInfo: {
              title: '后台角色管理',
              name:'roleManage',
              attribution:'全局'
            }
          },
          'role-manage-set/permission':{
            comLoad: function (resolve) {
              require(['../view/site/global/roleManagesSet/roleManagePermissionEditingView'], resolve)
            },
            metaInfo: {
              title: '后台角色管理权限编辑',
              name:'roleManage',
              attribution:'全局'
            }
          },*/


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
              require(['../view/site/cont/contManages/contManageView'], resolve)
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
              require(['../view/site/cont/contManages/contManageSearchView'], resolve)
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
              require(['../view/site/cont/contModeration/contReviewView'], resolve)
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
              require(['../view/site/cont/contModeration/replyReviewView'], resolve)
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
              require(['../view/site/cont/recycleBin/recycleBinView'], resolve)
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
              require(['../view/site/cont/recycleBin/recycleBinReplyView'], resolve)
            },
            metaInfo: {
              title: '回收站',
              name:'recycleBin',
              attribution:'内容',
              alias:'回帖'
            }
          },

          //财务分类
          'fund-details':{
            comLoad: function (resolve) {
              require(['../view/site/finance/fundDetailsView'], resolve)
            },
            metaInfo: {
              title: '资金明细',
              name:'fundDetails',
              attribution:'财务',
            }
          },
          'order-record':{
            comLoad: function (resolve) {
              require(['../view/site/finance/orderRecordView'], resolve)
            },
            metaInfo: {
              title: '订单记录',
              name:'orderRecord',
              attribution:'财务',
            }
          },
          'withdrawal-application':{
            comLoad: function (resolve) {
              require(['../view/site/finance/withdrawMange/withdrawalApplicationView'], resolve)
            },
            metaInfo: {
              title: '提现管理',
              name:'withdrawMange',
              attribution:'财务',
              alias:'提现申请'
            }
          },
          'withdrawal-setting':{
            comLoad: function (resolve) {
              require(['../view/site/finance/withdrawMange/withdrawalSettingView'], resolve)
            },
            metaInfo: {
              title: '提现管理',
              name:'withdrawMange',
              attribution:'财务',
              alias:'提现设置'
            }
          },
          'financial-statistics':{
            comLoad: function (resolve) {
              require(['../view/site/finance/financialStatistics'], resolve)
            },
            metaInfo: {
              title: '财务统计',
              name:'financialStatistics',
              attribution:'财务',
            }
          },


          //用户
          'user-manage':{
            comLoad:function (resolve) {
              require(['../view/site/user/userManage/userManageView'],resolve)
            },
            metaInfo:{
              title:'用户管理',
              name:'userManage',
              attribution:'用户'
            }
          },
          'user-search-list':{
            comLoad:function (resolve) {
              require(['../view/site/user/userManage/userSearchList'],resolve)
            },
            metaInfo:{
              title:'用户管理',
              name:'userManage',
              attribution:'用户'
            }
          },
          'user-details':{
            comLoad:function (resolve) {
              require(['../view/site/user/userManage/userDetailsView'],resolve)
            },
            metaInfo:{
              title:'用户管理',
              name:'userManage',
              attribution:'用户'
            }
          },
          'wallet':{
            comLoad:function (resolve) {
              require(['../view/site/user/userManage/walletView'],resolve)
            },
            metaInfo:{
              title:'用户管理',
              name:'userManage',
              attribution:'用户',
            }
          },

          'user-rol':{
            comLoad:function (resolve) {
              require(['../view/site/user/userRol/userRolView'],resolve)
            },
            metaInfo:{
              title:'用户角色',
              name:'userRol',
              attribution:'用户',
            }
          },
          'rol-permission':{
            comLoad:function (resolve) {
              require(['../view/site/user/userRol/rolPermissionView'],resolve)
            },
            metaInfo:{
              title:'用户角色',
              name:'userRol',
              attribution:'用户',
            }
          },
          'user-review':{
            comLoad:function (resolve) {
              require(['../view/site/user/userReviewView'],resolve)
            },
            metaInfo:{
              title:'用户审核',
              name:'userReview',
              attribution:'用户',
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
   * 后端路由守卫
   * @param  {[type]}   to   [description]
   * @param  {Function} next [description]
   * @return {[type]}        [description]
   */
  beforeEnter: function(to, form, next) {
    let token = webDb.getLItem('Authorization');
    let tokenId = webDb.getLItem('tokenId');
    let groupId = '';


    if(!token && !tokenId){
      if (to.path == '/admin/login'){
        next();
        return
      }
      next({path:"/admin/login"});
      console.log('未登录');
    } else {
      this.getUserInfo(tokenId).then(res=>{
        groupId = res.readdata.groups[0]._data.id;
        webDb.setLItem('username',res.data.attributes.username);
        if (groupId === '1'){
          if (to.path == '/admin/login'){
            next('/admin');
            return
          }
          next();
        } else {
          alert("权限不足！");
          if (to.path == '/admin/login'){
            next();
            return
          }
          next({path:"/admin/login"});
        }
      }).catch(()=>{
        if (to.path == '/admin/login'){
          next();
          return
        }
        next({path:"/admin/login"});
      });
      console.log('已经登录');
    }
  },

  getUserInfo(id){
    return appFetch({
      url:'users',
      method:'get',
      splice:'/' + id,
      data:{
        include:['groups']
      }
    }).then(res=>{
      console.log(res);
      return res
    }).catch(err=>{
      console.log(err);
    })
  }

};
