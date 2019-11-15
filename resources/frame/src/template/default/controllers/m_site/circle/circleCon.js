/**
 * 移动端圈子首页控制器
 */
export default {
	data: function() {
		return {
			showScreen: false,
			loginBtnFix: true,
			// footShow: true,
			fourHeader: true,
      // replyTag: false,
			themeChoList: [
				{
					typeWo: '全部主题',
					type:'1'
				},
				{
					typeWo: '精华主题',
					type:'2'
				}

			],
      themeListCon:{
        // themeDataCon:[
          // {
          //   "type": "",
          //   "id": "",
          //   "attributes": {
          //       "avatarUrl":"",
          //       "title": "",
          //       "price": "",
          //       "viewCount": 0,
          //       "postCount": 0,
          //       "likeCount": 0,
          //       "createdAt": "2019-11-12T17:11:00+08:00",
          //       "updatedAt": "2019-11-12T17:11:00+08:00",
          //       "isApproved": true,
          //       "isSticky": false,
          //       "isEssence": false,
          //       "isFavorite": false
          //   },
            // "rewardList":[
            //   'bbb',
            //   'ccccccccc'
            // ],
            // "fabulousList":[
            //   'ddddddddd',
            //   'ee',
            //   'ffff'
            // ],
          //   "relationships": {
          //       "user": {
          //           "data": {
          //               "type": "users",
          //               "id": "1"
          //           }
          //       },
          //       "firstPost": {
          //           "data": {
          //               "type": "Posts",
          //               "id": "32"
          //           }
          //       }
          //   }
          // }

        // ],
        // themeincludedCon:[

        // ]
      },

      currentData:{},
      replyTagShow: false,
		}
	},
  created:function(){
    this.loadThemeList();
  },
	methods: {
    loadThemeList(){
      this.appFetch({
        url:"threads",
        method:"get",
        data:{
          include:'user,firstPost,lastThreePosts,lastThreePosts.user'
        }
      },(res)=>{
        if (res.status === 200){
          // console.log(res);
          this.themeListCon = res.data; //是个对象
          console.log(this.themeListCon);
          // for(var i=0; i<this.themeListCon.length;i++){
          //   this.currentData = this.themeListCon[i];
          //   var lastThreePostsLen = this.currentData.relationships.lastThreePosts.data.length;
          //   // console.log(lastThreePosts);
          //   if(lastThreePostsLen == 0){
          //     // console.log(lastThreePosts.length);
          //     this.replyTagShow = false;
          //   } else {
          //     this.replyTagShow = true;
          //   }
          // }
        } else{
          console.log('400');
        }
      },(err)=>{
        console.log(err);
      })
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

	    choTheme() {
	    	console.log('筛选');
	    },
	    //跳转到登录页
	    loginJump:function(){
	    	console.log(this.oneHeader);
	    	// alert('跳转到登录页');
	    	this.$router.push({ path:'login-user'});
	    	// console.log(this.$router);
	    },
	    //跳转到注册页
	    registerJump:function(){
	    	// alert('跳转到注册页');
	    	this.$router.push({ path:'sign-up'});
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

	},

	mounted: function() {
		// this.getVote();
		window.addEventListener('scroll', this.footFix, true);
	},
	beforeRouteLeave (to, from, next) {
	   window.removeEventListener('scroll', this.footFix, true)
	   // next()
	}
}
