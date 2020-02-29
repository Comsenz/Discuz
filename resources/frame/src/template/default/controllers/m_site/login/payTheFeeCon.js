
/*
* 支付费用控制器
* */

import PayHeader from '../../../view/m_site/common/loginSignUpHeader/loginSignUpHeader';
import PayMethod from '../../../view/m_site/common/pay/paymentMethodView';
import webDb from '../../../../../helpers/webDbHelper';

export default {
  data:function () {
    return {
      sitePrice:'',      //付费站点价钱
      siteExpire:'',     //到期时间
      orderSn:'',        //订单号
      wxPayHref:'',      //微信支付链接
      qrcodeShow:false,  //pc端显示二维码
      codeUrl:"",        //支付url，base64
      amountNum:'',      //支付价钱
      payStatus:false,   //支付状态
      payStatusNum:0,    //支付状态次数
      authorityList:'',  //权限列表
      tokenId:'',        //用户ID
      dialogShow:false,  //微信支付确认弹框
      groupId:'',        //用户组ID
      limitList:[],      //用户组权限
      payList:[
        {
          name:'钱包',
          icon:'icon-wallet'
        }
      ],     //支付方式
      show:false,        //是否显示支付方式
      errorInfo:'',      //密码错误提示
      value:'',          //密码
      walletBalance:'',  //钱包余额
      walletStatus:''    //钱包支付密码状态
    }
  },

  components:{
    PayHeader,
    PayMethod
  },

  methods:{
    payImmediatelyClick(data){
      //data返回选中项

      let isWeixin = this.appCommonH.isWeixin().isWeixin;
      let isPhone = this.appCommonH.isWeixin().isPhone;

      if (data.name === '微信支付') {
        this.show = false;
        if (isWeixin){
          //微信
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
          //手机浏览器
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
          //pc
          this.getOrderSn().then(()=>{
            this.orderPay(10).then((res)=>{
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
      this.value = this.value + key;

      if (this.value.length === 6 ) {
        this.errorInfo = '';
        this.getOrderSn().then(()=>{
          this.orderPay(20,this.value).then((res)=>{
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
    //删除
    onDelete(){
    },
    //关闭
    onClose(){
      this.value = '';
      this.errorInfo = ''
    },

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
                webDb.setLItem('foregroundUser', res.data.attributes.username);
                this.show = false;
                toast.message = '支付成功，正在跳转首页...';
                toast.clear();

                let beforeVisiting = webDb.getSItem('beforeVisiting');

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

    payClick(){
      // this.show = !this.show;
      this.show = true;
    },

    /*
    * 接口请求
    * */
    getForum(){
      this.appFetch({
        url:'forum',
        method:'get',
        data:{}
      }).then(res=>{
        if (res.errors){
          this.$toast.fail(res.errors[0].code);
        } else {
          this.sitePrice = res.readdata._data.set_site.site_price;
          let day = res.readdata._data.set_site.site_expire;
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
          if (res.readdata._data.paycenter.wxpay_close === '1'){
            this.payList.unshift( {
              name:'微信支付',
              icon:'icon-wxpay'
            })
          }
        }
      }).catch(err=>{
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
        if (res.errors){
          this.$toast.fail(res.errors[0].code);
        } else {
          this.orderSn = res.readdata._data.order_sn;
        }
      }).catch(err=>{
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
        if (res.errors){
          this.$toast.fail(res.errors[0].code);
        } else {
          return res;
        }
      }).catch(err=>{
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
        if (res.errors){
          this.$toast.fail(res.errors[0].code);
        } else {
          this.payStatus = res.readdata._data.paid;
          this.payStatusNum = +1;
          if (this.payStatus) {
            this.qrcodeShow = false;
            this.show = false;
            this.$router.push('/');
            this.payStatusNum = 11;
            // clearInterval(time);
          }
        }
      }).catch(err=>{
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
          return res;
        }
      }).catch(err=>{
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
        if (res.errors){
          this.$toast.fail(res.errors[0].code);
        } else {
          return res
        }
      }).catch(err=>{
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
      this.getAuthority(res.readdata.groups[0]._data.id);
      this.walletBalance = res.readdata._data.walletBalance;
      this.walletStatus = res.readdata._data.canWalletPay;
    });
    this.tokenId = webDb.getLItem('tokenId');
    this.amountNum = webDb.getLItem('siteInfo')._data.set_site.site_price;
  }
}
