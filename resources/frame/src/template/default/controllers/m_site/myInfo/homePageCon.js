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
      userAvatar:''

    }
  },
  created(){
    this.imgUrl = "../../../../../../../static/images/mytx.png";
    this.loadTheme();
    // console.log(this.userId)
  },

  computed: {
      userId: function(){
          return this.$route.params.userId;
      },
  },
  methods:{
    loadTheme(){
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
          console.log(res);
          this.username = res.readdata._data.username;
          this.userAvatar = res.readdata._data.avatarUrl;
        });
      this.appFetch({
        url: 'threads',
        method: 'get',
        data: {
          'filter[userId]':this.userId,
          include: ['user', 'firstPost', 'firstPost.images', 'lastThreePosts', 'lastThreePosts.user', 'lastThreePosts.replyUser', 'firstPost.likedUsers', 'rewardedUsers'],
        }
      }).then((res) => {
        console.log(res);
        // this.userInfoAvataUrlCon = res[0].user._data.avatarUrl;
        // this.userInfoNameCon = res[0].user._data.username;
        // console.log(this.userInfoNameCon);
        this.OthersThemeList = res.readdata;
      })





      // const params = {
      //   'filter[user]': this.userId
      // };
      // params.include = 'user,firstPost,lastThreePosts,lastThreePosts.user,firstPost.likedUsers,rewardedUsers';
      // this.apiStore.find('threads', params).then(data => {
      //   // console.log(data[0]);
      //   this.userInfoAvataUrlCon = data[0].user().avatarUrl();
      //   this.userInfoNameCon = data[0].user().username();
      //   // console.log(this.userInfoCon.username());
      //   // console.log(this.userInfoCon.user().avatarUrl());
      //   this.OthersThemeList = data;
      // });
    }
  }
}
