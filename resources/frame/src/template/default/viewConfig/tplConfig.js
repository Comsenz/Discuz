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
      circle:{
        comLoad:function (resolve) {
          require(['../view/m_site/home/circleView'],resolve)
        },
        metaInfo:{
          title:"圈子首页"
        }
      },
      header:{
        comLoad:function (resolve) {
          require(['../view/m_site/common/headerView'],resolve)
        },
        metaInfo:{
          title:"header"
        }
      },

      //登录、注册、微信绑定模块路由
      login_user:{
        comLoad:function (resolve) {
          require(['../view/m_site/login/loginUserView'],resolve)
        },
        metaInfo:{
          title:"圈子首页"
        }
      },
      login_phone:{
        comLoad:function (resolve) {
          require(['../view/m_site/login/loginPhoneView'],resolve)
        },
        metaInfo:{
          title:"手机号登录"
        }
      },
      wx_login_bd:{
        comLoad:function (resolve) {
          require(['../view/m_site/login/wxLoginBdView'],resolve)
        },
        metaInfo:{
          title:"微信登录绑定账号"
        }
      },
      wx_sign_up_bd:{
        comLoad:function (resolve) {
          require(['../view/m_site/login/wxSignUpBdView'],resolve)
        },
        metaInfo:{
          title:"微信注册绑定账号"
        }
      },
      sign_up:{
        comLoad:function (resolve) {
          require(['../view/m_site/login/signUpView'],resolve)
        },
        metaInfo:{
          title:"注册账号"
        }
      },
      bind_phone:{
        comLoad:function (resolve) {
          require(['../view/m_site/login/bindPhoneView'],resolve)
        },
        metaInfo:{
          title:"绑定手机号"
        }
      },
      retrieve_pwd:{
        comLoad:function (resolve) {
          require(['../view/m_site/login/retrievePasswordView'],resolve)
        },
        metaInfo:{
          title:"忘记密码"
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
