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
      'circle':{
        comLoad:function (resolve) {
          require(['../view/m_site/home/circleView'],resolve)
        },
        metaInfo:{
          title:"圈子首页",
          oneHeader: true
        }
      },
      'index':{
        comLoad:function (resolve) {
          require(['../view/m_site/home/indexView'],resolve)
        },
        metaInfo:{
          title:"移动端首页"
        }
      },
      'search':{
        comLoad:function (resolve) {
          require(['../view/m_site/search/searchView'],resolve)
        },
        metaInfo:{
          title:"搜索"
        }
      },
      'pay-circle':{
        comLoad:function (resolve) {
          require(['../view/m_site/home/payCircleView'],resolve)
        },
        metaInfo:{
          title:"付费圈子-首页-未登录"
        }
      },
      'pay-circle-login':{
        comLoad:function (resolve) {
          require(['../view/m_site/home/payCircleLoginView'],resolve)
        },
        metaInfo:{
          title:"付费圈子-已登录-未付费",
          oneHeader: true
        }
      },
      'pay-circle-con':{
        comLoad:function (resolve) {
          require(['../view/m_site/home/payCircleConView'],resolve)
        },
        metaInfo:{
          title:"付费圈子，内容页的分享"
        }
      },
      'open-circle':{
        comLoad:function (resolve) {
          require(['../view/m_site/home/openCircleView'],resolve)
        },
        metaInfo:{
          title:"公开的圈子，菜单栏内的邀请"
        }
      },
      'open-circle-con':{
        comLoad:function (resolve) {
          require(['../view/m_site/home/openCircleConView'],resolve)
        },
        metaInfo:{
          title:"详情",
          threeHeader: true
        }
      },
      'details':{
        comLoad:function (resolve) {
          require(['../view/m_site/home/detailsView'],resolve)
        },
        metaInfo:{
          title:"主题详情页",
          fourHeader: true
        }
      },
      'circle-invite':{
        comLoad:function (resolve) {
          require(['../view/m_site/home/circleInviteView'],resolve)
        },
        metaInfo:{
          title:"付费圈子，菜单栏内的邀请"
        }
      },
      'circle-manage-invite':{
        comLoad:function (resolve) {
          require(['../view/m_site/home/circleManageInviteView'],resolve)
        },
        metaInfo:{
          title:"圈子管理里的邀请"
        }
      },
      'management-circles':{
        comLoad:function (resolve) {
          require(['../view/m_site/management/managementCirclesView'],resolve)
        },
        metaInfo:{
          title:"管理圈子",
          threeHeader: true
        }
      },
      'members-management':{
        comLoad:function (resolve) {
          require(['../view/m_site/management/membersManagementView'],resolve)
        },
        metaInfo:{
          title:"成员管理"
        }
      },
      'circle-members':{
        comLoad:function (resolve) {
          require(['../view/m_site/management/circleMembersView'],resolve)
        },
        metaInfo:{
          title:"圈子成员"
        }

      },
      'circle-info':{
        comLoad:function (resolve) {
          require(['../view/m_site/management/circleInfoView'],resolve)
        },
        metaInfo:{
          title:"圈子信息",
          threeHeader: true
        }
      },
      'invite-join':{
        comLoad:function (resolve) {
          require(['../view/m_site/management/inviteToJoinView'],resolve)
        },
        metaInfo:{
          title:"邀请加入",
          threeHeader: true
        }
      },
      'delete':{
        comLoad:function (resolve) {
          require(['../view/m_site/management/deleteView'],resolve)
        },
        metaInfo:{
          title:"批量删除",
          oneHeader: true
        }
      },
      'header':{
        comLoad:function (resolve) {
          require(['../view/m_site/common/headerView'],resolve)
        },
        metaInfo:{
          title:"header"
        }
      },
      'post-topic':{
        comLoad:function (resolve) {
          require(['../view/m_site/home/postTopicView'],resolve)
        },
        metaInfo:{
          title:"发布主题"
        }
      },

      //登录、注册、微信绑定模块路由
      'login-user':{
        comLoad:function (resolve) {
          require(['../view/m_site/login/loginUserView'],resolve)
        },
        metaInfo:{
          title:"登录",
          threeHeader: true
        }
      },
      'login-phone':{
        comLoad:function (resolve) {
          require(['../view/m_site/login/loginPhoneView'],resolve)
        },
        metaInfo:{
          title:"手机号登录"
        }
      },
      'wx-login-bd':{
        comLoad:function (resolve) {
          require(['../view/m_site/login/wxLoginBdView'],resolve)
        },
        metaInfo:{
          title:"微信登录绑定账号"
        }
      },
      'wx-sign-up-bd':{
        comLoad:function (resolve) {
          require(['../view/m_site/login/wxSignUpBdView'],resolve)
        },
        metaInfo:{
          title:"微信注册绑定账号"
        }
      },
      'sign-up':{
        comLoad:function (resolve) {
          require(['../view/m_site/login/signUpView'],resolve)
        },
        metaInfo:{
          title:"注册账号"
        }
      },
      'bind-phone':{
        comLoad:function (resolve) {
          require(['../view/m_site/login/bindPhoneView'],resolve)
        },
        metaInfo:{
          title:"绑定手机号"
        }
      },
      'retrieve-pwd':{
        comLoad:function (resolve) {
          require(['../view/m_site/login/retrievePasswordView'],resolve)
        },
        metaInfo:{
          title:"忘记密码"
        }
      },
      'pay-the-fee':{
        comLoad:function (resolve) {
          require(['../view/m_site/login/payTheFeeView'],resolve)
        },
        metaInfo:{
          title:"支付费用"
        }
      },

      //主题详情页模块
      'reply-to-topic':{
        comLoad:function (resolve) {
          require(['../view/m_site/themeDetails/replyToTopicView'],resolve)
        },
        metaInfo:{
          title:"回复主题"
        }
      },

      //我的模块
      'modify-data':{
        comLoad:function (resolve) {
          require(['../view/m_site/myInfo/modifyDataView'],resolve)
        },
        metaInfo:{
          title:"修改资料"
        }
      },
      'modify-phone':{
        comLoad:function (resolve) {
          require(['../view/m_site/myInfo/modifyPhoneView'],resolve)
        },
        metaInfo:{
          title:"修改手机号"
        }
      },
      'change-pwd':{
        comLoad:function (resolve) {
          require(['../view/m_site/myInfo/changePasswordView'],resolve)
        },
        metaInfo:{
          title:"修改密码"
        }
      },
      'withdraw':{
        comLoad:function (resolve) {
          require(['../view/m_site/myInfo/withdrawView'],resolve)
        },
        metaInfo:{
          title:"提款"
        }
      },
      'frozen-amount':{
        comLoad:function (resolve) {
          require(['../view/m_site/myInfo/frozenAmountView'],resolve)
        },
        metaInfo:{
          title:"冻结资金"
        }
      },
      'withdrawals-record':{
        comLoad:function (resolve) {
          require(['../view/m_site/myInfo/withdrawalsRecordView'],resolve)
        },
        metaInfo:{
          title:"提现记录"
        }
      },
      'my-notice':{
        comLoad:function (resolve) {
          require(['../view/m_site/myInfo/myNoticeView'],resolve)
        },
        metaInfo:{
          title:"我的通知"
        }
      },
      'my-collection':{
        comLoad:function (resolve) {
          require(['../view/m_site/myInfo/myCollectionView'],resolve)
        },
        metaInfo:{
          title:"我的收藏"
        }
      },
      'home-page':{
        comLoad:function (resolve) {
          require(['../view/m_site/myInfo/homePageView'],resolve)
        },
        metaInfo:{
          title:"个人主页"
        }
      },
      'reply':{
        comLoad:function (resolve) {
          require(['../view/m_site/myInfo/replyView'],resolve)
        },
        metaInfo:{
          title:"回复我的"
        }
      },
      'reward':{
        comLoad:function (resolve) {
          require(['../view/m_site/myInfo/rewardView'],resolve)
        },
        metaInfo:{
          title:"打赏我的"
        }
      },
      'like':{
        comLoad:function (resolve) {
          require(['../view/m_site/myInfo/likeView'],resolve)
        },
        metaInfo:{
          title:"点赞我的"
        }
      },
      'my-wallet':{
        comLoad:function (resolve) {
          require(['../view/m_site/myInfo/myWalletView'],resolve)
        },
        metaInfo:{
          title:"我的钱包"
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
