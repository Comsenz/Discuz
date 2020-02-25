/**
 * 付费站点分享页控制器
 */
import browserDb from '../../../../../helpers/webDbHelper';
export default {
	data: function() {
		return {
      thread:{},
	    sitePrice:'',   //加入价格
	    loading: false,  //是否处于加载状态
      finished: false, //是否已加载完所有数据
      isLoading: false, //是否处于下拉刷新状态
      pageIndex: 1,//页码
      pageLimit: 20,
      offset: 100, //滚动条与底部距离小于 offset 时触发load事件
      thread:false,
      themeCon:[],
      limitList:'',
      allowRegister: '',
      token:'',
      alreadyLogin: '',
      loginName: '',
      amountNum:'',      //支付价钱
      codeUrl:"",        //支付url，base64
      qrcodeShow:false,  //pc端显示二维码
      payList:[
        {
          name:'钱包',
          icon:'icon-weixin'
        }
      ],     //支付方式
      show:false,        //是否显示支付方式
      errorInfo:'',      //密码错误提示
      value:'',          //密码
      walletBalance:'',   //钱包余额
      userDet: '',
      
		}
	},
  computed: {
    themeId: function(){
        return this.$route.params.themeId;
    },
    groupId: function(){
        return this.$route.params.groupId;
    },
    
  },
  created(){
    if(browserDb.getLItem('tokenId')){
      this.getUsers(browserDb.getLItem('tokenId')).then(res=>{
        this.walletBalance = res.readdata._data.walletBalance;
      });
    }
    
    this.tokenId = browserDb.getLItem('tokenId');
    this.amountNum = browserDb.getLItem('siteInfo')._data.set_site.site_price;
    this.token = browserDb.getLItem('Authorization');
    this.loginName = browserDb.getLItem('foregroundUser');
    if(this.token){
      this.alreadyLogin = true;
    } else {
      this.alreadyLogin = false;
    }
    this.myThread();
    // this.sitePrice = browserDb.getLItem('siteInfo')._data.set_site.site_price;
    this.getInfo();
    this.getUsersInfo();
    
  },
  methods: {
    getInfo(){
      //请求站点信息，用于判断站点是否是付费站点
      this.appFetch({
        url: 'forum',
        method: 'get',
        data: {
          include: ['users'],
        }
      }).then((res) => {
        if (res.errors){
          this.$toast.fail(res.errors[0].code);
          throw new Error(res.error)
        } else {
          console.log(res,'123456');
          this.siteInfo = res.readdata;
          // console.log(res.readdata._data.siteMode+'请求');
          if(res.readdata._data.set_site.site_author){
            this.siteUsername = res.readdata._data.set_site.site_author.username;
          } else {
            this.siteUsername = '暂无站长信息';
          }
          if (res.readdata._data.paycenter.wxpay_close === '1'){
            this.payList.unshift( {
              name:'微信支付',
              icon:'icon-money'
            })
          }
          this.sitePrice = res.readdata._data.set_site.site_price;
          this.allowRegister = res.readdata._data.set_reg.register_close;
        }
      });

      //请求权限列表数据
      this.appFetch({
        url: 'groups',
        method: 'get',
        data: {
          'filter[isDefault]': '1',
          include: ['permission'],
        }
      }).then((res) => {
        if (res.errors){
          this.$toast.fail(res.errors[0].code);
          throw new Error(res.error)
        }else{
        if (res.errors){
          this.$toast.fail(res.errors[0].code);
          throw new Error(res.error)
        }else{
        console.log('000000');
        console.log(res);
        this.limitList = res.readdata[0];
        }
      }
      });


    },

    myThread(initStatus = false){
     this.appFetch({
        url:'shareThreads',
        method:'get',
        splice:'/'+this.themeId,
        data:{
        }
      }).then(res=>{
        if (res.errors){
          this.$toast.fail(res.errors[0].code);
          throw new Error(res.error)
        }else{
          if(initStatus){
            this.thread=[]
          }
          console.log('123');
          console.log(res)
          this.thread = res.readdata;
          console.log(this.thread._data.createdAt);
          console.log('567');
        }
      })
    },
		//跳转到登录页
		loginJump:function(){
			this.$router.push({ path:'/login-user'})
		},
		//跳转到注册页
		registerJump:function(){
			this.$router.push({ path:'/sign-up'})
		},
		onRefresh(){    //下拉刷新
			this.pageIndex = 1;
			this.myThread(true).then(()=>{
			  this.$toast('刷新成功');
			  this.finished = false;
			  this.isLoading = false;
			}).catch((err)=>{
			  this.$toast('刷新失败');
			  this.isLoading = false;
			})
    },
    //退出登录
    signOut(){
      browserDb.removeLItem('tokenId');
      browserDb.removeLItem('Authorization');
      // this.$router.push({ path:'/login-user'});
      this.alreadyLogin = false;
    },

    getOrderSn(){
      return this.appFetch({
        url:'orderList',
        method:'post',
        data:{
          "type":1
        }
      }).then(res=>{
        console.log(res);
        if (res.errors){
          this.$toast.fail(res.errors[0].code);
        } else {
          this.orderSn = res.readdata._data.order_sn;
        }
      }).catch(err=>{
        console.log(err);
      })
    },
    orderPay(type,value){
      return this.appFetch({
        url:'orderPay',
        method:'post',
        splice:'/' + this.orderSn,
        data:{
          "payment_type":type,
          'pay_password':value
        }
      }).then(res=>{
        console.log(res);
        if (res.errors){
          this.$toast.fail(res.errors[0].code);
        } else {
          return res;
        }
      }).catch(err=>{
        console.log(err);
      })
    },
    getUsersInfo(){
      if(!browserDb.getLItem('tokenId')){
        return false;
      }
      this.appFetch({
        url:'users',
        method:'get',
        splice:'/' + browserDb.getLItem('tokenId'),
        data:{
          include:['groups']
        }
      }).then(res=>{
        console.log(res,'用户是否付费');
        console.log(res.readdata._data.paid);
        if (res.errors){
          this.$toast.fail(res.errors[0].code);
        } else {
          this.payStatus = res.readdata._data.paid;
          this.userDet = res.readdata;
          this.payStatusNum = +1;
          if (this.payStatus) {
            this.qrcodeShow = false;
            this.$router.push({path:'/details/' + this.themeId});
            this.payStatusNum = 11;
            // clearInterval(pay);
          }
        }
      }).catch(err=>{
        console.log(err);
      })
    },
    getUsers(id){
      return this.appFetch({
        url:'users',
        method:'get',
        splice:'/' + id,
        headers:{'Authorization': 'Bearer ' + browserDb.getLItem('Authorization')},
        data:{
          include:['groups']
        }
      }).then(res=>{
        if (res.errors){
          this.$toast.fail(res.errors[0].code);
        } else {
          console.log(res);
          return res;
        }
      }).catch(err=>{
        console.log(err);
      })
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
                toast.message = `正在查询订单...`;
              } else if (res.readdata._data.paid){
                clearInterval(timer);
                browserDb.setLItem('foregroundUser', res.data.attributes.username);
                toast.message = '支付成功，正在跳转首页...';
                toast.clear();

                let beforeVisiting = browserDb.getSItem('beforeVisiting');
                console.log(beforeVisiting);

                if (beforeVisiting) {
                  this.$router.push({path: beforeVisiting})
                } else {
                  this.$router.push({path: '/'})
                }
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

    payImmediatelyClick(data){
      //data返回选中项
      console.log(data);

      let isWeixin = this.appCommonH.isWeixin().isWeixin;
      let isPhone = this.appCommonH.isWeixin().isPhone;

      if (data.name === '微信支付') {
        this.show = false;
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
                if (this.payStatus && this.payStatusNum > 10){
                  clearInterval(payPhone);
                }
                this.getUsersInfo()
              },3000)

            })
          });
        } else {
          console.log('pc');
          this.getOrderSn().then(()=>{
            this.orderPay(10).then((res)=>{
              console.log(res);
              this.codeUrl = res.readdata._data.wechat_qrcode;
              this.qrcodeShow = true;
              const pay = setInterval(()=>{
                if (this.payStatus && this.payStatusNum > 10){
                  clearInterval(pay);
                }
                this.getUsersInfo()
              },3000)
            })
          });
        }
      }

    },

    onInput(key){
      console.log(key);
      this.value = this.value + key;

      if (this.value.length === 6 ) {
        this.errorInfo = '';
        this.getOrderSn().then(()=>{
          this.orderPay(20,this.value).then((res)=>{
            console.log(res);
            const pay = setInterval(()=>{
              if (this.payStatus && this.payStatusNum > 10){
                clearInterval(pay);
              }
              this.getUsersInfo()
            },3000)
          })
        })
      }
    },

    onDelete(){
      console.log("删除");
    },

    onClose(){
      console.log('关闭');
      this.value = '';
      this.errorInfo = ''
    },





    //付费，获得成员权限
    payClick(){
      this.show = !this.show;
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
