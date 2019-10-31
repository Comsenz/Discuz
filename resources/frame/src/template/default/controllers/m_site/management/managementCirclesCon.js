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
		console.log(this.headOneShow)
	},
	methods: {
	    //跳转到登录页
	    loginJump:function(){
	    	// alert('跳转到成员管理');
	    	this.$router.push({ path:'m_site/open-circle'});
	    	// console.log(this.$router);
	    },
	    //跳转到批量管理
	    registerJump:function(){
	    	// alert('跳转到批量管理');
	    	this.$router.push({ path:'m_site/sign-up'});
	    },
		//跳转到批量管理
	    postTopic:function(){
	    	// alert('跳转到邀请成员');
	    	this.$router.push({ path:'m_site/post-topic'});
	    }

	},

	mounted: function() {
		
	},
	beforeRouteLeave (to, from, next) {
	   
	}
}
