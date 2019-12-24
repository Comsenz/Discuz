/**
 * 模板配置
 */
import browserDb from '../../../helpers/webDbHelper';
import appFetch from '../../../helpers/axiosHelper';
import appCommonH from '../../../helpers/commonHelper';

export default {
  /**
   * [路由器模板配置]
   * @type {Object}
   *
   * site为模块名，index为页面名称，拼接后路径为site/index
   */
  template: {
    m_site:{
      js: ['/js/rem.js'],
      css: [],
      'circle':{
        comLoad:function (resolve) {
          require(['../view/m_site/home/circleView'],resolve)
        },
        metaInfo:{
          title:"站点首页",
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
      'sidebar':{
        comLoad:function (resolve) {
          require(['../view/m_site/common/sidebarView'],resolve)
        },
        metaInfo:{
          title:"sidebar"
        }
      },
      'expression':{  //表情
        comLoad:function (resolve) {
          require(['../view/m_site/common/expressionView'],resolve)
        },
        metaInfo:{
          title:"expression"
        }
      },

      'wechat':{
        comLoad:function (resolve) {
          require(['../view/m_site/common/wechatView'],resolve)
        },
        metaInfo:{
          title:"微信"
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
          title:"付费站点-首页-未登录",
          oneHeader: true
        }
      },
      'pay-circle-login':{
        comLoad:function (resolve) {
          require(['../view/m_site/home/payCircleLoginView'],resolve)
        },
        metaInfo:{
          title:"付费站点-已登录-未付费",
          oneHeader: true
        }
      },
      'pay-circle-con/:themeId':{
        comLoad:function (resolve) {
          require(['../view/m_site/home/payCircleConView'],resolve)
        },
        metaInfo:{
          title:"付费站点，内容页的分享",
          oneHeader: true
        }
      },
      'open-circle':{
        comLoad:function (resolve) {
          require(['../view/m_site/home/openCircleView'],resolve)
        },
        metaInfo:{
          title:"公开的站点，菜单栏内的邀请",
          oneHeader: true
        }
      },
      'open-circle-con':{
        comLoad:function (resolve) {
          require(['../view/m_site/home/openCircleConView'],resolve)
        },
        metaInfo:{
          title:"公开站点，内容页的分享"
        }
      },
      'details/:themeId':{
        comLoad:function (resolve) {
          require(['../view/m_site/home/detailsView'],resolve)
        },
        metaInfo:{
          title:"主题详情页",
          oneHeader: true
        }
      },
      'circle-invite':{
        comLoad:function (resolve) {
          require(['../view/m_site/home/circleInviteView'],resolve)
        },
        metaInfo:{
          title:"付费站点，菜单栏内的邀请",
          oneHeader: true
        }
      },
      'circle-manage-invite':{
        comLoad:function (resolve) {
          require(['../view/m_site/home/circleManageInviteView'],resolve)
        },
        metaInfo:{
          title:"站点管理里的邀请",
          oneHeader: true
        }
      },
      'management-circles':{
        comLoad:function (resolve) {
          require(['../view/m_site/management/managementCirclesView'],resolve)
        },
        metaInfo:{
          title:"管理站点",
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
          title:"站点成员"
        }

      },
      'circle-info':{
        comLoad:function (resolve) {
          require(['../view/m_site/management/circleInfoView'],resolve)
        },
        metaInfo:{
          title:"站点信息"
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

	  'theme-det':{
	    comLoad:function (resolve) {
	      require(['../view/m_site/common/themeDetView'],resolve)
	    },
	    metaInfo:{
	      title:"主题详情"
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
          title:"用户名登录"
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
      'wallet-details':{
        comLoad:function (resolve) {
          require(['../view/m_site/myInfo/walletDetailsView'],resolve)
        },
        metaInfo:{
          title:"钱包明细"
        }
      },
      'order-details':{
        comLoad:function (resolve) {
          require(['../view/m_site/myInfo/orderDetailsView'],resolve)
        },
        metaInfo:{
          title:"订单明细"
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
      'home-page/:userId':{
        comLoad:function (resolve) {
          require(['../view/m_site/myInfo/homePageView'],resolve)
        },
        metaInfo:{
          title:"用户主页",
          oneHeader: true
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

  if (isWeixin == true) {
    //微信登录时
    console.log(to.query);
      if(to.query.code){
        appFetch({
          url: "wechat",
          method: "get",
          data: {
            code:to.query.code,
            state:to.query.state
          }
        }).then(res => {
          console.log(res);
        });
      } else {
          appFetch({
            url: "wechat",
            method: "get",
            data: {}
          }).then(res => {
            console.log(res);
            // window.location.href = res.data.attributes.location;
            let url = 'http://10.0.10.210:8883/pay-circle';
            // window.location.href = `https://open.weixin.qq.com/connect/oauth2/authorize?appid=wxba449971e7a27c1c&redirect_uri=${encodeURIComponent(url)}&response_type=code&scope=snsapi_userinfo&state=0`

            // console.log(encodeURIComponent(url);

            window.location.href="https://open.weixin.qq.com/connect/oauth2/authorize?appid=wxba449971e7a27c1c&redirect_uri=http%3A%2F%2F10.0.10.210%3A8883%2Fpay-circle&response_type=code&scope=snsapi_userinfo&state=0";

          });
        }


  } else if(isPhone == true) {
    //手机浏览器登录时
    console.log('手机浏览器登录');
    let authVal = browserDb.getLItem('Authorization');
      let siteMode = '';
      let isPaid = '';
      var pro1 = new Promise(function(resolve, reject){
        //请求站点信息接口，判断站点是否付费
        appFetch({
          url: 'forum',
          method: 'get',
          data: {
            include: ['users'],
          }
        }).then((res) => {
          console.log(res);
          siteMode = res.readdata._data.siteMode;
          console.log(siteMode);
          resolve();
        });
      });

      Promise.all([pro1]).then(function (results) {
      // Promise.all([pro1, pro2]).then(function (results) {
      if(authVal){ //判断本地是否存在access_token
        // console.log('已登录，token已存在');
        var pro2 = new Promise(function(resolve, reject){
          //请求站点信息接口，判断站点是否付费
        var userId = browserDb.getLItem('tokenId');
        console.log(browserDb.getLItem('tokenId'));
        appFetch({
          url: 'users',
          method: 'get',
          splice:'/'+userId,
          data: {
          include: 'groups',
          }
          }).then((res) => {
          isPaid = res.readdata._data.paid;
          resolve();
          // console.log(isPaid+'000000');
         })
        });
        //promise先请求接口，再根据接口数据去判断
        Promise.all([pro2]).then(function (results) {
          //请求站点信息，用于判断站点是否是付费站点
          console.log(siteMode+'23232323');
           if(siteMode == 'pay'){
             //站点为付费站点时
             if(isPaid == true){
                //当用户已付费时
                console.log(to);
                console.log('已付费');
                // console.log('当前用户已登录已付费时');
                next({
                  path:to.fullPath
                });
             } else {
               // console.log('已登录，未付费ssssss')
                next({
                  path:'/pay-circle-login'
                });
             }
           } else if(siteMode == 'public'){
             //站点为公开站点时
             // console.log('公开站点，已登录');
             //当用户已登录，且站点为公开站点时，进入到路由页面
              next({
                path:to.fullPath
              });
           } else {

           }
          next();
        });

      } else {
        console.log('未登录，token不存在');
        // console.log(siteMode+'123456')
         //请求站点信息，用于判断站点是否是付费站点
          if(siteMode == 'pay'){
            console.log(7777);
            // console.log(to);
            // console.log(8888);
            //站点为付费站点时，跳转到付费页，如果是登录注册页，跳转到登录注册
            var ifLogin = to.fullPath.indexOf("login");
            var ifSign = to.fullPath.indexOf("sign");
            if(ifLogin != -1){
              // console.log('d登录页');
              next({
                path:'/login-user'
              });
            } else if(ifSign != -1){
              // console.log('注册页');
              next({
                path:'/sign-up'
              });
            } else {
              console.log('首页');
              next({
                path:'/pay-circle'
              });
            }
          } else if(siteMode == 'public'){
            //站点为公开站点时
            //当用户未登录，且站点为公开站点时，进入到路由页面
            // console.log('当用户未登录，且站点为公开站点时，进入到路由页面');
            // console.log(to.fullPath)
             next({
               path:to.fullPath
             });
          } else {
            //当siteMode为其他值（undefined,null）

          }
      };
    });
    next();
  } else {
    let authVal = browserDb.getLItem('Authorization');
    let siteMode = '';
    let isPaid = '';
    var pro1 = new Promise(function(resolve, reject){
      //请求站点信息接口，判断站点是否付费
      appFetch({
        url: 'forum',
        method: 'get',
        data: {
          include: ['users'],
        }
      }).then((res) => {
        console.log(res);
        siteMode = res.readdata._data.siteMode;
        console.log(siteMode);
        resolve();
      });
    });

    Promise.all([pro1]).then(function (results) {
    // Promise.all([pro1, pro2]).then(function (results) {
    if(authVal){ //判断本地是否存在access_token
      // console.log('已登录，token已存在');
      var pro2 = new Promise(function(resolve, reject){
        //请求站点信息接口，判断站点是否付费
      var userId = browserDb.getLItem('tokenId');
      console.log(browserDb.getLItem('tokenId'));
      appFetch({
        url: 'users',
        method: 'get',
        splice:'/'+userId,
        data: {
        include: 'groups',
        }
        }).then((res) => {
        isPaid = res.readdata._data.paid;
        resolve();
        // console.log(isPaid+'000000');
       })
      });
      //promise先请求接口，再根据接口数据去判断
      Promise.all([pro2]).then(function (results) {
        //请求站点信息，用于判断站点是否是付费站点
        console.log(siteMode+'23232323');
         if(siteMode == 'pay'){
           //站点为付费站点时
           if(isPaid == true){
              //当用户已付费时
              console.log(to);
              // console.log('当前用户已登录已付费时');
              next({
                path:to.fullPath
              });
           } else {
             // console.log('已登录，未付费ssssss')
              next({
                path:'/pay-circle-login'
              });
           }
         } else if(siteMode == 'public'){
           //站点为公开站点时
           // console.log('公开站点，已登录');
           //当用户已登录，且站点为公开站点时，进入到路由页面
            next({
              path:to.fullPath
            });
         } else {

         }
        next();
      });

    } else {
      console.log('未登录，token不存在');
      // console.log(siteMode+'123456')
       //请求站点信息，用于判断站点是否是付费站点
        if(siteMode == 'pay'){
          console.log(7777);
          // console.log(to);
          // console.log(8888);
          //站点为付费站点时，跳转到付费页，如果是登录注册页，跳转到登录注册
          var ifLogin = to.fullPath.indexOf("login");
          var ifSign = to.fullPath.indexOf("sign");
          if(ifLogin != -1){
            // console.log('d登录页');
            next({
              path:'/login-user'
            });
          } else if(ifSign != -1){
            // console.log('注册页');
            next({
              path:'/sign-up'
            });
          } else {
            console.log('首页');
            next({
              path:'/pay-circle'
            });
          }
        } else if(siteMode == 'public'){
          //站点为公开站点时
          //当用户未登录，且站点为公开站点时，进入到路由页面
          // console.log('当用户未登录，且站点为公开站点时，进入到路由页面');
          // console.log(to.fullPath)
           next({
             path:to.fullPath
           });
        } else {
          //当siteMode为其他值（undefined,null）

        }
      };
    })
    next();










    console.log('pc登录');
  }







  },

};
