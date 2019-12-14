
/**
 * 移动端站点首页控制器
 */
export default {
	data: function() {
		return {
			showScreen: false,
			loginBtnFix: true,
			// footShow: true,
			fourHeader: true,
      isWx:'1',
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
		}
	},
  created:function(){
    this.loadThemeList();
    this.load();
  },
	methods: {
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
            include: ['user', 'firstPost', 'lastThreePosts', 'lastThreePosts.user', 'lastThreePosts.replyUser', 'firstPost.likedUsers', 'rewardedUsers'],
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
            include: ['user', 'firstPost', 'lastThreePosts', 'lastThreePosts.user', 'lastThreePosts.replyUser', 'firstPost.likedUsers', 'rewardedUsers'],
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
            include: ['user', 'firstPost', 'lastThreePosts', 'lastThreePosts.user', 'lastThreePosts.replyUser', 'firstPost.likedUsers', 'rewardedUsers'],

            // page: {
            //   offset: 20,
            //   num: 3
            // },
          }
        }).then((res) => {
          console.log(res.readdata[0], 'res1111');
          console.log(res.readdata[0].lastThreePosts[0].replyUser._data.username, 'res1111');
          this.themeListCon = res.readdata;
        })
      }


    },
		// 先分别获得id为testNavBar的元素距离顶部的距离和页面滚动的距离
    // 比较他们的大小来确定是否添加fixedHead样式
    // 比较他们的大小来确定是否添加fixedNavBar样式
		footFix() {
	    	// console.log(this.$route.meta.oneHeader);
	    	if(this.$route.meta.oneHeader){
	    		var scrollTop = window.pageYOffset || document.documentElement.scrollTop || document.body.scrollTop
		        var offsetTop = document.querySelector('#testNavBar').offsetTop;
		        if(scrollTop > offsetTop){
		          this.loginBtnFix = false;
		        } else {
		          this.loginBtnFix = true;
		        };
	    	}
	    },
      //筛选
	    choTheme(themeType) {
        this.loadThemeList('isEssence',themeType);
	    	// console.log('筛选');
	    },
      //点击分类
      categoriesChoice(cateId) {
        console.log(cateId);
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
