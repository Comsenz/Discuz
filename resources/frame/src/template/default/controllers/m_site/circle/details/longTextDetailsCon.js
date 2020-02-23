/**
 * pc 端首页控制器
 */
import appCommonH from '../../../../../../helpers/commonHelper';
import browserDb from '../../../../../../helpers/webDbHelper';
import {ImagePreview} from "vant";
export default {
	data: function() {
		return {
      show: false,  //是否显示支付方式
      payList:[
        {
          name:'钱包',
          icon:'icon-weixin'
        }
      ],
      qrcodeShow: false,
      walletBalance: '',  //钱包余额
      errorInfo:'',      //密码错误提示
      value:'',          //密码
      userId: '',         //当前用户ID
      codeUrl:"",        //支付url，base64
		}
  },
  props: {
    themeCon: { // 组件的list
      type: Object
    },
    firstpostImageListProp: {
      type: Array
    },
    
  },
  created:function(){
    var u = navigator.userAgent;
    this.isAndroid = u.indexOf('Android') > -1 || u.indexOf('Adr') > -1; //android终端
    this.isiOS = !!u.match(/\(i[^;]+;( U;)? CPU.+Mac OS X/); //ios终端
    this.isWeixin = appCommonH.isWeixin().isWeixin;
    this.isPhone = appCommonH.isWeixin().isPhone;
    this.userId = browserDb.getLItem('tokenId');
    this.loadUserInfo();
    this.getForum();
  },
  computed: {
    
  },
	methods: {
   //判断设备，下载时提示
    downAttachment(url){
      if(this.isiOS){
        this.$message('因iphone系统限制，您的手机无法下载文件。请使用安卓手机或电脑访问下载');
      }
    },
    //点击用户名称，跳转到用户主页
    jumpPerDet:function(id){
      if(!this.token){
        this.$router.push({
          path:'/login-user',
          name:'login-user'
        })
      } else {
        this.$router.push({ path:'/home-page'+'/'+id});
      }
    },
    //初始化请求用户信息
    loadUserInfo(){
      if(!this.userId){
        return false;
      }
      // console.log(this.personUserId,'访问Id');
      this.appFetch({
        url:'users',
        method:'get',
        splice:'/'+ this.userId,
        data: {
        }
      }).then((res) => {
        console.log(res,'000000000—————');
        this.walletBalance = res.readdata._data.walletBalance;
        
      })
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
            icon:'icon-money'
          })
        }
      }
    }).catch(err=>{
      console.log(err);
    })
  },
    //购买内容
    buyTheme(){
      this.show = !this.show;
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

    imageSwiper(imgIndex, typeclick, replyItem) {
      ImagePreview({
        images:this.firstpostImageListProp,
        startPosition:imgIndex,    //图片预览起始位置索引 默认 0
        showIndex: true,    //是否显示页码         默认 true
        showIndicators: true, //是否显示轮播指示器 默认 false
        loop:true,            //是否开启循环播放  貌似循环播放是不起作用的。。。
        
      })
    },


	},

	mounted: function() {
		
	},
	beforeRouteLeave (to, from, next) {
	   
	   next()
	}
}
