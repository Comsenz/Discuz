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
      css: ['/css/reset.css'],
      // js: ['/js/rem.js'],

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
      'pay-circle-con/:themeId/:groupId':{
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
      'edit-topic/:themeId':{
        comLoad:function (resolve) {
          require(['../view/m_site/home/editTopicView'],resolve)
        },
        metaInfo:{
          title:"编辑主题"
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
        },
      },
        'bind-new-phone':{
          comLoad:function (resolve) {
            require(['../view/m_site/myInfo/bindNewPhoneView'],resolve)
        },
        metaInfo:{
          title:"绑定新手机号"
        },
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

  /*
  * 登录且付费不能访问的页面列表
  * */
  const noLoginPage = [
    'login-user',
    'login-phone',
    'sign-up',
    'wx-login-bd',
    'retrieve-pwd',
    'bind-phone',
    'retrieve-pwd',
    'pay-the-fee',
    'pay-circle-con/:themeId/:groupId',
    'pay-circle-login',
    'pay-circle',
    'pay-circle-con/:themeId'
  ];

  /*
  * 登录成功状态，不能访问的页面列表
  * */
  const noLoginAccessPage = [
    'login-user',
    'login-phone',
    'sign-up',
    'retrieve-pwd'
  ];

  /*
  * 获取用户第一次访问页面，登录后跳转回来
  * */
  if (!browserDb.getSItem('beforeVisiting')){
    browserDb.setSItem('beforeVisiting',to.path);
  }

  /*
  * 获取tokenId
  * */
  const tokenId = browserDb.getLItem('tokenId');
  const Authorization = browserDb.getLItem('Authorization');

  /*
  * 前台路由全局处理
  * */
  var registerClose = ''; //站点是否关闭
  var siteMode = '';      //站点模式

  /*
  * 注册关闭，未登录状态，进入注册页面后跳转到对应的站点页面
  * */
  if (to.name === 'sign-up'){
  this.getForum().then((res)=>{
    registerClose = res._data.setreg.register_close;
    siteMode = res._data.setsite.site_mode;
    if (!Authorization && !tokenId && !registerClose) {
      if (siteMode === 'pay'){
        next({path:'/pay-circle'});
        return
      } else {
        next({path:'/'});
        return
      }
    }
  })

  } else {
    next();
  }


  /*
  * 前台登录状态后，不能访问未登录状态的页面
  * */
  if (tokenId && Authorization){
    if (noLoginAccessPage.includes(to.name)){
      next({path:'/'});
      return;
    }else {
      next();
    }
  } else {
    console.log('前台未登录，跳转');
    next();
    return;
  }




  if (isWeixin == true) {
    //微信登录时
    console.log(to);
    console.log('微信登录');

    if (!browserDb.getLItem('Authorization') && !browserDb.getLItem('tokenId')){
      if(to.name === 'wx-login-bd') {
        next();
        return
      } else {
        next();
      }
      next({path:'/wx-login-bd'});

      /*if(localStorage.getItem('officeDb_code')) {
        console.log('跳转微信绑定');
        next();
        return
      } else {
        console.log('获取code');
        next(false);
        return;
      }

      next({path:'/wx-login-bd'});*/
      console.log('未登录');
    } else {

      if (tokenId)  {
        this.getUsers(tokenId).then(res => {

          if (res) {
            /*
            * 登录成功，且付费后，判断路由不能进未登录和付费页面
            * bug：路由跳转前需要在显示页面前直接跳转，不然会触发页面的方法
            * */
            if (noLoginPage.includes(to.name)){
              console.log(form.fullPath);
              next(form.fullPath);
              /*next(vm => {
                // vm.$router.go(-1);
              })*/
              console.log('符合');
            } else {
              console.log('不符合');
              next();
              return
            }

            if (to.fullPath !== browserDb.getSItem('beforeVisiting')){
              next();
            } else {
              next({path: browserDb.getSItem('beforeVisiting')});
            }

          } else {

            let siteMode = browserDb.getLItem('siteInfo')._data.setsite.site_mode;

            if (siteMode === 'pay') {
              if(to.name === 'pay-circle-logi') {
                next();
                return
              } else {
                next();
              }
              next({path: 'pay-circle-login'});
            } else if (siteMode === 'public') {
              if(to.name === '/') {
                next();
                return
              } else {
                next();
              }
              next({path: '/'});
            } else {
              console.log("路由守卫缺少参数，请刷新页面");
            }
          }

        });
      } else {
        /*
        * 获取站点模式，需要forum接口
        * */
        /*if (this.siteMode === 'pay') {
          this.$router.push({path: 'pay-circle-login'});
        } else if (this.siteMode === 'public') {
          this.$router.push({path: '/'});
        } else {
          console.log("缺少参数，请刷新页面");
        }*/
        next();
        return
      }
      /*if(to.path === '/'){
        next();
      } else {
        next();
      }*/
      /*if(to.path !== browserDb.getSItem('beforeVisiting')){
        next();
      } else {
        /!*
        *  用户登录后，跳转第一次访问页面
        * *!/
        if(to.path === browserDb.getSItem('beforeVisiting')){
          next();
        } else {
          next();
        }
        next({path:browserDb.getSItem('beforeVisiting')});
      }*/

      console.log('已经登录');
    }

  } else if(isPhone == true) {
    //手机浏览器登录时
    console.log('手机浏览器登录');
    console.log(to);
    // 基准大小
    const baseSize = 37.5;  //需要跟.postcssrc.js’rootValue‘属性统一大小
    // 设置 rem 函数
    function setRem () {
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
    if (noLoginPage.includes(to.name)){
      console.log(form.fullPath);
      next();
      /*next(vm => {
        // vm.$router.go(-1);
      })*/
      console.log('符合');
    } else {
      console.log('不符合');

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
      return
    }

    /*let authVal = browserDb.getLItem('Authorization');
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
    next();*/
  } else {
    console.log('pc');
    // 基准大小
    const baseSize = 24;  //需要跟.postcssrc.js’rootValue‘属性统一大小
    // 设置 rem 函数
    function setRem () {
      // 当前页面宽度相对于 640 宽的缩放比例，可根据自己需要修改。
      const scale = document.documentElement.clientWidth / 240;
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
    document.getElementsByTagName("html")[0].style.width  = "640px";
    let viewportWidth = window.innerWidth;
    document.getElementsByTagName("body")[0].style.marginLeft = (viewportWidth - 640)/2+'px';
    console.log('3456543');
    if (noLoginPage.includes(to.name)){
      console.log(form.fullPath);
      next();
      /*next(vm => {
        // vm.$router.go(-1);
      })*/
      console.log('pc符合');
    } else {
      console.log('pc不符合');

      let authVal = browserDb.getLItem('Authorization');
      let siteMode = '';
      let isPaid = '';
      var pro1 = new Promise(function (resolve, reject) {
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
        if (authVal) { //判断本地是否存在access_token
          // console.log('已登录，token已存在');
          var pro2 = new Promise(function (resolve, reject) {
            //请求站点信息接口，判断站点是否付费
            var userId = browserDb.getLItem('tokenId');
            console.log(browserDb.getLItem('tokenId'));
            appFetch({
              url: 'users',
              method: 'get',
              splice: '/' + userId,
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
            console.log(siteMode + '23232323');
            if (siteMode == 'pay') {
              //站点为付费站点时
              if (isPaid == true) {
                //当用户已付费时
                console.log(to);
                // console.log('当前用户已登录已付费时');
                next({
                  path: to.fullPath
                });
              } else {
                // console.log('已登录，未付费ssssss')
                next({
                  path: '/pay-circle-login'
                });
              }
            } else if (siteMode == 'public') {
              //站点为公开站点时
              // console.log('公开站点，已登录');
              //当用户已登录，且站点为公开站点时，进入到路由页面
              next({
                path: to.fullPath
              });
            } else {

            }
            next();
          });

        } else {
          console.log('未登录，token不存在');
          // console.log(siteMode+'123456')
          //请求站点信息，用于判断站点是否是付费站点
          if (siteMode == 'pay') {
            console.log(7777);
            // console.log(to);
            // console.log(8888);
            //站点为付费站点时，跳转到付费页，如果是登录注册页，跳转到登录注册
            var ifLogin = to.fullPath.indexOf("login");
            var ifSign = to.fullPath.indexOf("sign");
            if (ifLogin != -1) {
              // console.log('d登录页');
              next({
                path: '/login-user'
              });
            } else if (ifSign != -1) {
              // console.log('注册页');
              next({
                path: '/sign-up'
              });
            } else {
              console.log('首页');
              next({
                path: '/pay-circle'
              });
            }
          } else if (siteMode == 'public') {
            //站点为公开站点时
            //当用户未登录，且站点为公开站点时，进入到路由页面
            // console.log('当用户未登录，且站点为公开站点时，进入到路由页面');
            // console.log(to.fullPath)
            next({
              path: to.fullPath
            });
          } else {
            next();
            //当siteMode为其他值（undefined,null）

          }
        }
        ;
      });
      next();

      console.log('pc登录');
    }
  }




  },

  /*
  * 接口请求
  * */
  getUsers(id){
    return appFetch({
      url:'users',
      method:'get',
      splice:'/' + id,
      headers:{'Authorization': 'Bearer ' + browserDb.getLItem('Authorization')},
      data:{
        include:['groups']
      }
    }).then(res=>{
      console.log(res);
      return res.readdata._data.paid;
    }).catch(err=>{
      console.log(err);
    })
  },
  getForum(){
    return appFetch({
      url:'forum',
      method:'get',
      data:{}
    }).then(res=>{
      console.log(res);
      browserDb.setLItem('siteInfo',res.readdata);
      return res.readdata;
    }).catch(err=>{
      console.log(err);
    })
  },

};
