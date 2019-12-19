/**
 * wap详情页控制器
 */
import {Bus} from '../../../store/bus.js';
import Thread from '../../../../../common/models/Thread';
// import User from '../../../../../common/models/User';
import browserDb from '../../../../../helpers/webDbHelper';
import Forum from '../../../../../common/models/Forum';
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
      // themeChoList: [
      // 	{
      // 		typeWo: '加精',
      // 		type:'2'
      // 	},
      // 	{
      // 		typeWo: '置顶',
      // 		type:'3'
      // 	},
      //   {
      //   	typeWo: '删除',
      //   	type:'4'
      //   },
      //   {
      //   	typeWo: '编辑',
      //   	type:'5'
      //   }

      // ],
      showScreen: false,
      request:false,
      isliked:'',
      likedClass:'',
      imageShow: false,
      index: 1,
      firstpostImageList: [
        // 'https://img.yzcdn.cn/2.jpg',
        // 'https://img.yzcdn.cn/2.jpg'
      ],
      isPayVal:'',
      isPaid:'',
      situation1:false,  //付费站点 已登录已付费
      situation2:false,  //付费站点 已登录但未付费
      situation3:false,   //付费站点 未登录
      situation4:false,  //公开站点 已登录
      situation5:false,   //公开站点 未登录
      siteInfo: false,
      siteUsername:'',  //站长
      joinedAt:'',    //加入时间
      sitePrice:'',   //加入价格
      username:'',    //当前用户名
      roleList:[]
		}
	},
  created(){
    this.getInfo();

    if(!this.themeCon){
      this.themeShow = false;
    } else {
      this.themeShow = true
    }
    // this.detailsLoad();
  },

  computed: {
      themeId: function(){
          return this.$route.params.themeId;
      }
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
        console.log(res);
        this.siteInfo = res.readdata;
        console.log(res.readdata._data.siteMode+'请求');
        this.siteUsername = res.readdata._data.siteAuthor.username;
        this.sitePrice = res.readdata._data.sitePrice
        //把站点是否收费的值存储起来，以便于传到父页面
        this.isPayVal = res.readdata._data.siteMode;
        if(this.isPayVal != null && this.isPayVal != ''){
          this.isPayVal = res.readdata._data.siteMode;
          //判断站点信息是否付费，用户是否登录，用户是否已支付
          this.detailIf(this.isPayVal,false);
        }
      });
    },
    //请求用户信息
    getUser(){
    //初始化请求User信息，用于判断当前用户是否已付费
      var userId = browserDb.getLItem('tokenId');
      this.appFetch({
        url: 'users',
        method: 'get',
        splice:'/'+userId,
        data: {
          include: 'groups',
        }
      }).then((res) => {
        // console.log(res.readdata._data.username);
        this.username = res.readdata._data.username;
        this.isPaid = res.readdata._data.paid;
        this.roleList = res.readdata.groups;
        if(res.readdata._data.joinedAt=='' || res.readdata._data.joinedAt == null){
          this.joinedAt = res.readdata._data.createdAt;
        } else {
          this.joinedAt = res.readdata._data.joinedAt;
        }
        if(this.isPaid != null && this.isPaid != ''){
          this.detailIf(this.isPayVal,false);
        }
        // this.detailIf(false,this.isPaid);
      })

    },
    detailIf(isPayVal,isPaid){
      // console.log(isPayVal+'090909');
      var token = browserDb.getLItem('Authorization',token);
      // console.log(isPayVal+'3333');
      // console.log(isPaid+'44444');
      if(isPayVal == 'pay'){
      //当站点为付费站点时
      // console.log('付费');
        if(token != '' && token !== null){
          //当用户已登录时
          // console.log('已登录');
          //请求用户接口
          this.getUser();
          if(isPaid){
            // console.log('已付费');
            //当用户已登录且已付费时
            // console.log('当用户已登录且已付费时');
            this.situation1 = true;
            this.detailsLoad();
          } else {
            //当用户已登录未付费时
            // console.log('当前用户已登录未付费');
             this.situation2 = true;
          }
        } else {
          //付费站点，当前用户未登录时
          // console.log('付费站点，但用户未未登录');
          this.situation2 = false;
          this.situation3 = true;
          this.detailsLoad();
        }

      } else {
        //当站点为公开站点时
        console.log('公开');
          if(token){
            // console.log('公开，已登录');
            //当用户已登录时
            this.detailsLoad();
            this.situation1 = true;
          }  else {
            //当用户未登录时
            // console.log('公开，未登录');
            this.detailsLoad();
            this.situation5 = true;
          }
      }
    },

    //查看更多站点成员
    moreCilrcleMembers(){
      this.$router.push({path:'circle-members'});
    },

    //初始化请求主题列表数据
    detailsLoad(){
        let threads = 'threads/'+this.themeId;
        this.appFetch({
          url: threads,
          method: 'get',
          data: {
            'filter[isDeleted]':'no',
            include: ['user', 'posts', 'posts.user', 'posts.likedUsers', 'posts.images', 'firstPost', 'firstPost.likedUsers', 'firstPost.images', 'firstPost.attachments', 'rewardedUsers', 'category'],
          }
        }).then((res) => {
          // console.log(res, 'res1111');
          // console.log(res.readdata[0].lastThreePosts[0].replyUser._data.username, 'res1111');
          this.themeShow = true;
          this.themeCon = res.readdata;
          // this.firstpostImageList = this.themeCon.firstPost.images;
          var firstpostImageLen = this.themeCon.firstPost.images.length;
          if (firstpostImageLen === 0) return;
          var firstpostImage = [];
          for (let i = 0; i < firstpostImageLen; i++) {
            firstpostImage.push(this.themeCon.firstPost.images[i]._data.fileName);
            // firstpostImage.push('https://img.yzcdn.cn/2.jpg');
          }
          this.firstpostImageList = firstpostImage;
          // console.log(this.firstpostImageList);

          // console.log(this.themeCon.firstPost._data.content);
        })
    },
    //主题详情图片放大轮播
    imageSwiper(){
      this.imageShow = true;
    },
    //主题详情图片放大轮播index值监听
    onChange(index) {
      this.index = index+1;
    },
    //分享，复制浏览器地址
    shareTheme(){
        var Url= location.href;
        var oInput = document.createElement('input');
        oInput.value = Url;
        document.body.appendChild(oInput);
        oInput.select(); // 选择对象
        document.execCommand("Copy");
        // 执行浏览器复制命令
        oInput.className = 'oInput';
        oInput.style.display='none';
        // alert('复制成功');
        this.$toast.success('分享链接已复制成功');
    },
    //退出登录
    signOut(){
      browserDb.removeLItem('tokenId');
      browserDb.removeLItem('Authorization');
      this.$router.push({ path:'/login-user'});
    },
    //跳转到登录页
    loginJump:function(){
    	this.$router.push({ path:'/login-user'});
      browserDb.setLItem('themeId',this.themeId);
    },
    //跳转到注册页
    registerJump:function(){
    	this.$router.push({ path:'/sign-up'});
      browserDb.setLItem('themeId',this.themeId);
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
        this.$router.push({
          path:'/circle',
          name:'circle'
        })
       } else {
         // content = content
         // console.log(content);
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
    }


	},

	mounted: function() {
	},

	beforeRouteLeave (to, from, next) {
    next()
	}

}
