/**
 * 个人主页
 */


export default {
  data:function () {
    return {
      OthersThemeList:[
        // 'aaa':'sdf'
      ],
      userInfoAvataUrlCon:'',
      userInfoNameCon:'',
      username:'',
      userAvatar:'',
      loading: false,  //是否处于加载状态
      finished: false, //是否已加载完所有数据
      isLoading: false, //是否处于下拉刷新状态
      pageIndex: 1,//页码
      pageLimit: 20,//每页20条
      offset: 100, //滚动条与底部距离小于 offset 时触发load事件

    }
  },
  created(){
    this.imgUrl = "../../../../../../../static/images/noavatar.gif";
    this.loadTheme();
    // console.log(this.userId)
  },

  computed: {
      userId: function(){
          return this.$route.params.userId;
      },
  },
  methods:{
    loadTheme(initStatus = false){
      //请求用户信息
      //初始化请求User信息，用于判断当前用户是否已付费
        this.appFetch({
          url: 'users',
          method: 'get',
          splice:'/'+this.userId,
          data: {
            include: 'groups',
          }
        }).then((res) => {
          if (res.errors){
            this.$toast.fail(res.errors[0].code);
            throw new Error(res.error)
          }else{
          console.log('234');
          console.log(res);
          this.username = res.readdata._data.username;
          this.userAvatar = res.readdata._data.avatarUrl;
          }
        });
     return this.appFetch({
        url: 'threads',
        method: 'get',
        data: {
          'filter[userId]':this.userId,
          include: ['user', 'firstPost', 'firstPost.images', 'lastThreePosts', 'lastThreePosts.user', 'lastThreePosts.replyUser', 'firstPost.likedUsers', 'rewardedUsers'],
          'page[number]': this.pageIndex,
          'page[limit]': this.pageLimit,
          'filter[isDeleted]':'no'
        }
      }).then((res) => {
        if (res.errors){
          this.$toast.fail(res.errors[0].code);
          throw new Error(res.error)
        }else{
        if(initStatus){
          this.OthersThemeList = []
        }
        console.log(res);
        // this.userInfoAvataUrlCon = res[0].user._data.avatarUrl;
        // this.userInfoNameCon = res[0].user._data.username;
        // console.log(this.userInfoNameCon);
        this.OthersThemeList =this.OthersThemeList.concat(res.readdata);
        this.loading = false;
        this.finished = res.data.length < this.pageLimit;
      }
      }).catch((err)=>{
        if(this.loading && this.pageIndex !== 1){
          this.pageIndex--;
        }
        this.loading = false;
      })
    },
    onLoad(){    //上拉加载
      this.loading = true;
      this.pageIndex++;
      this.loadTheme();
    },
    onRefresh(){    //下拉刷新
      this.pageIndex = 1;
      this.loadTheme(true).then(()=>{
        this.$toast('刷新成功');
        this.finished = false;
        this.isLoading = false;
      }).catch((err)=>{
        this.$toast('刷新失败');
        this.isLoading = false;
      })
  }
  },
  beforeRouteLeave(to, from, next) {
		next()
	}
}
