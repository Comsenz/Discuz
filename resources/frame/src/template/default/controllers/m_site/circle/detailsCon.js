/**
 * pc 端首页控制器
 */
import Thread from '../../../../../common/models/Thread';
// import User from '../../../../../common/models/User';

export default {
	data: function() {
		return {
			headBackShow: true,
			rewardShow: false,
      themeCon:false,
      themeShow:false
		}
	},
  created(){
    console.log(this.themeCon);
      if(!this.themeCon){
      // console.log('1111');
      this.themeShow = false;
    } else {
      // console.log('22222');
      this.themeShow = true
    }
    // this.Thread = new Thread();
    // this.themeCon.user = new User();
    this.detailsLoad();
    // console.log(this.themeId);
  },

  computed: {
      themeId: function(){
          return this.$route.params.themeId;
      }
  },
	methods: {
    detailsLoad(){
      const params = {};
      params.include = 'user,posts.likedUsers';
      const threads= 'threads/'+this.themeId;
      this.apiStore.find(threads, params).then(data => {
        this.themeCon = data;
        this.themeShow = true;
        console.log(data);
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
    console.log('2345');
	},
	beforeRouteLeave (to, from, next) {

	}
}
