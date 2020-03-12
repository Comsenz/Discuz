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
          icon:'icon-wallet'
        }
      ],
      qrcodeShow: false,
      walletBalance: '',  //钱包余额
      errorInfo:'',      //密码错误提示
      value:'',          //密码
      userId: '',         //当前用户ID
      codeUrl:"",        //支付url，base64
      payLoading: false,
      // appId: '',
      // userDet: '',
      tcPlayerId: 'tcPlayer' + Date.now(),
      player: null,
      videoFileid: '',
      videoAppid: '',
      viewportWidth: '',
		}
  },
  props: {
    themeCon: { // 组件的list
      type: Object
    },
    firstpostImageListProp: {
      type: Array
    },
    userDet: {
      type: Object
    },
    
  },
  mounted () {
    let self = this
    setTimeout(() => {
      self.videoFileid = self.themeCon.threadVideo._data.file_id;
      self.videoAppid = self.videoAppid
      self.$nextTick(() => {
        console.log(self.videoFileid, self.videoAppid,'@@@@#~~~~');
        self.getVideoLang(self.videoFileid, self.videoAppid)
      })
    }, 2000)
  },

  created:function(){
    this.viewportWidth = window.innerWidth;
    var u = navigator.userAgent;
    this.isAndroid = u.indexOf('Android') > -1 || u.indexOf('Adr') > -1; //android终端
    this.isiOS = !!u.match(/\(i[^;]+;( U;)? CPU.+Mac OS X/); //ios终端
    this.isWeixin = appCommonH.isWeixin().isWeixin;
    this.isPhone = appCommonH.isWeixin().isPhone;
    this.userId = browserDb.getLItem('tokenId');
    this.videoAppid = browserDb.getLItem('siteInfo')._data.qcloud.qcloud_app_id;
    // console.log(this.videoAppid,'++++++++++++++++++++++++');
    this.loadUserInfo();
    this.getForum();
    if(this.userId){
      this.getUsers(browserDb.getLItem('tokenId')).then(res=>{
        this.getAuthority(res.readdata.groups[0]._data.id);
        this.walletBalance = res.readdata._data.walletBalance;
      });
    }
    
  },
  computed: {
    themeId: function () {
      return this.$route.params.themeId;
    }
  },
	methods: {
    // 初始化腾讯云播放器
    getVideoLang (fileID, appID) {
      console.log(fileID, appID,'####~~~~');
      const playerParam = {
        fileID: fileID,
        appID: appID,
        'width': this.viewportWidth - 30,
        'coverpic' : '',
      }
      console.log(window.TCPlayer,'lllllllllllllllllllll');
      this.player = window.TCPlayer(this.tcPlayerId, playerParam)
    },
    //点击用户名称，跳转到用户主页
    jumpPerDet:function(id){
    //   if(!this.userId){
    //     this.$router.push({
    //       path:'/login-user',
    //       name:'login-user'
    //     })
    //   } else {
      this.$router.push({ path:'/home-page'+'/'+id});
      // }
    },
    //初始化请求用户信息
    loadUserInfo(){
      if(!this.userId){
        return false;
      }
      this.appFetch({
        url:'users',
        method:'get',
        splice:'/'+ this.userId,
        data: {
        }
      }).then((res) => {
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
    //购买内容
    buyTheme(){
      this.show = !this.show;
    },
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
                this.getOrderStatus();
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
                this.getOrderStatus();
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
              this.getOrderStatus();
            },3000)  
          })
        })
      }
    },
    //删除
    onDelete(){
      this.value = this.value.slice(0, this.value.length - 1);
    },
    //关闭
    onClose(){
      this.value = '';
      this.errorInfo = '';
      this.payLoading = false;
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
          

        });

      const payWechat = setInterval(()=>{
        if (this.payStatus == '1' || this.payStatusNum > 10){
          clearInterval(payWechat);
          return;
        }
        this.getOrderStatus();
      },3000)

    },
    getOrderSn(){
      return this.appFetch({
        url:'orderList',
        method:'post',
        data:{
          "type":3,
          "thread_id": this.themeId
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
          this.value = '';
          if (res.errors[0].detail){
            this.$toast.fail(res.errors[0].code + '\n' + res.errors[0].detail[0])
          } else {
            this.$toast.fail(res.errors[0].code);
          }
        } else {
          this.payLoading = true;
          return res;
        }
      }).catch(err=>{
      })
    },
    getUsersInfo(){
    },
    getOrderStatus(){
      // alert('查询支付状态');
      // alert(this.orderSn);
      return this.appFetch({
        url:'order',
        method:'get',
        splice:'/' + this.orderSn,
        data:{
        },
      }).then(res=>{
        // const orderStatus = res.readdata._data.status;
        if (res.errors){
          if (res.errors[0].detail){
            this.$toast.fail(res.errors[0].code + '\n' + res.errors[0].detail[0])
          } else {
            this.$toast.fail(res.errors[0].code);
            throw new Error(res.error)
          }
        } else {
          this.payStatus = res.readdata._data.status;
          this.payStatusNum ++;
          if (this.payStatus == '1' || this.payStatusNum > 10){
            if(this.payStatus == '1'){
              location.reload();
              this.sendMsgToParent();
              this.payLoading = false;
            }
            this.rewardShow = false;
            this.qrcodeShow = false;
            this.payStatusNum = 11;
           
            // clearInterval(pay);
          }
        }
        // return res;
      })
    },
    sendMsgToParent(){
      // alert('执行');
      this.$emit('listenToChildEvent',true);
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
	beforeRouteLeave (to, from, next) {
	   
	   next()
	}
}