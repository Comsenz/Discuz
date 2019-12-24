
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
      amountNum:''       //支付价钱
    }
  },

  components:{
    PayHeader
  },

  methods:{
    leapFrogClick(){
      this.$router.push({path:'pay-circle-login'})
    },

    payClick(){
      let isWeixin = this.appCommonH.isWeixin().isWeixin;
      let isPhone = this.appCommonH.isWeixin().isPhone;

      if (isWeixin){
        console.log('微信');
        this.getOrderSn().then(()=>{
          this.orderPay(12).then(()=>{

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
        /*switch (type){
          case 10:

            break;
          case 11:

            break;
          case 12:
            break;
          default:
            console.log("支付费用页面参数获取失败，请重新获取！")
        }*/
      }).catch(err=>{
        console.log(err);
      })
    }

  },
  created(){
    this.getForum();
    this.amountNum = webDb.getLItem('siteInfo')._data.setsite.site_price;
  }
}
