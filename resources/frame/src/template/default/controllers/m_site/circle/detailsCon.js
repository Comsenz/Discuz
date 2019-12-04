/**
 * wap详情页控制器
 */
import {Bus} from '../../../store/bus.js';
import Thread from '../../../../../common/models/Thread';
// import User from '../../../../../common/models/User';

export default {
	data: function() {
		return {
			headBackShow: true,
			rewardShow: false,
      themeCon:false,
      themeShow:false,
      examineNum:'qqqq',
      rewardNumList:[
        {rewardNum:'0.01'},
        {rewardNum:'2'},
        {rewardNum:'5'},
        {rewardNum:'10'},
        {rewardNum:'20'},
        {rewardNum:'50'},
        {rewardNum:'88'},
        {rewardNum:'128'},
        {rewardNum:'666'}
      ],
      qrcodeShow:false,
      codeUrl:''
		}
	},
  created(){
    // console.log(this.themeCon);
      if(!this.themeCon){
      // console.log('1111');
      this.themeShow = false;
    } else {
      // console.log('22222');
      this.themeShow = true
    }
    // this.Thread = new Thread();
    // this.themeCon.user = new User();
    this.detailsLoad();
    // console.log(this.themeId);
  },

  computed: {
      themeId: function(){
          return this.$route.params.themeId;
      }
  },
	methods: {
    //主题详情数据请求
    detailsLoad(){
      const params = {};
      params.include = 'user,posts,posts.user,posts.likedUsers,firstPost,rewardedUsers';
      const threads= 'threads/'+this.themeId;
      this.apiStore.find(threads, params).then(data => {
        this.themeCon = data;
        this.themeShow = true;
        // console.log(data);
        // console.log(data.rewardedUsers());
        // console.log(data.firstPost().content());
      });
    },

		showRewardPopup:function() {
	      this.rewardShow = true;
	  },
		//跳转到回复页
		replyToJump:function(themeId,replyId,quoteCon) {
			this.$router.push({
        path:'/reply-to-topic',
        name:'reply-to-topic',
        params: { themeId:themeId,replyQuote: quoteCon,replyId:replyId }
       })
		},
    //打赏 生成订单
    rewardPay(amount){
      this.appFetch({
        url:"orderList",
        method:"post",
        data:{
              "type":"2",
              "thread_id":this.themeId,
              "amount":amount
        },
      }).then(data =>{
        console.log(data.data.attributes.order_sn);
        const orderSn = data.data.attributes.order_sn;
        this.orderPay(orderSn);

      })
    },

    //打赏，生成订单成功后支付
    orderPay(orderSn){
      let isWeixin =this.appCommonH.isWeixin().isWeixin;
      let isPhone =this.appCommonH.isWeixin().isPhone;
      console.log(isWeixin+'1111')
      console.log(isPhone+'2222')
      let payment_type = '';
      if(isWeixin == true){
        //微信登录时
        alert('微信支付');
        // this.appFetch({
        //   url:"weixin",
        //   method:"get",
        //   data:{
        //   }
        // }).then(data=>{
        //   console.log(data.data.attributes.location)
        //   window.location.href = data.data.attributes.location;
        // });
        payment_type = "12";
      } else if( isPhone == true) {
        //手机浏览器登录时
        console.log('手机浏览器登录');
         payment_type = "11";
      } else {
        payment_type = "10";
        console.log('pc登录');
      }
      let orderPay = 'trade/pay/order/'+orderSn;
      this.appFetch({
        url:orderPay,
        method:"post",
        data:{
              'payment_type':payment_type
        },
      }).then(data =>{
        console.log(data);
        if(isWeixin){
          //如果是微信支付
           console.log(data.data.attributes.wechat_js);
        } else if(isPhone) {
          //如果是h5支付
          // console.log(data.data.attributes.wechat_h5_link);
          window.location.href = data.data.attributes.wechat_h5_link;
        } else {
          //如果是pc支付
          // console.log(data.data.attributes.wechat_qrcode);
          this.qrcodeShow = true;
          this.codeUrl= data.data.attributes.wechat_qrcode;
        }

      })
    }


	},

	mounted: function() {
	},

	beforeRouteLeave (to, from, next) {
    next()
	}

}
