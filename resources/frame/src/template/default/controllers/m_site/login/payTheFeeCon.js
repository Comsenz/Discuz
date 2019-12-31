
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
          console.log(res);
          alert('支付成功');
          alert(res);

          if (res.err_msg == "get_brand_wcpay_request:ok") {
            alert("支付成功");
            this.$toast.success('支付成功');
          } else if (res.err_msg == "get_brand_wcpay_request:cancel") {
            alert("支付过程中用户取消");             //支付取消正常走
          } else if (res.err_msg == "get_brand_wcpay_request:fail") {
            alert("支付失败");
          }

        });
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
              alert('存在wx方法');
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
        this.sitePrice = res.readdata._data.setsite.site_price;

        let day = res.readdata._data.setsite.site_expire;

        switch (day){
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
        this.payStatus = res.readdata._data.paid;
        this.payStatusNum =+1;
        if (this.payStatus){
          this.qrcodeShow = false;
          this.$router.push('/');
          this.payStatusNum = 11;
          clearInterval(pay);
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
        console.log(res);
        return res.readdata.groups[0]._data.id;
        //paid
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
        return res
      }).catch(err=>{
        console.log(err);
      })
    }

  },
  created(){
    this.getForum();
    this.getUsers(webDb.getLItem('tokenId')).then(res=>{
      this.getAuthority(res)
    });
    this.amountNum = webDb.getLItem('siteInfo')._data.setsite.site_price;
  }
}
