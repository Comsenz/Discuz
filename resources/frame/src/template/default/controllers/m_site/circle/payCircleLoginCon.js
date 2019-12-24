/**
 * 付费站点-已支付-未登录控制器
 */
import Header from '../../../view/m_site/common/headerView'
export default {
	data: function() {
		return {
			headOpeShow: false,
			isfixNav: false,
			current:0,
        todos: [
          { text: '选项一111' },
          { text: '选项二' },
          { text: '选项三' },
          { text: '选项四' },
          { text: '选项五' },
          { text: '选项六' },
          { text: '选项七' },
          { text: '选项八' }
      ],
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
        if(initStatus){
          this.siteInfo= []
        }
        console.log(res);
        this.siteInfo = res.readdata;
        console.log(res.readdata._data.siteMode+'请求');
        if(res.readdata._data.siteAuthor){
          this.siteUsername = res.readdata._data.siteAuthor.username;
        } else {
          this.siteUsername = '暂无站长信息';
        }
        this.sitePrice = res.readdata._data.sitePrice
      });
    },
    //退出登录
    signOut(){
      browserDb.removeLItem('tokenId');
      browserDb.removeLItem('Authorization');
      this.$router.push({ path:'/login-user'});
    },
    //付费，获得成员权限
    sitePayClick(amount){
      this.appFetch({
        url:"orderList",
        method:"post",
        data:{
              "type":"1",
              "thread_id":this.themeId,
              "amount":amount
        },
      }).then(data =>{
        // console.log(data.data.attributes.order_sn);
        const orderSn = data.data.attributes.order_sn;
        this.orderPay(orderSn,amount);

      })
    },
    //生成订单成功后支付
    orderPay(orderSn,amount){
      // console.log(amount+'101010');
      let isWeixin =this.appCommonH.isWeixin().isWeixin;
      let isPhone =this.appCommonH.isWeixin().isPhone;
      // console.log(isWeixin+'1111')
      // console.log(isPhone+'2222')
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
        // console.log('手机浏览器登录');
         payment_type = "11";
      } else {
        payment_type = "10";
        // console.log('pc登录');
      }
      let orderPay = 'trade/pay/order/'+orderSn;
      this.appFetch({
        url:orderPay,
        method:"post",
        data:{
              'payment_type':payment_type
        },
      }).then(data =>{
        // console.log(data);
        if(isWeixin){
          //如果是微信支付
           // console.log(data.data.attributes.wechat_js);
        } else if(isPhone) {
          //如果是h5支付
          // console.log(data.data.attributes.wechat_h5_link);
          window.location.href = data.data.attributes.wechat_h5_link;
        } else {
          // console.log('pc');
          //如果是pc支付
          // console.log(data.data.attributes.wechat_qrcode);
          this.qrcodeShow = true;
          // console.log(this.qrcodeShow);
          this.amountNum = amount;
          // console.log(this.amountNum);
          this.codeUrl= data.data.attributes.wechat_qrcode;
        }

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
