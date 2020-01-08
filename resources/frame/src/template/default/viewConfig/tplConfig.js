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
      },
      'pay-status':{
        comLoad:function (resolve) {
          require(['../view/m_site/common/pay/payView'],resolve)
        },
        metaInfo:{
          title:"支付订单查询"
        }
      },
      'site-close':{
        comLoad:function (resolve) {
          require(['../view/m_site/common/siteClose'],resolve)
        },
        metaInfo:{
          title:"站点关闭提示"
        }
      },
      'supplier-all-back':{
        comLoad:function (resolve) {
          require(['../view/m_site/common/supplierAllBack'],resolve)
        },
        metaInfo:{
          title:"空白页"
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
  const signInAndPayForAccess = [
    'login-user',
    'login-phone',
    'sign-up',
    'wx-login-bd',
    'retrieve-pwd',
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
  const successfulLoginForbiddenPage = [
    'login-user',
    'login-phone',
    'sign-up',
    'retrieve-pwd'
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
    'open-circle',
    'details/:themeId',
    'home-page/:userId',
    'pay-status',
    'wx-login-bd'
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
  var registerClose = ''; //注册是否关闭
  var siteMode = '';      //站点模式

  /*
  * 注册关闭，未登录状态，进入注册页面后跳转到对应的站点页面
  * */
  if (to.name === 'sign-up'){
  this.getForum().then((res)=>{
    registerClose = res.readdata._data.setreg.register_close;
    siteMode = res.readdata._data.setsite.site_mode;
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
   * 站点关闭，跳转到站点关闭页面
   * */
  this.getForum().then((res)=>{
    if (res.errors){
      if (res.rawData[0].code === 'site_closed'){
        if (to.name === 'login-user'){
          next();
        } else {
          next({path:'/site-close'});
          return
        }
      }
    }else{
       siteMode = res.readdata._data.setsite.site_mode;
    }

  });


  /*
  * 路由过渡设置
  * */
  // if (siteMode === 'public'){
  //   if (!notLoggedInToAccessPage.includes(to.name)){
  //     if (to.name == 'supplier-all-back'){
  //       next();
  //       return
  //     }
  //     next({path:'/supplier-all-back',query:{url:to.name}});
  //   }
  // } else {
  //   if (!notLoggedInToAccessPage.includes(to.name) && to.name !== 'circle'){
  //     if (to.name == 'supplier-all-back'){
  //       next();
  //       return
  //     }
  //     next({path:'/supplier-all-back',query:{url:to.name}});
  //   }
  // }


  /*
  * 前台路由前置判断
  * 判断登录状态
  * */
  if (tokenId && Authorization){
    /*已登录状态*/

    this.getForum().then(ress=>{
      // if (ress.errors[0].code === 'site_closed'){
      //   next({path:'/site-close'})
      //   return
      // }

      if (ress.readdata._data.setsite.site_mode === 'pay'){

        this.getUsers(tokenId).then(res=>{
          /*获取用户付费状态并判断*/
          if (res){
            /*付费状态下，用户已付费可以任意访问，但不能访问未登录可以访问的页面*/
            if (signInAndPayForAccess.includes(to.name)){
              next(vm=>{
                vm.$router.go(-1);
              })
            }else {
              next();
            }
          }else {
            if (notLoggedInToAccessPage.includes(to.name)){
              next();
            }else {
              if(to.name === 'pay-circle-login'){
                next();
                return
              }
              next({path:'pay-circle-login'});
            }
          }
        })

      } else {

        if (signInAndPayForAccess.includes(to.name)){
          console.log(form);
          // next(form.path)
          next('/')
        }else {
          next();
        }

        /*this.getUsers(tokenId).then(res=>{
          console.log(res);
          if (res){
            if (signInAndPayForAccess.includes(to.name)){
              console.log(form);
              // next(form.path)
              next('/')
            }else {
              next();
            }
          }else {
            if (notLoggedInToAccessPage.includes(to.name)){
              next();
            }else {
              if(to.name === '/'){
                next();
                return
              }
              next({path:'/'});
            }
          }
        })*/

      }

    })


  } else {
    /*未登录状态*/

    /*判断登录设备*/
    if (isWeixin){
      /*微信设备，跳转到微信绑定页*/
      if(to.name === 'wx-login-bd') {
        next();
        return
      } else {
        next();
      }
      next({path:'/wx-login-bd'});
    } else {
      if (notLoggedInToAccessPage.includes(to.name)){
        /*符合，未登录可以访问站点*/
        console.log('符合');
        next();
      }else {
        /*不符合，跳转到未登录，可访问站点*/
        this.getForum().then(res=>{
          // if (res.errors[0].code === 'site_closed'){
          //   next({path:'/site-close'})
          //   return
          // }
          /*判断站点模式*/
          if (res.readdata._data.setsite.site_mode === 'pay'){
            if(to.name === 'pay-circle'){
              next();
              return
            }
            next({path:'pay-circle'});
          }else {
            if (to.name === '/'){
              next();
              return
            }
            next('/')
          }
        })
      }
    }

  }


  /*
  * 判断设备显示不同的尺寸
  * */
  if(isWeixin){

  }else if (isPhone){
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
  }else {
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
      return res;
    }).catch(err=>{
      console.log(err);
    })
  },

};
