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
		}
	},
	components:{
    Header
  },
  created(){
    this.getInfo();
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
        console.log(res.readdata._data.siteMode+'请求');
        if(res.readdata._data.siteAuthor){
          this.siteUsername = res.readdata._data.siteAuthor.username;
          console.log(res.readdata._data.siteAuthor.username,'用户名')
        } else {
          this.siteUsername = '暂无站长信息';
        }
        this.sitePrice = res.readdata._data.sitePrice
      }
      });
    },
    //退出登录
    signOut(){
      browserDb.removeLItem('tokenId');
      browserDb.removeLItem('Authorization');
      this.$router.push({ path:'/login-user'});
    },

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
        },
        function(res){
          // alert('支付唤醒');
          // if (res.err_msg == "get_brand_wcpay_request:ok") {
          //   alert("支付成功");
          //   alert(res.err_msg);
          //   resolve;
          // } else if (res.err_msg == "get_brand_wcpay_request:cancel") {
          //   alert("支付过程中用户取消");             //支付取消正常走
          //   alert(res.err_msg);
          //   resolve;
          // } else if (res.err_msg == "get_brand_wcpay_request:fail") {
          //   alert("支付失败");
          //   alert(res.err_msg);
          //   resolve;
          // }

        });

      setTimeout(()=>{
        const toast = that.$toast.loading({
          duration: 0, // 持续展示 toast
          forbidClick: true,
          message: '支付状态查询中...'
        });
        let second = 5;
        const timer = setInterval(() => {
          second--;
          this.getUsers(that.tokenId).then(res=>{
            console.log(second);

            if (res.errors){
              clearInterval(timer);
              toast.message = '支付失败，请重新支付！';
              setTimeout(()=>{
                toast.clear();
              },2000)
            } else {
              if (second > 0 || !res.readdata._data.paid){
                toast.message = '正在查询订单...';
              } else if (res.readdata._data.paid){
                clearInterval(timer);
                toast.message = '支付成功，正在跳转首页...';
                toast.clear();
                that.$router.push({path:'/'});
              } else {
                clearInterval(timer);
                toast.message = '支付失败，请重新支付！';
                toast.clear();
              }
            }
          });
        }, 1000);
      },3000);

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
                this.getUsersInfo()
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

    // //生成订单成功后支付
    // orderPay(orderSn,amount){
    //   // console.log(amount+'101010');
    //   let isWeixin =this.appCommonH.isWeixin().isWeixin;
    //   let isPhone =this.appCommonH.isWeixin().isPhone;
    //   // console.log(isWeixin+'1111')
    //   // console.log(isPhone+'2222')
    //   let payment_type = '';
    //   if(isWeixin == true){
    //     //微信登录时
    //     alert('微信支付');
    //     // this.appFetch({
    //     //   url:"weixin",
    //     //   method:"get",
    //     //   data:{
    //     //   }
    //     // }).then(data=>{
    //     //   console.log(data.data.attributes.location)
    //     //   window.location.href = data.data.attributes.location;
    //     // });
    //     payment_type = "12";
    //   } else if( isPhone == true) {
    //     //手机浏览器登录时
    //     // console.log('手机浏览器登录');
    //      payment_type = "11";
    //   } else {
    //     payment_type = "10";
    //     // console.log('pc登录');
    //   }
    //   let orderPay = 'trade/pay/order/'+orderSn;
    //   this.appFetch({
    //     url:orderPay,
    //     method:"post",
    //     data:{
    //           'payment_type':payment_type
    //     },
    //   }).then(data =>{
    //     // console.log(data);
    //     if(isWeixin){
    //       //如果是微信支付
    //        // console.log(data.data.attributes.wechat_js);
    //     } else if(isPhone) {
    //       //如果是h5支付
    //       // console.log(data.data.attributes.wechat_h5_link);
    //       window.location.href = data.data.attributes.wechat_h5_link;
    //     } else {
    //       // console.log('pc');
    //       //如果是pc支付
    //       // console.log(data.data.attributes.wechat_qrcode);
    //       this.qrcodeShow = true;
    //       // console.log(this.qrcodeShow);
    //       this.amountNum = amount;
    //       // console.log(this.amountNum);
    //       this.codeUrl= data.data.attributes.wechat_qrcode;
    //     }

    //   })
    // },



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
