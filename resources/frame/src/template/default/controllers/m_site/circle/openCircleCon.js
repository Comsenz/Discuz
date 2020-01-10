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

      ],
      loading: false,  //是否处于加载状态
      finished: false, //是否已加载完所有数据
      isLoading: false, //是否处于下拉刷新状态
      pageIndex: 1,//页码
      pageLimit: 20,
      offset: 100, //滚动条与底部距离小于 offset 时触发load事件
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
  computed: {
    userId: function(){
        return this.$route.params.userId;
    },
},
	methods: {

    //初始化请求主题列表数据
    loadThemeList(filterCondition,filterVal,initStatus=false){
      if(filterCondition == 'isEssence'){
     return this.appFetch({
          url: 'threads',
          method: 'get',
          data: {
            'filter[isEssence]':filterVal,
            include: ['user', 'firstPost', 'firstPost.images', 'lastThreePosts', 'lastThreePosts.user', 'lastThreePosts.replyUser', 'firstPost.likedUsers', 'rewardedUsers'],
            'page[number]': this.pageIndex,
            'page[limit]': this.pageLimit
          }
        }).then((res) => {
          if (res.errors){
            this.$toast.fail(res.errors[0].code);
            throw new Error(res.error)
            }else{
          if(initStatus){
            this.themeListCon = []
          }
          this.themeListCon =this.themeListCon.concat(res.readdata);
          this.loading = false;
          this.finished = res.data.length < this.pageLimit;
        }
        }).catch((err)=>{
          if(this.loading && this.pageIndex !== 1){
            this.pageIndex--;
          }
          this.loading = false;
        })

      } else if(filterCondition == 'categoryId') {
        return this.appFetch({
          url: 'threads',
          method: 'get',
          data: {
            'filter[categoryId]':filterVal,
            include: ['user', 'firstPost', 'firstPost.images', 'lastThreePosts', 'lastThreePosts.user', 'lastThreePosts.replyUser', 'firstPost.likedUsers', 'rewardedUsers'],
            'page[number]': this.pageIndex,
            'page[limit]': this.pageLimit
          }
        }).then((res) => {
          if (res.errors){
            this.$toast.fail(res.errors[0].code);
            throw new Error(res.error)
            }else{
          if(initStatus){
            this.themeListCon = []
          }
          this.themeListCon =this.themeListCon.concat(res.readdata);
          this.loading = false;
          this.finished = res.data.length < this.pageLimit;
        }
        }).catch((err)=>{
          if(this.loading && this.pageIndex !== 1){
            this.pageIndex--;
          }
          this.loading = false;
        })
      } else {
        return this.appFetch({
          url: 'threads',
          method: 'get',
          data: {
            filterValue:filterVal,
            include: ['user', 'firstPost', 'firstPost.images', 'lastThreePosts', 'lastThreePosts.user', 'lastThreePosts.replyUser', 'firstPost.likedUsers', 'rewardedUsers'],
            'page[number]': this.pageIndex,
            'page[limit]': this.pageLimit

            // page: {
            //   offset: 20,
            //   num: 3
            // },
          }
        }).then((res) => {
          if (res.errors){
            this.$toast.fail(res.errors[0].code);
            throw new Error(res.error)
            }else{
          if(initStatus){
            this.themeListCon = []
          }
          this.themeListCon =this.themeListCon.concat(res.readdata);
          console.log( this.themeListCon)
          this.loading = false;
          this.finished = res.data.length < this.pageLimit;
        }
        }).catch((err)=>{
          if(this.loading && this.pageIndex !== 1){
            this.pageIndex--;
          }
          this.loading = false;
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
        },
    onLoad(){    //上拉加载
      this.loading = true;
      this.pageIndex++;
      this.loadThemeList();
          },
    onRefresh(){    //下拉刷新
      this.pageIndex = 1;
      this.loadThemeList(true).then(()=>{
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
		// window.addEventListener('scroll', this.handleTabFix, true);
	},
	beforeRouteLeave (to, from, next) {
	   // window.removeEventListener('scroll', this.handleTabFix, true)
	   next()
	}
}
