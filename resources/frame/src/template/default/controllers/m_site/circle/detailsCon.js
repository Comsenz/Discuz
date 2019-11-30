/**
 * pc 端首页控制器
 */

export default {
	data: function() {
		return {
			headBackShow: true,
			rewardShow: false,
      themeListCon:[]
		}
	},
created(){
    this.detailsLoad();
    // console.log(this.userId)
  },

  computed: {
      userId: function(){
          return this.$route.params.userId;
      },
  },
	methods: {
    detailsLoad(){
      const params = {};
      params.include = 'user,firstPost,lastThreePosts,lastThreePosts.user,firstPost.likedUsers,rewardedUsers';
      this.apiStore.find('threads', params).then(data => {
        // console.log(data[0].firstPost().id());
        // console.log(data[0].user().username());
        this.themeListCon = data;
      });
    },

		showRewardPopup:function() {
	      this.rewardShow = true;
	  },
		// //跳转到回复页
		replayJump:function() {
			this.$router.push({ path:'post-topic'})
		}
	},

	mounted: function() {

	},
	beforeRouteLeave (to, from, next) {

	}
}
