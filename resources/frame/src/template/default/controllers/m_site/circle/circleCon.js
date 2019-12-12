
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
					type:'1'
				},
				{
					typeWo: '精华主题',
					type:'2'
				}

			],
      themeListCon:[
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
      ],
      themeNavListCon:[
        { text: '选项111' },
        { text: '选项222' },
        { text: '选项333' }
        // {
        //     "type": "classify",
        //     "id": "6",
        //     "attributes": {
        //         "id": 6,
        //         "name": "户外趣事2",
        //         "icon": "",
        //         "description": "户外活动，组织，趣事",
        //         "property": 0,
        //         "sort": 1,
        //         "threads": 0
        //     }
        // },
      ],
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


    loadThemeList(){
      this.appFetch({
        url: 'threads',
        method: 'get',
        data: {
          include: ['user', 'firstPost', 'lastThreePosts', 'lastThreePosts.user', 'firstPost.likedUsers', 'rewardedUsers'],
          // page: {
          //   offset: 20,
          //   num: 3
          // },
        }
      }).then((res) => {
        // console.log(res, 'res1111');
        this.themeListCon = res.readdata;
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
