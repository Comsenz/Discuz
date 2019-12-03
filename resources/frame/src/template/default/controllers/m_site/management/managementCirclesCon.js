/**
 * 移动端圈子管理页控制器
 */

export default {
	data: function() {
		return {
			// headOneShow: false,
			// navShow: false,
			// headTwoShow: true
		}
	},
	 //用于数据初始化
    created: function(){
		// console.log(this.headOneShow)
		this.managementCircles()
	},
	methods: {
	    //跳转到成员管理
	    // loginJump:function(){
	    // 	// alert('跳转到成员管理');
	    // 	this.$router.push({ path:'/open-circle'});
	    // 	// console.log(this.$router);
	    // },
	    // //跳转到批量管理
	    // registerJump:function(){
	    // 	// alert('跳转到批量管理');
	    // 	this.$router.push({ path:'/sign-up'});
	    // },
		// //跳转到批量管理
	    // postTopic:function(){
	    // 	// alert('跳转到邀请成员');
	    // 	this.$router.push({ path:'/post-topic'});
	    // }

	// },
	managementCircles(str){
		switch (str) {
		  case 'circle-members':
			this.$router.push('/circle-members'); //成员管理
			break;
		  case 'delete':
			this.$router.push('/delete'); //批量管理
			break;
			case 'circle-manage-invite':
			this.$router.push('/circle-manage-invite'); //成员邀请
			break;
		  default:
			// this.$router.push('/');
		}
	  },
	},
	

	mounted: function() {
		
	},
	beforeRouteLeave (to, from, next) {
	   next()
	}
}
