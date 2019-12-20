
/**
 * 移动端站点首页控制器
 */
import browserDb from '../../../../../helpers/webDbHelper';
export default {
	data: function() {
		return {
			showScreen: false,
			loginBtnFix: false,
      loginHide:false,
			// footShow: true,
			fourHeader: true,
      isWx:'1',
      isLoading: false,
      // replyTag: false,
			themeChoList: [
				{
					typeWo: '全部主题',
					type:'1',
          themeType:''
				},
				{
					typeWo: '精华主题',
					type:'2',
          themeType:'isEssence'
				}

			],
      // themeListCon:false,
      themeListCon:[],
      themeNavListCon:[],
      currentData:{},
      replyTagShow: false,
      firstpostImageListCon:[],
      situation1:true,  //付费站点 已登录且已付费
      situation2:false,  //付费站点 已登录但未付费
      situation3:false,  //付费站点 ，未登录
      situation4:false,   //公开站点 未登录
      situation5:false,  //公开站点 已登录
		}
	},
  created:function(){
    this.loadThemeList();
    this.getInfo();
    this.load();
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
        // this.siteUsername = res.readdata._data.siteAuthor.username;
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

    //首页，逻辑判断
    detailIf(isPayVal,isPaid){
      // // console.log(isPayVal+'090909');
      // var token = browserDb.getLItem('Authorization',token);
      // // console.log(isPayVal+'3333');
      // // console.log(isPaid+'44444');
      // if(isPayVal == 'pay'){
      // //当站点为付费站点时
      // console.log('付费');
      //   if(token != '' && token !== null){
      //     //当用户已登录时
      //     console.log('已登录');
      //     //请求用户接口
      //     this.getUser();
      //     if(isPaid){
      //       // console.log('已付费');
      //       //当用户已登录且已付费时
      //       console.log('当用户已登录且已付费时');
      //       this.situation1 = true;
      //       this.loadThemeList();
      //     } else {
      //       //当用户已登录未付费时
      //       console.log('当前用户已登录未付费ddddd');
      //        // this.situation2 = true;
      //     }
      //   } else {
      //     //付费站点，当前用户未登录时
      //     console.log('付费站点，但用户未登录');
      //     this.situation2 = false;
      //     this.situation3 = true;
      //     this.loadThemeList();
      //   }

      // } else {
      //   //当站点为公开站点时
      //   console.log('公开');
      //     if(token){
      //       console.log('公开，已登录');
      //       //当用户已登录时
      //       this.loadThemeList();
      //       this.loginBtnFix = false;
      //       this.loginHide = true;
      //       this.situation1 = true;
      //     }  else {
      //       console.log('公开，未登录');
      //       // this.loadThemeList();
      //       // //当用户未登录时
      //       this.loginBtnFix = true;
      //       this.loginHide = false;
      //       this.situation1 = true;
      //     }
      // }
    },


    //初始化请求主题列表数据
    load(){
      let isWeixin =this.appCommonH.isWeixin().isWeixin;
      if(isWeixin == true){
        //微信登录时
        // alert('微信登录');
        this.isWx = 2;

      } else {
        //手机浏览器登录时
        // console.log('手机浏览器登录');
        this.isWx = 1;
      }
      return this.isWx;
    },
    // //接收站点是否收费的值
    // isPayFn (data) {
    //   // if (data == 'log') {
    //     console.log(data);
    //     // this.isPay = data;
    //   // }
    // },
    //初始化请求主题列表数据
    loadThemeList(filterCondition,filterVal){
      if(filterCondition == 'isEssence'){
        this.appFetch({
          url: 'threads',
          method: 'get',
          data: {
            'filter[isEssence]':filterVal,
            include: ['user', 'firstPost', 'firstPost.images', 'lastThreePosts', 'lastThreePosts.user', 'lastThreePosts.replyUser', 'firstPost.likedUsers', 'rewardedUsers'],
          }
        }).then((res) => {
          this.themeListCon = res.readdata;
        })

      } else if(filterCondition == 'categoryId') {
        this.appFetch({
          url: 'threads',
          method: 'get',
          data: {
            'filter[categoryId]':filterVal,
            include: ['user', 'firstPost', 'firstPost.images', 'lastThreePosts', 'lastThreePosts.user', 'lastThreePosts.replyUser', 'firstPost.likedUsers', 'rewardedUsers'],
          }
        }).then((res) => {
          this.themeListCon = res.readdata;
        })
      } else {
        this.appFetch({
          url: 'threads',
          method: 'get',
          data: {
            filterValue:filterVal,
            include: ['user', 'firstPost', 'firstPost.images', 'lastThreePosts', 'lastThreePosts.user', 'lastThreePosts.replyUser', 'firstPost.likedUsers', 'rewardedUsers'],

            // page: {
            //   offset: 20,
            //   num: 3
            // },
          }
        }).then((res) => {
          // console.log(res.readdata, 'res1111');
          // console.log(res.readdata[1].firstPost.images.length, 'res22222');
          this.themeListCon = res.readdata;
          // this.pushImgArray();

        })
      }


    },
    //把图片url取出，组成一个新的数组（用户主题图片预览）
    pushImgArray(){
      //   var themeListLen = this.themeListCon.length;
      //   var firstpostImage = [];
      //   for (let h = 0; h < themeListLen; h++) {
      //     var firstpostImageLen = this.themeListCon[h].firstPost.images.length;
      //     firstpostImage.push(h,this.themeListCon[h]);
      //     console.log(this.themeListCon[h].firstPost.images);
      //     // console.log(this.themeListCon[h].firstPost.images);
      //     // if (firstpostImageLen === 0) return;
      //     // for (let i = 0; i < firstpostImageLen; i++) {
      //     //   firstpostImage.push(this.themeListCon[h].firstPost.images[i]._data.fileName);
      //     //   console.log(firstpostImage+'3333');
      //     //   // firstpostImage.push('https://img.yzcdn.cn/2.jpg');
      //     // }
      //   }
      // console.log(firstpostImage+'343434');
      // this.firstpostImageListCon = firstpostImage;
      // console.log(this.firstpostImageListCon+'5555');
    },
		// 先分别获得id为testNavBar的元素距离顶部的距离和页面滚动的距离
    // 比较他们的大小来确定是否添加fixedHead样式
    // 比较他们的大小来确定是否添加fixedNavBar样式
		footFix() {
	    	// console.log(this.$route.meta.oneHeader);
	    	// if(this.$route.meta.oneHeader){
            var scrollTop = window.pageYOffset || document.documentElement.scrollTop || document.body.scrollTop
		        var offsetTop = document.querySelector('#testNavBar').offsetTop;
              if(this.loginBtnFix == true){
                this.loginHide = true;
                console.log(scrollTop+'1111');
                console.log(offsetTop+'2222');
                if(scrollTop > offsetTop){
                  console.log('大于');
                  this.loginHide = true;
                  console.log(this.loginHide);
                } else {
                  console.log('小于');
                  this.loginHide = false;
                }
		        }
	    	// }
	    },
      //筛选
	    choTheme(themeType) {
        this.loadThemeList('isEssence',themeType);
	    	// console.log('筛选');
	    },
      //点击分类
      categoriesChoice(cateId) {
        // console.log(cateId);
        this.loadThemeList('categoryId',cateId);
      },
	    //跳转到登录页
	    loginJump:function(isWx){
        let wxCode =this.load();

        const that = this;
        that.$router.push({
          path:'wechat',
        });
        if(wxCode ==1){
          this.$router.push({ path:'login-user'});
        } else if(wxCode ==2){
          this.appFetch({
            url:"weixin",
            method:"get",
            data:{
              // attributes:this.attributes,
            }
          }).then(res=>{
            alert(1234);
            // alert(this.showScreen);
            // console.log(res.data.attributes.location);
            // window.location.href = res.data.attributes.location;
            this.$router.push({ path:'wechat'});
          });

        }
	    },
	    postTopic:function(){
	    	// alert('跳转到发布主题页');
	    	this.$router.push({ path:'post-topic'});
	    },
		/**
		 * 给导航添加点击状态
		 */
      addClass:function(index,event){
        this.current=index;
  　　　　//获取点击对象
        var el = event.currentTarget;
         // alert("当前对象的内容："+el.innerHTML);
      },
	    //筛选
	    bindScreen:function(){
	        //是否显示筛选内容
	        this.showScreen = !this.showScreen;
	    },
	    hideScreen(){
	        //是否显示筛选内容
	        this.showScreen = false;
      },
      onRefresh(){
        setTimeout(()=>{
          this.loadThemeList()
          this.$toast('刷新成功');
          this.isLoading = false;
        },200)
      }
	},
	mounted: function() {
		// this.getVote();
		window.addEventListener('scroll', this.footFix, true);
	},
	beforeRouteLeave (to, from, next) {
	   window.removeEventListener('scroll', this.footFix, true)
	   next()
	}
}
