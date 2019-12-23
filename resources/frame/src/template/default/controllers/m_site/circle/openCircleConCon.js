/**
 * wap详情页控制器
 */
import {Bus} from '../../../store/bus.js';
import Thread from '../../../../../common/models/Thread';
// import User from '../../../../../common/models/User';
import browserDb from '../../../../../helpers/webDbHelper';
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
      amountNum:'',
      codeUrl:'',
      themeChoList: [
      	{
      		typeWo: '加精',
      		type:'2'
      	},
      	{
      		typeWo: '置顶',
      		type:'3'
      	},
        {
        	typeWo: '删除',
        	type:'4'
        },
        {
        	typeWo: '编辑',
        	type:'5'
        }

      ],
      showScreen: false,
      request:false,
      isliked:'',
      likedClass:''
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
    //初始化请求主题列表数据
    detailsLoad(){
        let threads = 'threads/'+this.themeId;
        this.appFetch({
          url: threads,
          method: 'get',
          data: {
            'filter[isDeleted]':'no',
            include: ['user', 'posts', 'posts.user', 'posts.likedUsers', 'firstPost','firstPost.likedUsers', 'rewardedUsers', 'category'],
          }
        }).then((res) => {
          console.log(res, 'res1111');
          // console.log(res.readdata[0].lastThreePosts[0].replyUser._data.username, 'res1111');
          this.themeShow = true;
          this.themeCon = res.readdata;
          // console.log(this.themeCon.firstPost._data.content);
        })
    },
    //分享
    shareTheme(){
      var userId = browserDb.getLItem('tokenId');
      this.appFetch({
        url: 'users',
        method: 'get',
        splice:'/'+userId,
        data: {
          include: '',
        }
      }).then((res) => {
        console.log(res.readdata._data.paid);
        if(res.readdata._data.paid){
          console.log('付费站点，内容页的分享');
          this.$router.push({
            path:'/pay-circle-con',
            name:'pay-circle-con',
          })
        } else {
          console.log('公开站点，内容页的分享');
          this.$router.push({
            path:'/open-circle-con',
            name:'open-circle-con'
          })
        }
      })

    },
    // detailsLoad(){
    //   const params = {
    //     'filter[isDeleted]':'no'
    //   };
    //   params.include = 'user,posts,posts.user,posts.likedUsers,firstPost,rewardedUsers,category';
    //   let threads= 'threads/'+this.themeId;
    //   this.apiStore.find(threads, params).then(data => {
    //     this.themeCon = data;
    //     this.themeShow = true;
    //     // console.log(data.posts());
    //     // console.log(data.rewardedUsers());
    //     // console.log(data.firstPost().content());
    //   });
    // },
    //主题管理
    bindScreen:function(){
        //是否显示筛选内容
        this.showScreen = !this.showScreen;
    },
    //管理操作
    themeOpera(postsId,clickType,cateId,content) {
      let attri = new Object();
       if(clickType == 1){
        attri.isFavorite = true;
        content ='';
        this.themeOpeRequest(attri,cateId);
       } else if(clickType == 2){
         content ='';
         this.themeOpeRequest(attri,cateId);
        attri.isEssence = true;
       } else if(clickType == 3){
         content ='';
         // request = true;
        attri.isSticky = true;
        this.themeOpeRequest(attri,cateId);
       } else if(clickType == 4){
        attri.isDeleted = true;
        content ='';
        this.themeOpeRequest(attri,cateId);
       } else {
         // content = content
         console.log(content);
         //跳转到发帖页
        this.$router.push({
          path:'/post-topic',
          name:'post-topic',
          params: { themeId:this.themeId,postsId:postsId,themeContent:content}
        })
       }
    },
    //主题操作接口请求
    themeOpeRequest(attri,cateId){
        // console.log(attri);
        let threads = 'threads/'+this.themeId;
        this.appFetch({
          url:threads,
          method:'patch',
          data:{
            "data": {
              "type": "threads",
              "attributes": attri
            },
            "relationships": {
                "category": {
                    "data": {
                        "type": "categories",
                        "id": cateId
                    }
                }
            }
          }
        }).then((res)=>{

        })


    },
    //点赞/删除
    replyOpera(postId,type,isLike){
      // console.log(isLike);
      let attri = new Object();
      if(type == 1){
        attri.isDeleted = true;
      } else if(type == 2){
        if(isLike){
          //如果已点赞
          attri.isLiked = false;
        } else {
          //如果未点赞
          attri.isLiked = true;
        }
      }
      // console.log(attri);
      let posts = 'posts/'+postId;
      this.appFetch({
        url:posts,
        method:'patch',
        data:{
          "data": {
            "type": "posts",
            "attributes": attri,
          }
        }
      }).then((res)=>{
        this.$message('修改成功');
        this.detailsLoad();
      })
    },
    //打赏
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
        // console.log(data.data.attributes.order_sn);
        const orderSn = data.data.attributes.order_sn;
        this.orderPay(orderSn,amount);

      })
    },

    //打赏，生成订单成功后支付
    orderPay(orderSn,amount){
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
        // console.log(data);
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
          this.amountNum = amount;
          console.log(this.amountNum);
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
