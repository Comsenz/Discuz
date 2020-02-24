/**
 * 付费站点-已支付-未登录控制器
 */
import Header from '../../../view/m_site/common/headerView';
import browserDb from '../../../../../helpers/webDbHelper';
export default {
  data: function() {
    return {
      headOpeShow: false,
      isfixNav: false,
      current:0,
      siteInfo: false,
      siteUsername:'',  //站长
      joinedAt:'',    //加入时间
      sitePrice:'',   //加入价格
      username:'' ,   //当前用户名
      loading: false,  //是否处于加载状态
      finished: false, //是否已加载完所有数据
      isLoading: false, //是否处于下拉刷新状态
      pageIndex: 1,//页码
      pageLimit: 20,
      offset: 100, //滚动条与底部距离小于 offset 时触发load事件
      loginUserInfo:''
    }
  },
  components:{
    Header
  },
  created(){
    this.getInfo();
    this.getUsers();
  },
  methods: {
    getInfo(initStatus = false){
      //请求站点信息，用于判断站点是否是付费站点
     return this.appFetch({
        url: 'forum',
        method: 'get',
        data: {
          include: ['users'],
        }
      }).then((res) => {
        if (res.errors){
          this.$toast.fail(res.errors[0].code);
          throw new Error(res.error)
        }else{
        if(initStatus){
          this.siteInfo= []
        }
        console.log(res);
        this.siteInfo = res.readdata;
        console.log(res.readdata._data.set_site.site_mode+'请求');
        if(res.readdata._data.set_site.site_author){
          this.siteUsername = res.readdata._data.set_site.site_author.username;;
          console.log(res.readdata._data.set_site.site_author.username,'用户名')
        } else {
          this.siteUsername = '暂无站长信息';
        }
        this.sitePrice = res.readdata._data.set_site.site_price;
      }
      });
    },
    //退出登录
    signOut(){
      browserDb.removeLItem('tokenId');
      browserDb.removeLItem('Authorization');
      this.$router.push({ path:'/login-user'});
    },

    // onBridgeReady(data){
    //   let that = this;

    //   WeixinJSBridge.invoke(
    //     'getBrandWCPayRequest', {
    //       "appId":data.data.attributes.wechat_js.appId,     //公众号名称，由商户传入
    //       "timeStamp":data.data.attributes.wechat_js.timeStamp,         //时间戳，自1970年以来的秒数
    //       "nonceStr":data.data.attributes.wechat_js.nonceStr, //随机串
    //       "package":data.data.attributes.wechat_js.package,
    //       "signType":"MD5",         //微信签名方式：
    //       "paySign":data.data.attributes.wechat_js.paySign //微信签名
    //     },
    //     function(res){
    //       // alert('支付唤醒');
    //       // if (res.err_msg == "get_brand_wcpay_request:ok") {
    //       //   alert("支付成功");
    //       //   alert(res.err_msg);
    //       //   resolve;
    //       // } else if (res.err_msg == "get_brand_wcpay_request:cancel") {
    //       //   alert("支付过程中用户取消");             //支付取消正常走
    //       //   alert(res.err_msg);
    //       //   resolve;
    //       // } else if (res.err_msg == "get_brand_wcpay_request:fail") {
    //       //   alert("支付失败");
    //       //   alert(res.err_msg);
    //       //   resolve;
    //       // }

    //     });

    //   setTimeout(()=>{
    //     const toast = that.$toast.loading({
    //       duration: 0, // 持续展示 toast
    //       forbidClick: true,
    //       message: '支付状态查询中...'
    //     });
    //     let second = 5;
    //     const timer = setInterval(() => {
    //       second--;
    //       this.getUsers(that.tokenId).then(res=>{
    //         console.log(second);

    //         if (res.errors){
    //           clearInterval(timer);
    //           toast.message = '支付失败，请重新支付！';
    //           setTimeout(()=>{
    //             toast.clear();
    //           },2000)
    //         } else {
    //           if (second > 0 || !res.readdata._data.paid){
    //             toast.message = '正在查询订单...';
    //           } else if (res.readdata._data.paid){
    //             clearInterval(timer);
    //             toast.message = '支付成功，正在跳转首页...';
    //             toast.clear();
    //             that.$router.push({path:'/'});
    //           } else {
    //             clearInterval(timer);
    //             toast.message = '支付失败，请重新支付！';
    //             toast.clear();
    //           }
    //         }
    //       });
    //     }, 1000);
    //   },3000);

    // },

    onBridgeReady(data){
      let that = this;
      WeixinJSBridge.invoke(
        'getBrandWCPayRequest', {
          "appId":data.data.attributes.wechat_js.appId,     //公众号名称，由商户传入
          "timeStamp":data.data.attributes.wechat_js.timeStamp,         //时间戳，自1970年以来的秒数
          "nonceStr":data.data.attributes.wechat_js.nonceStr, //随机串
          "package":data.data.attributes.wechat_js.package,
          "signType":"MD5",         //微信签名方式：
          "paySign":data.data.attributes.wechat_js.paySign //微信签名
        })

       const payWechat = setInterval(()=>{
         if (this.payStatus == '1' || this.payStatusNum > 10){
           clearInterval(payWechat);
         }
         this.getOrderStatus();
       },3000)

    },


    //付费，获得成员权限
    payClick(){
      let isWeixin = this.appCommonH.isWeixin().isWeixin;
      let isPhone = this.appCommonH.isWeixin().isPhone;

      if (isWeixin){
        console.log('微信');
        this.getOrderSn().then(()=>{
          this.orderPay(12).then((res)=>{
            if (typeof WeixinJSBridge == "undefined"){
              if( document.addEventListener ){
                document.addEventListener('WeixinJSBridgeReady', this.onBridgeReady(res), false);
              }else if (document.attachEvent){
                document.attachEvent('WeixinJSBridgeReady', this.onBridgeReady(res));
                document.attachEvent('onWeixinJSBridgeReady', this.onBridgeReady(res));
              }
            }else{
              this.onBridgeReady(res);
            }
          })
        });
      } else if (isPhone){
        console.log('手机浏览器');
        this.getOrderSn().then(()=>{
          this.orderPay(11).then((res)=>{
            this.wxPayHref = res.readdata._data.wechat_h5_link;
            window.location.href = this.wxPayHref;
            const payPhone = setInterval(()=>{
              if (this.payStatus == '1' || this.payStatusNum > 10){
                clearInterval(payPhone);
              }
              this.getOrderStatus();
            },3000)
          })
        });
      } else {
        console.log('pc');
        this.getOrderSn().then(()=>{
          this.orderPay(10).then((res)=>{
            console.log(res);
            this.codeUrl = 'data:image/jpg;base64,' + res.readdata._data.wechat_qrcode;
            this.qrcodeShow = true;

            if (this.payStatus && this.payStatusNum < 10){
              clearInterval(pay);
            }else {
              var pay = setInterval(()=>{
                // this.getUsersInfo()
                this.getOrderStatus();
              },3000)
            }

          })
        });
      }
    },

    // completePayment(){
    //  this.getUsers(this.tokenId).then(res=>{
    //    if (res.errors){
    //      this.$toast.message = '支付失败，请重新支付！';
    //    } else {
    //      if (res.readdata._data.paid){
    //        this.$toast.message = '支付成功，正在跳转首页...';
    //        this.dialogShow = false;
    //      } else {
    //        this.$toast.message = '支付失败，请重新支付！';
    //      }
    //    }
    //  })
    // },

    getOrderSn(){
      return this.appFetch({
        url:'orderList',
        method:'post',
        data:{
          "type":1
        }
      }).then(res=>{
        console.log(res);
        this.orderSn = res.readdata._data.order_sn;
      }).catch(err=>{
        console.log(err);
      })
    },
    orderPay(type){
      return this.appFetch({
        url:'orderPay',
        method:'post',
        splice:'/' + this.orderSn,
        data:{
          "payment_type":type
        }
      }).then(res=>{
        console.log(res);
        return res;
      }).catch(err=>{
        console.log(err);
      })
    },

    getOrderStatus(){
      return this.appFetch({
        url:'order',
        method:'get',
        splice:'/' + this.orderSn,
        data:{
        },
      }).then(res=>{
        console.log(res);
        // const orderStatus = res.readdata._data.status;
        if (res.errors){
          this.$toast.fail(res.errors[0].code);
          throw new Error(res.error)
        } else {
          this.payStatus = res.readdata._data.status;
          console.log(res.readdata._data.status);
          this.payStatusNum ++;
          // console.log(this.payStatusNum);
          if (this.payStatus == '1' || this.payStatusNum > 10){
            this.rewardShow = false;
            this.qrcodeShow = false;
            this.rewardedUsers.push({_data:{avatarUrl:this.currentUserAvatarUrl,id:this.userId}});
            console.log(this.rewardedUsers);
            this.payStatusNum = 11;
            // this.detailsLoad(true);
            // console.log('重新请求');
            clearInterval(pay);
          }
        }

        // return res;
      })
    },

    //跳转到登录页
    loginJump:function(){
      this.$router.push({ path:'login-user'})
    },
    //跳转到注册页
    registerJump:function(){
      this.$router.push({ path:'sign-up'})
    },
    onRefresh(){    //下拉刷新
      this.pageIndex = 1;
      this.getInfo(true).then(()=>{
        this.$toast('刷新成功');
        this.finished = false;
        this.isLoading = false;
      }).catch((err)=>{
        this.$toast('刷新失败');
        this.isLoading = false;
      })
    },
    getUsers(){
      return this.appFetch({
        url:'users',
        method:'get',
        splice:'/' + browserDb.getLItem('tokenId'),
        data:{
          // include:['groups']
        }
      }).then(res=>{
        console.log(res);
        if (res.errors){
          this.$toast.fail(res.errors[0].code);
        } else {
          this.loginUserInfo = res.data.attributes.username
        }
      }).catch(err=>{
        console.log(err);
      })
    }

  },

  mounted: function() {
    // this.getVote();
    window.addEventListener('scroll', this.handleTabFix, true);
  },
  beforeRouteLeave (to, from, next) {
     window.removeEventListener('scroll', this.handleTabFix, true)
     next()
  }
}
