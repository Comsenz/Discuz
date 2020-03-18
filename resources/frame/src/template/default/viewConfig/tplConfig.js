/**
 * 模板配置
 */
import browserDb from '../../../helpers/webDbHelper';
import appFetch from '../../../helpers/axiosHelper';
import appCommonH from '../../../helpers/commonHelper';
import fa from "element-ui/src/locale/lang/fa";

export default {
  /**
   * [路由器模板配置]
   * @type {Object}
   *
   * site为模块名，index为页面名称，拼接后路径为site/index
   */
  template: {
    m_site: {
      css: ['/css/reset.css'],
      // js: ['/js/rem.js'],
      'circle': {
        comLoad: function (resolve) {
          require(['../view/m_site/home/circleView'], resolve)
        },
        metaInfo: {
          title: "站点首页",
          oneHeader: true
        }
      },
      'header': {
        comLoad: function (resolve) {
          require(['../view/m_site/common/headerView'], resolve)
        },
        metaInfo: {
          title: "header"
        }
      },
      'sidebar': {
        comLoad: function (resolve) {
          require(['../view/m_site/common/sidebarView'], resolve)
        },
        metaInfo: {
          title: "sidebar"
        }
      },
      'expression': {  //表情
        comLoad: function (resolve) {
          require(['../view/m_site/common/expressionView'], resolve)
        },
        metaInfo: {
          title: "expression"
        }
      },

      'wechat': {
        comLoad: function (resolve) {
          require(['../view/m_site/common/wechatView'], resolve)
        },
        metaInfo: {
          title: "微信"
        }
      },
      'index': {
        comLoad: function (resolve) {
          require(['../view/m_site/home/indexView'], resolve)
        },
        metaInfo: {
          title: "移动端首页"
        }
      },
      'search': {
        comLoad: function (resolve) {
          require(['../view/m_site/search/searchView'], resolve)
        },
        metaInfo: {
          title: "搜索"
        }
      },
      'theme-search': {
        comLoad: function (resolve) {
          require(['../view/m_site/search/themeSearchView'], resolve)
        },
        metaInfo: {
          title: "主题搜索"
        }
      },
      'pay-circle': {
        comLoad: function (resolve) {
          require(['../view/m_site/home/payCircleView'], resolve)
        },
        metaInfo: {
          title: "付费站点-首页-未登录",
          oneHeader: true
        }
      },
      'pay-circle-login': {
        comLoad: function (resolve) {
          require(['../view/m_site/home/payCircleLoginView'], resolve)
        },
        metaInfo: {
          title: "付费站点-已登录-未付费",
          oneHeader: true
        }
      },
      'pay-circle-con/:themeId/:groupId': {
        comLoad: function (resolve) {
          require(['../view/m_site/home/payCircleConView'], resolve)
        },
        metaInfo: {
          title: "付费站点，内容页的分享",
          oneHeader: true
        }
      },
      'open-circle/:userId': {
        comLoad: function (resolve) {
          require(['../view/m_site/home/openCircleView'], resolve)
        },
        metaInfo: {
          title: "公开的站点，菜单栏内的邀请",
          oneHeader: true
        }
      },
      'open-circle-con': {
        comLoad: function (resolve) {
          require(['../view/m_site/home/openCircleConView'], resolve)
        },
        metaInfo: {
          title: "公开站点，内容页的分享"
        }
      },
      'details/:themeId': {
        comLoad: function (resolve) {
          require(['../view/m_site/home/detailsView'], resolve)
        },
        metaInfo: {
          title: "主题详情页",
          oneHeader: true
        }
      },
      'normal-details': {
        comLoad: function (resolve) {
          require(['../view/m_site/home/details/normalDetailsView'], resolve)
        },
        metaInfo: {
          title: "普通主题详情页",
          oneHeader: true
        }
      },
      'long-text-details': {
        comLoad: function (resolve) {
          require(['../view/m_site/home/details/longTextDetailsView'], resolve)
        },
        metaInfo: {
          title: "长文主题详情页",
          oneHeader: true
        }
      },
      'circle-invite': {
        comLoad: function (resolve) {
          require(['../view/m_site/home/circleInviteView'], resolve)
        },
        metaInfo: {
          title: "付费站点，菜单栏内的邀请",
          oneHeader: true
        }
      },
      'circle-manage-invite': {
        comLoad: function (resolve) {
          require(['../view/m_site/home/circleManageInviteView'], resolve)
        },
        metaInfo: {
          title: "站点管理里的邀请",
          oneHeader: true
        }
      },
      'management-circles': {
        comLoad: function (resolve) {
          require(['../view/m_site/management/managementCirclesView'], resolve)
        },
        metaInfo: {
          title: "管理站点",
          threeHeader: true
        }
      },
      'members-management': {
        comLoad: function (resolve) {
          require(['../view/m_site/management/membersManagementView'], resolve)
        },
        metaInfo: {
          title: "成员管理"
        }
      },
      'circle-members': {
        comLoad: function (resolve) {
          require(['../view/m_site/management/circleMembersView'], resolve)
        },
        metaInfo: {
          title: "站点成员"
        }

      },
      'circle-info': {
        comLoad: function (resolve) {
          require(['../view/m_site/management/circleInfoView'], resolve)
        },
        metaInfo: {
          title: "站点信息"
        }
      },
      'invite-join': {
        comLoad: function (resolve) {
          require(['../view/m_site/management/inviteToJoinView'], resolve)
        },
        metaInfo: {
          title: "邀请加入",
          threeHeader: true
        }
      },
      'delete': {
        comLoad: function (resolve) {
          require(['../view/m_site/management/deleteView'], resolve)
        },
        metaInfo: {
          title: "批量删除",
          oneHeader: true
        }
      },

      'theme-det': {
        comLoad: function (resolve) {
          require(['../view/m_site/common/themeDetView'], resolve)
        },
        metaInfo: {
          title: "主题详情"
        }
      },
      'post-topic/:cateId': {
        comLoad: function (resolve) {
          require(['../view/m_site/home/post/postTopicView'], resolve)
        },
        metaInfo: {
          title: "发布主题"
        }
      },
      'post-video/:cateId':{
        comLoad:function (resolve) {
        require(['../view/m_site/home/post/postVideoView'],resolve)
        },
        metaInfo:{
        title:"发布视频"
        }
      },
      'post-longText/:cateId': {
        comLoad: function (resolve) {
          require(['../view/m_site/home/post/postLongTextView'], resolve)
        },
        metaInfo: {
          title: "发布长文"
        }
      },
      'edit-topic/:themeId': {
        comLoad: function (resolve) {
          require(['../view/m_site/home/edit/editTopicView'], resolve)
        },
        metaInfo: {
          title: "编辑主题"
        }
      },
      'edit-long-text/:themeId': {
        comLoad: function (resolve) {
          require(['../view/m_site/home/edit/editLongTextView'], resolve)
        },
        metaInfo: {
          title: "编辑长文"
        }
      },
      'edit-video/:themeId':{
        comLoad:function (resolve) {
          require(['../view/m_site/home/edit/editVideoView'],resolve)
        },
        metaInfo:{
          title:"编辑视频"
        }
      },
      //登录、注册、微信绑定模块路由
      'login-user': {
        comLoad: function (resolve) {
          require(['../view/m_site/login/loginUserView'], resolve)
        },
        metaInfo: {
          title: "用户名登录"
        }
      },
      'login-phone': {
        comLoad: function (resolve) {
          require(['../view/m_site/login/loginPhoneView'], resolve)
        },
        metaInfo: {
          title: "手机号登录"
        }
      },
      'wx-login-bd': {
        comLoad: function (resolve) {
          require(['../view/m_site/login/wxLoginBdView'], resolve)
        },
        metaInfo: {
          title: "微信登录绑定账号"
        }
      },
      'wx-sign-up-bd': {
        comLoad: function (resolve) {
          require(['../view/m_site/login/wxSignUpBdView'], resolve)
        },
        metaInfo: {
          title: "微信注册绑定账号"
        }
      },
       'welink-login-bd':{
            comLoad:function (resolve) {
                require(['../view/m_site/login/welinkLoginBdView'],resolve)
            },
            metaInfo:{
                title:"WeLink登录绑定账号"
            }
        },
      'welink-sign-up-bd':{
          comLoad:function (resolve) {
              require(['../view/m_site/login/welinkSignUpBdView'],resolve)
          },
          metaInfo:{
              title:"WeLink注册绑定账号"
          }
      },
      'sign-up':{
        comLoad:function (resolve) {
          require(['../view/m_site/login/signUpView'],resolve)
        },
        metaInfo: {
          title: "注册账号"
        }
      },
      'bind-phone': {
        comLoad: function (resolve) {
          require(['../view/m_site/login/bindPhoneView'], resolve)
        },
        metaInfo: {
          title: "绑定手机号"
        }
      },
      'retrieve-pwd': {
        comLoad: function (resolve) {
          require(['../view/m_site/login/retrievePasswordView'], resolve)
        },
        metaInfo: {
          title: "忘记密码"
        }
      },
      'pay-the-fee': {
        comLoad: function (resolve) {
          require(['../view/m_site/login/payTheFeeView'], resolve)
        },
        metaInfo: {
          title: "支付费用"
        }
      },

      //主题详情页模块
      'reply-to-topic/:themeId/:replyId': {
        comLoad: function (resolve) {
          require(['../view/m_site/themeDetails/replyToTopicView'], resolve)
        },
        metaInfo: {
          title: "回复主题"
        }
      },

      //我的模块
      'modify-data': {
        comLoad: function (resolve) {
          require(['../view/m_site/myInfo/myData/modifyDataView'], resolve)
        },
        metaInfo: {
          title: "修改资料"
        }
      },
      'modify-phone': {
        comLoad: function (resolve) {
          require(['../view/m_site/myInfo/myData/modifyPhoneView'], resolve)
        },
        metaInfo: {
          title: "修改手机号"
        },
      },
      'bind-new-phone': {
        comLoad: function (resolve) {
          require(['../view/m_site/myInfo/myData/bindNewPhoneView'], resolve)
        },
        metaInfo: {
          title: "绑定新手机号"
        },
      },
      'change-pwd': {
        comLoad: function (resolve) {
          require(['../view/m_site/myInfo/myData/changePasswordView'], resolve)
        },
        metaInfo: {
          title: "修改密码"
        }
      },
      'real-name': {
        comLoad: function (resolve) {
          require(['../view/m_site/myInfo/myData/realNameView'], resolve)
        },
        metaInfo: {
          title: "实名认证"
        }
      },
      'withdraw': {
        comLoad: function (resolve) {
          require(['../view/m_site/myInfo/myWallet/withdrawView'], resolve)
        },
        metaInfo: {
          title: "提款"
        }
      },
      'wallet-details': {
        comLoad: function (resolve) {
          require(['../view/m_site/myInfo/myWallet/walletDetailsView'], resolve)
        },
        metaInfo: {
          title: "钱包明细"
        }
      },
      'order-details': {
        comLoad: function (resolve) {
          require(['../view/m_site/myInfo/myWallet/orderDetailsView'], resolve)
        },
        metaInfo: {
          title: "订单明细"
        }
      },
      'frozen-amount': {
        comLoad: function (resolve) {
          require(['../view/m_site/myInfo/myWallet/frozenAmountView'], resolve)
        },
        metaInfo: {
          title: "冻结资金"
        }
      },
      'withdrawals-record': {
        comLoad: function (resolve) {
          require(['../view/m_site/myInfo/myWallet/withdrawalsRecordView'], resolve)
        },
        metaInfo: {
          title: "提现记录"
        }
      },
      'my-notice': {
        comLoad: function (resolve) {
          require(['../view/m_site/myInfo/myNotice/myNoticeView'], resolve)
        },
        metaInfo: {
          title: "我的通知"
        }
      },
      'my-follow': {
        comLoad: function (resolve) {
          require(['../view/m_site/myInfo/myFollow/myFollowView'], resolve)
        },
        metaInfo: {
          title: "我的关注"
        }
      },
      'my-care': {
        comLoad: function (resolve) {
          require(['../view/m_site/myInfo/myFollow/myCareView'], resolve)
        },
        metaInfo: {
          title: "我关注的人"
        }
      },
      'care-me': {
        comLoad: function (resolve) {
          require(['../view/m_site/myInfo/myFollow/careMeView'], resolve)
        },
        metaInfo: {
          title: "关注我的人"
        }
      },
      'my-collection': {
        comLoad: function (resolve) {
          require(['../view/m_site/myInfo/myCollectionView'], resolve)
        },
        metaInfo: {
          title: "我的收藏"
        }
      },
      'home-page/:userId': {
        comLoad: function (resolve) {
          require(['../view/m_site/myInfo/homePageView'], resolve)
        },
        metaInfo: {
          title: "用户主页",
          oneHeader: true
        }
      },
      'reply': {
        comLoad: function (resolve) {
          require(['../view/m_site/myInfo/myNotice/replyView'], resolve)
        },
        metaInfo: {
          title: "回复我的"
        }
      },
      'reward': {
        comLoad: function (resolve) {
          require(['../view/m_site/myInfo/myNotice/rewardView'], resolve)
        },
        metaInfo: {
          title: "打赏我的"
        }
      },
      'like': {
        comLoad: function (resolve) {
          require(['../view/m_site/myInfo/myNotice/likeView'], resolve)
        },
        metaInfo: {
          title: "点赞我的"
        }
      },
      'system': {
        comLoad: function (resolve) {
          require(['../view/m_site/myInfo/myNotice/systemView'], resolve)
        },
        metaInfo: {
          title: "系统通知"
        }
      },
      'my-wallet': {
        comLoad: function (resolve) {
          require(['../view/m_site/myInfo/myWallet/myWalletView'], resolve)
        },
        metaInfo: {
          title: "我的钱包"
        }
      },
      'verify-pay-pwd': {
        comLoad: function (resolve) {
          require(['../view/m_site/myInfo/paymentPasswordSet/verifyPayPasswordView'], resolve);
        },
        metaInfo: {
          title: '验证支付密码'
        }
      },
      'setup-pay-pwd': {
        comLoad: function (resolve) {
          require(['../view/m_site/myInfo/paymentPasswordSet/setUpPayPasswordView'], resolve);
        },
        metaInfo: {
          title: '设置支付密码'
        }
      },
      'confirm-pay-pwd': {
        comLoad: function (resolve) {
          require(['../view/m_site/myInfo/paymentPasswordSet/confirmPayPasswordView'], resolve);
        },
        metaInfo: {
          title: '确认支付密码'
        }
      },


      //公共页面模块
      'pay-status': {
        comLoad: function (resolve) {
          require(['../view/m_site/common/pay/payView'], resolve)
        },
        metaInfo: {
          title: "支付订单查询"
        }
      },
      'site-close': {
        comLoad: function (resolve) {
          require(['../view/m_site/common/siteClose'], resolve)
        },
        metaInfo: {
          title: "站点关闭提示"
        }
      },
      'supplier-all-back': {
        comLoad: function (resolve) {
          require(['../view/m_site/common/supplierAllBack'], resolve)
        },
        metaInfo: {
          title: "空白页"
        }
      },
      'information-page': {
        comLoad: function (resolve) {
          require(['../view/m_site/common/informationPage'], resolve)
        },
        metaInfo: {
          title: '提示信息'
        }
      }


    }
  },

  /**
   * 前端路由守卫
   * @param  {[type]}   to   [description]
   * @param  {Function} next [description]
   * @return {[type]}        [description]
   */

  beforeEnter: function(to, form, next) {
  //判断设备
  let isWeixin = appCommonH.isWeixin().isWeixin;
  let isPhone = appCommonH.isWeixin().isPhone;
  let isWeLink = appCommonH.isWeLink().isWeLink;


    /*
    * 登录且付费不能访问的页面列表
    * */
    const signInAndPayForAccess = [
      'login-user',
      'login-phone',
      'sign-up',
      'pay-the-fee',
      'pay-circle-login',
      'pay-circle',
      'pay-circle-con/:themeId/:groupId',
      'open-circle/:userId',
    ];


    /*
    * 登录成功状态，不能访问的页面列表
    * */
    const successfulLoginForbiddenPage = [
      'login-user',
      'login-phone',
      'sign-up',
      'retrieve-pwd'
    ];


    //公开模式下不能访问的页面
    const publicNotAccessPage = [
      // 'pay-the-fee',
      'pay-circle-con/:themeId/:groupId',
      // 'pay-circle',         //付费站点,逻辑内做判断，如果访问除去'/'的页面，都要跳到该页面
      // 'pay-status',
    ];


    /*
    * 未登录可访问页面
    * */
    const notLoggedInToAccessPage = [
      'login-user',
      'login-phone',
      'sign-up',
      'bind-phone',
      'pay-the-fee',
      'retrieve-pwd',
      'pay-circle-con/:themeId/:groupId',
      'open-circle-con',
      'pay-circle',         //付费站点,逻辑内做判断，如果访问除去'/'的页面，都要跳到该页面
      'open-circle/:userId',
      'details/:themeId',
      'home-page/:userId',
      'pay-status',
      'wx-login-bd',
      'wx-sign-up-bd',
      'supplier-all-back',
      'circle-invite',
      'site-close',
      'information-page'
    ];

    /*
    * 微信未登录可访问页面
    * */
    const wxNotLoggedInToAccessPage = [
      'wx-login-bd',
      'wx-sign-up-bd',
      'supplier-all-back',
      'site-close',
      'information-page'
    ];


    /*
    * 获取tokenId
    * */
    const tokenId = browserDb.getLItem('tokenId');
    const Authorization = browserDb.getLItem('Authorization');

    /*
    * 前台路由全局处理
    * */
    var registerClose = ''; //注册是否关闭
    var siteMode = '';      //站点模式
    var realName = '';      //实名认证是否关闭
    var canWalletPay = '';  //钱包密码设置
    var modifyPhone = '';   //短信验证是否关闭



    if (to.name === 'supplier-all-back' || form.name === 'supplier-all-back'){
      next();
    } else {
      this.getForum().then((res) => {

        /*
        * 站点关闭，跳转到站点关闭页面
        * */
        if (res.errors) {
          if (res.rawData[0].code === 'site_closed') {
            if (to.name === 'login-user') {
              next();
            } else {
              if (to.name === 'site-close') {
                next();
                return;
              } else {
                next({ path: '/site-close' });
                return;
              }

            }
          }

        } else {
          siteMode = res.readdata._data.set_site.site_mode;
          registerClose = res.readdata._data.set_reg.register_close;
          realName = res.readdata._data.qcloud.qcloud_faceid;
          canWalletPay = res.readdata._data.other.initialized_pay_password;
          modifyPhone = res.readdata._data.qcloud.qcloud_sms;

          /*
          * 注册关闭，未登录状态，进入注册页面后跳转到对应的站点页面
          * */
          if (to.name === 'sign-up') {
            if (!Authorization && !tokenId && !registerClose) {
              if (siteMode === 'pay') {
                next({ path: '/pay-circle' });
                return
              } else {
                next({ path: '/' });
                return
              }
            }
          } else if (to.name === 'real-name') {
            if (Authorization) {
              this.getUsers(tokenId).then(data => {
                if (realName === true && data.readdata._data.realname === '') {
                  next({ path: '/real-name' });
                  return
                } else {
                  next({ path: '/' })
                }
              })
            } else {
              next({ path: '/' });
              return;
            }

          } else if (to.name === 'verify-pay-pwd') {
            if (canWalletPay) {
              next();
              return
            } else {
              next({ path: '/setup-pay-pwd' })
            }
          } else if (to.name === 'bind-new-phone' || to.name === 'modify-phone') {
            if (modifyPhone) {
              next();
              return
            } else {
              next({ path: '/' })
            }
          }
        }


        if (tokenId && Authorization) {
          /*已登录状态*/

          // this.getForum().then((ress) => {
          // console.log('已经登录状态');

          if (res.readdata._data.set_site.site_mode === 'pay') {
            this.getUsers(tokenId).then(userInfo => {
              /*获取用户付费状态并判断*/
              if (userInfo.readdata._data.paid) {
                /*付费状态下，用户已付费可以任意访问，但不能访问未登录可以访问的页面*/
                if (signInAndPayForAccess.includes(to.name)) {
                  if (to.name === 'pay-circle-con/:themeId/:groupId') {
                    // next({path:'/details/' + to.params.themeId});
                  }
                  next(vm => {
                    vm.$router.go(-1);
                  })
                } else {
                  next();
                }
              } else {
                if (notLoggedInToAccessPage.includes(to.name)) {
                  next();
                } else {
                  if (to.name === 'pay-circle-login') {
                    next();
                    return
                  }
                  next({ path: 'pay-circle-login' });
                }
              }
            })

          } else {
            if (signInAndPayForAccess.includes(to.name)) {
              // next(form.path)
              next('/')
            } else {
              next();
            }

          }

          // })


        } else {
          /*未登录状态*/

          if (res.readdata._data.passport.offiaccount_close == true) {
            /*判断登录设备*/
            if (isWeixin) {
              /*微信设备，跳转到微信绑定页，改成跳转到微信注册绑定*/

              /*if (!browserDb.getSItem('wxData')) {
                browserDb.setSItem('wxData',false);
                localStorage.clear();
                browserDb.setLItem('siteInfo', res.readdata);
                console.log('第一次进');
              } else {
                console.log('多次进');
              }*/

              if (res.readdata._data.set_site.site_mode === 'public') {
                if (!browserDb.getSItem('beforeVisiting')){
                  if (!wxNotLoggedInToAccessPage.includes(to.name)){
                    browserDb.setSItem('beforeVisiting',to.path);
                  }
                }
              }
              if (wxNotLoggedInToAccessPage.includes(to.name)) {
                next();
              } else {
                next({ path: '/wx-sign-up-bd' });
              }
            } else {
              if (notLoggedInToAccessPage.includes(to.name)) {
                /*符合，未登录可以访问站点*/
                /*判断站点模式*/
                if (res.readdata._data.set_site.site_mode === 'public') {
                  if (publicNotAccessPage.includes(to.name)) {
                    // to.name,'当前包含路由
                    if (to.name === 'pay-circle-con/:themeId/:groupId') {
                      // to.params.themeId,'当前router主题id
                      next({ path: '/details/' + to.params.themeId });
                    }
                  }
                }
                next();
              } else {
                /*不符合，跳转到未登录，可访问站点*/
                /*判断站点模式*/
                if (res.readdata._data.set_site.site_mode === 'pay') {
                  if (to.name === 'pay-circle') {
                    next();
                    return
                  }
                  next({ path: 'pay-circle' });
                } else {
                  if (to.name === 'circle') {
                    next();
                    return
                  }
                  next();
                }
              }
            }

          }else {
            if (notLoggedInToAccessPage.includes(to.name)){
              /*符合，未登录可以访问站点*/
              /*判断站点模式*/
              if (res.readdata._data.set_site.site_mode === 'public') {
                if (publicNotAccessPage.includes(to.name)) {
                  // to.name,'当前包含路由
                  if (to.name === 'pay-circle-con/:themeId/:groupId') {
                    // to.params.themeId,'当前router主题id
                    next({ path: '/details/' + to.params.themeId });
                  }
                }
              }
              next();
            } else {
              /*不符合，跳转到未登录，可访问站点*/
              /*判断站点模式*/
              if (res.readdata._data.set_site.site_mode === 'pay') {
                if (to.name === 'pay-circle') {
                  next();
                  return
                }
                next({ path: 'pay-circle' });
              } else {
                if (to.name === 'circle') {
                  next();
                  return
                }
                next()
              }
            }
          }
        }
      });
    }



    /*
    * 判断设备显示不同的尺寸
    * */
    if (isWeixin) {

    } else if (isPhone) {
      // 基准大小
      const baseSize = 37.5;  //需要跟.postcssrc.js’rootValue‘属性统一大小
      // 设置 rem 函数
      function setRem() {
        // 当前页面宽度相对于 750 宽的缩放比例，可根据自己需要修改。
        const scale = document.documentElement.clientWidth / 375;
        // 设置页面根节点字体大小
        document.documentElement.style.fontSize = (baseSize * Math.min(scale, 2)) + 'px'
      }
      // 初始化
      setRem();
      // 改变窗口大小时重新设置 rem
      window.onresize = function () {
        setRem()
      };
    } else {
      // 基准大小
      const baseSize = 32;  //需要跟.postcssrc.js’rootValue‘属性统一大小
      // 设置 rem 函数
      function setRem() {
        // 当前页面宽度相对于 640 宽的缩放比例，可根据自己需要修改。
        const scale = document.documentElement.clientWidth / 320;
        // 设置页面根节点字体大小
        document.documentElement.style.fontSize = (baseSize * Math.min(scale, 2)) + 'px'
      }
      // 初始化
      setRem();
      // 改变窗口大小时重新设置 rem
      window.onresize = function () {
        setRem()
      };

      document.getElementsByTagName("html")[0].style.backgroundColor = '#f9f9f9';
      document.getElementsByTagName("body")[0].style.width = "640px";
      let viewportWidth = window.innerWidth;
      document.getElementsByTagName("body")[0].style.marginLeft = (viewportWidth - 640) / 2 + 'px';
    }

  },

  /*
  * 接口请求
  * */
  getUsers(id) {
    return appFetch({
      url: 'users',
      method: 'get',
      splice: '/' + id,
      headers: { 'Authorization': 'Bearer ' + browserDb.getLItem('Authorization') },
      data: {
        include: ['groups']
      }
    }).then(res => {
      return res;
    }).catch(err => {
    })
  },
  getForum() {
    return appFetch({
      url: 'forum',
      method: 'get',
      data: {}
    }).then(res => {
      if (res.errors) {
        if(res.rawData[0].code == 'not_install'){
          window.location.href = res.rawData[0].detail.installUrl;
        }
      } else {
        browserDb.setLItem('siteInfo', res.readdata);
        let siteInfoStat = res.readdata._data.set_site.site_stat;
        app.bus.$emit('stat',siteInfoStat)
        return res;
      }

    }).catch(err => {
    })
  },

};
