/**
 * 个人主页
 */


export default {
  data:function () {
    return {
      // imgUrl:'',
      // stateTitle:'点赞了我',
      // time:"15分钟前",
      // userName:'Elizabeth',
      // contText:'我们来看一下程序员经常去的 14 个顶级开发者社区，如果你还不知道它们，那么赶紧去看看，也许会有意想不到的收获。',
      OthersThemeList:[
        // 'aaa':'sdf'
      ],
      userInfoAvataUrlCon:'',
      userInfoNameCon:''
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
      const params = {
        'filter[user]': this.userId
      };
      params.include = 'user,firstPost,lastThreePosts,lastThreePosts.user,firstPost.likedUsers,rewardedUsers';
      this.apiStore.find('threads', params).then(data => {
        // console.log(data[0]);
        this.userInfoAvataUrlCon = data[0].user().avatarUrl();
        this.userInfoNameCon = data[0].user().username();
        // console.log(this.userInfoCon.username());
        // console.log(this.userInfoCon.user().avatarUrl());
        this.OthersThemeList = data;
      });
    }
  }
}
