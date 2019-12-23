/**
 * pc 端首页控制器
 */

export default {
	data: function() {
		return {
      showScreen: false,
      themeListCon:[],
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

      ]
			// navActi: 0,
			// current:0,
   //    todos: [
   //        { text: '选项一111' },
   //        { text: '选项二' },
   //        { text: '选项三' },
   //        { text: '选项四' },
   //        { text: '选项五' },
   //        { text: '选项六' },
   //        { text: '选项七' },
   //        { text: '选项八' }
   //    ]
		}
	},
  created:function(){
    this.loadThemeList();
  },
	methods: {

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
    //筛选
    bindScreen:function(){
        //是否显示筛选内容
        this.showScreen = !this.showScreen;
    },
    hideScreen(){
        //是否显示筛选内容
        this.showScreen = false;
    },
		//跳转到登录页
		loginJump:function(){
			this.$router.push({ path:'login-user'})
		},
		//跳转到注册页
		registerJump:function(){
			this.$router.push({ path:'sign-up'})
		},
		/**
		 * 给导航添加点击状态
		 */
		addClass:function(index,event){
            this.current=index;

　　　　　　 //获取点击对象
           var el = event.currentTarget;
           // alert("当前对象的内容："+el.innerHTML);
        }

	},

	mounted: function() {
		// this.getVote();
		// window.addEventListener('scroll', this.handleTabFix, true);
	},
	beforeRouteLeave (to, from, next) {
	   // window.removeEventListener('scroll', this.handleTabFix, true)
	   next()
	}
}
