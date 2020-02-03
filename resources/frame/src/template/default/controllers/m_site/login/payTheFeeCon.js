
import PayHeader from '../../../view/m_site/common/loginSignUpHeader/loginSignUpHeader'
import "../../../scss/var.scss";
import webDb from '../../../../../helpers/webDbHelper';

export default {
  data:function () {
    return {
      sitePrice:'',     //付费站点价钱
      siteExpire:'',    //到期时间
      orderSn:'',       //订单号
      wxPayHref:'',     //微信支付链接
      qrcodeShow:false,
      codeUrl:"",        //支付url，base64
      amountNum:'',      //支付价钱
      payStatus:false,   //支付状态
      payStatusNum:0,    //支付状态次数
      authorityList:'',  //权限列表
      tokenId:'',        //用户ID
      dialogShow:false,  //微信支付确认弹框
      groupId:'',        //用户组ID
      limitList:[]       //用户组权限
    }
  },

  components:{
    PayHeader
  },

  methods:{
    leapFrogClick(){
      this.$router.push({path:'pay-circle-login'})
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
    },

    completePayment(){
     this.getUsers(this.tokenId).then(res=>{
       if (res.errors){
         this.$toast.message = '支付失败，请重新支付！';
       } else {
         if (res.readdata._data.paid){
           this.$toast.message = '支付成功，正在跳转首页...';
           this.dialogShow = false;
         } else {
           this.$toast.message = '支付失败，请重新支付！';
         }
       }
     })
    },

    /*groupListDealWith(key){

      const config = {
        default: '默认权限',
        viewThreads:'查看主题列表',
        thread.viewPosts:'查看主题',
        createThread:'发表主题',
        thread.reply:'回复主题'
      };

      return config[key] ? config[key] : config['default'];
    },*/


    /*
    * 接口请求
    * */
    getForum(){
      this.appFetch({
        url:'forum',
        method:'get',
        data:{}
      }).then(res=>{
        console.log(res);
        if (res.errors){
          this.$toast.fail(res.errors[0].code);
        } else {
          this.sitePrice = res.readdata._data.setsite.site_price;
          let day = res.readdata._data.setsite.site_expire;
          switch (day) {
            case '':
              this.siteExpire = '永久有效';
              break;
            case '0':
              this.siteExpire = '永久有效';
              break;
            default:
              this.siteExpire = '有效期自加入起' + day + '天';
              break;
          }
        }
      }).catch(err=>{
        console.log(err);
      })
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
      this.appFetch({
        url:'users',
        method:'get',
        splice:'/' + webDb.getLItem('tokenId'),
        data:{
          include:['groups']
        }
      }).then(res=>{
        console.log(res);
        console.log(res.readdata._data.paid);
        if (res.errors){
          this.$toast.fail(res.errors[0].code);
        } else {
          this.payStatus = res.readdata._data.paid;
          this.payStatusNum = +1;
          if (this.payStatus) {
            this.qrcodeShow = false;
            this.$router.push('/');
            this.payStatusNum = 11;
            clearInterval(pay);
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
        headers:{'Authorization': 'Bearer ' + webDb.getLItem('Authorization')},
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
    getAuthority(id){
      return this.appFetch({
        url:"authority",
        method:'get',
        splice:'/' + id,
        data:{
          include:['permission']
        }
      }).then(res=>{
        console.log(res);
        if (res.errors){
          this.$toast.fail(res.errors[0].code);
        } else {
          return res
        }
      }).catch(err=>{
        console.log(err);
      })
    },
    getGroups(){
      this.appFetch({
        url:'groups',
        method:'get',
        data:{
          include:['permission'],
          'filter[isDefault]':1
        }
      }).then(res=>{
        if(res.errors){
          this.$toast.fail(res.errors[0].code);
        } else {
          this.groupId = res.readdata[0]._data.id;
          this.getGroupsList();
        }
      })
    },
    getGroupsList(){
      this.appFetch({
        url: 'groups',
        method: 'get',
        splice:'/'+this.groupId,
        data: {
          include: ['permission'],
        }
      }).then((res) => {
        if(res.errors){
          this.$toast.fail(res.errors[0].code);
        } else {
          this.limitList = res.readdata;

          // res.readdata.forEach((item)=>{
          //   this.limitList.push(
          //     this.groupListDealWith(item._data.permission)
          //   )
          // })

        }
      });
    }

  },
  created(){
    this.getForum();
    this.getGroups();
    this.getUsers(webDb.getLItem('tokenId')).then(res=>{
      this.getAuthority(res.readdata.groups[0]._data.id)
    });
    this.tokenId = webDb.getLItem('tokenId');
    this.amountNum = webDb.getLItem('siteInfo')._data.setsite.site_price;
  }
}
