/**
 * pc 端首页控制器
 */

export default {
	data: function() {
		return {
			headBackShow: true,
			rewardShow: false
		}
	},
	
	methods: {
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