/**
 * pc 端首页控制器
 */
import browserDb from '../../../../../helpers/webDbHelper';
import appCommonH from '../../../../../helpers/commonHelper';
export default {
	data: function() {
		return {
      showScreen: false,
      themeListCon:[],
      userInfoAvatarUrl:'',
      userInfoName:'',
      invitationShow:false,
      loginBtnFix: true,
      loginHide:false,
      loginWord:'登录 / 注册',
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
      viewportWidth: '',
      isWeixin: false,
      isPhone: false,
      canViewThreads:'',
      nullTip:false,
      nullWord:'',
		}
	},
  created:function(){
    this.viewportWidth = window.innerWidth;
    this.isWeixin = appCommonH.isWeixin().isWeixin;
    this.isPhone = appCommonH.isWeixin().isPhone;
    this.loadThemeList();
    // this.getUserInfo();
    // this.detailIf();
    var token = browserDb.getLItem('Authorization');
    if(token){
      //当用户已登录时
      this.loginBtnFix = false;
      this.loginHide = true;
    }  else {
      // //当用户未登录时
      this.loginBtnFix = true;
      this.loginHide = false;
    }
    
  },
  computed: {
    userId: function(){
        return this.$route.params.userId;
    },
},
	methods: {
    getInfo() {
      //请求站点信息，用于判断站点是否是付费站点
      this.appFetch({
        url: 'forum',
        method: 'get',
        data: {
          include: ['users'],
        }
      }).then((res) => {
        if (res.errors){
          this.$toast.fail(res.errors[0].code);
          throw new Error(res.error);
        } else {
          this.siteInfo = res.readdata;
          this.canViewThreads = res.readdata._data.other.can_view_threads;
          // this.allowRegister = res.readdata._data.set_reg.register_close;
          // this.offiaccountClose = res.readdata._data.passport.offiaccount_close;
          // if (!this.allowRegister) {
          //   this.loginWord = '登录';
          // }
          
        }
      });
    },
    // getUserInfo(){
    //   this.appFetch({
    //     url: 'users',
    //     method: 'get',
    //     splice:'/'+this.userId,
    //     data:{

    //     }
    //   }).then(res=>{
    //     this.userInfoName = res.readdata._data.username;
    //     this.userInfoAvatarUrl = res.readdata._data.avatarUrl;
    //     if(this.userInfoName){
    //       this.invitationShow = true;
    //     }
    //   })
    // },

    //初始化请求主题列表数据
    // loadThemeList(filterCondition,filterVal,initStatus=false){
    //   if(filterCondition == 'isEssence'){
    //  return this.appFetch({
    //       url: 'threads',
    //       method: 'get',
    //       data: {
    //         'filter[isEssence]':filterVal,
    //         include: ['user', 'firstPost', 'firstPost.images', 'lastThreePosts', 'lastThreePosts.user', 'lastThreePosts.replyUser', 'firstPost.likedUsers', 'rewardedUsers'],
    //         'page[number]': this.pageIndex,
    //         'page[limit]': this.pageLimit
    //       }
    //     }).then((res) => {
    //       if (res.errors){
    //         this.$toast.fail(res.errors[0].code);
    //         throw new Error(res.error)
    //         }else{
    //       if(initStatus){
    //         this.themeListCon = []
    //       }
    //       this.themeListCon =this.themeListCon.concat(res.readdata);
    //       this.loading = false;
    //       this.finished = res.data.length < this.pageLimit;
    //     }
    //     }).catch((err)=>{
    //       if(this.loading && this.pageIndex !== 1){
    //         this.pageIndex--;
    //       }
    //       this.loading = false;
    //     })

    //   } else if(filterCondition == 'categoryId') {
    //     return this.appFetch({
    //       url: 'threads',
    //       method: 'get',
    //       data: {
    //         'filter[categoryId]':filterVal,
    //         include: ['user', 'firstPost', 'firstPost.images', 'lastThreePosts', 'lastThreePosts.user', 'lastThreePosts.replyUser', 'firstPost.likedUsers', 'rewardedUsers'],
    //         'page[number]': this.pageIndex,
    //         'page[limit]': this.pageLimit
    //       }
    //     }).then((res) => {
    //       if (res.errors){
    //         this.$toast.fail(res.errors[0].code);
    //         throw new Error(res.error)
    //         }else{
    //       if(initStatus){
    //         this.themeListCon = []
    //       }
    //       this.themeListCon =this.themeListCon.concat(res.readdata);
    //       this.loading = false;
    //       this.finished = res.data.length < this.pageLimit;
    //     }
    //     }).catch((err)=>{
    //       if(this.loading && this.pageIndex !== 1){
    //         this.pageIndex--;
    //       }
    //       this.loading = false;
    //     })
    //   } else {
    //     return this.appFetch({
    //       url: 'threads',
    //       method: 'get',
    //       data: {
    //         filterValue:filterVal,
    //         include: ['user', 'firstPost', 'firstPost.images', 'lastThreePosts', 'lastThreePosts.user', 'lastThreePosts.replyUser', 'firstPost.likedUsers', 'rewardedUsers'],
    //         'page[number]': this.pageIndex,
    //         'page[limit]': this.pageLimit

    //         // page: {
    //         //   offset: 20,
    //         //   num: 3
    //         // },
    //       }
    //     }).then((res) => {
    //       if (res.errors){
    //         this.$toast.fail(res.errors[0].code);
    //         throw new Error(res.error)
    //         }else{
    //       if(initStatus){
    //         this.themeListCon = []
    //       }
    //       this.themeListCon =this.themeListCon.concat(res.readdata);
    //       this.loading = false;
    //       this.finished = res.data.length < this.pageLimit;
    //     }
    //     }).catch((err)=>{
    //       if(this.loading && this.pageIndex !== 1){
    //         this.pageIndex--;
    //       }
    //       this.loading = false;
    //     })
    //   }
    // },

    loadThemeList(filterCondition, filterVal) {
      var userId = browserDb.getLItem('tokenId');
      if (filterVal) {
        this.categoryId = filterVal;
      } else {
        this.categoryId = 0;
      }
      let data = {
        'filter[isEssence]':'yes',
        'filter[fromUserId]':userId,
        'filter[categoryId]':this.categoryId,
        'filter[isApproved]':1,
        'filter[isDeleted]':'no',
        include: ['user', 'firstPost', 'firstPost.images', 'lastThreePosts', 'lastThreePosts.user', 'lastThreePosts.replyUser', 'firstPost.likedUsers', 'rewardedUsers', 'threadVideo'],
        'page[number]': this.pageIndex,
        'page[limit]': this.pageLimit
      }
      if (filterVal == 0) {
        delete data['filter[categoryId]'];
      }
      if (filterCondition !== 'isEssence') {
        delete data['filter[isEssence]'];
      }
      if (filterCondition !== 'fromUserId') {
        delete data['filter[fromUserId]'];
      }
      return this.appFetch({
        url: 'threads',
        method: 'get',
        data: data,
      }).then((res) => {
        if (res.errors) {
          if (res.rawData[0].code == 'permission_denied') {
            this.nullTip = true;
            this.nullWord = res.errors[0].code;
          } else {
            this.$toast.fail(res.errors[0].code);
            throw new Error(res.error)
          }
        } else {
          console.log(res,'eeeee');
          if (!this.canViewThreads) {
            this.nullTip = true;
            this.nullWord = res.errors[0].code;
          } else {
            if (this.themeListCon.length < 0) {
              this.nullTip = true
            }
            this.themeListCon = this.themeListCon.concat(res.readdata);
            this.loading = false;
            this.finished = res.readdata.length < this.pageLimit;
          }

        }
        console.log(this.themeListCon,'this.themeListCon');
      }).catch((err) => {
        if (this.loading && this.pageIndex !== 1) {
          this.pageIndex--;
        }
        this.loading = false;
      })
    },








    footFix() {
      // if(this.$route.meta.oneHeader){
          var scrollTop = window.pageYOffset || document.documentElement.scrollTop || document.body.scrollTop
          var offsetTop = document.querySelector('#testNavBar').offsetTop;
            if(this.loginBtnFix == true){
              this.loginHide = true;
              if(scrollTop > offsetTop){
                this.loginHide = true;
              } else {
                this.loginHide = false;
              }
          }
      // }
    },



    //筛选
    choTheme(themeType) {
      this.loadThemeList('isEssence',themeType);
    },
    //点击分类
    categoriesChoice(cateId) {
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
			this.$router.push({ path:'/login-user'})
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
