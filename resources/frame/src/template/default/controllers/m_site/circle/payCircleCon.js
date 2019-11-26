/**
 * pc 端首页控制器
 */
export default {
	data: function() {
		return {
			// title: "纯净版框架22222",
			// description: "vue + webpack + vue-router + vuex + sass + prerender + axios ",
			// num: 0,
			// voteInfo: {}
			isfixNav: false,
			current:0,
            todos: [
	            { text: '选项一111' },
	            { text: '选项二' },
	            { text: '选项三' },
	            { text: '选项四' },
	            { text: '选项五' },
	            { text: '选项六' },
	            { text: '选项七' },
	            { text: '选项八' }
        	]
		}
	},
	
	methods: {
		//跳转到登录页
		loginJump:function(){
			this.$router.push({ path:'login-user'}) 
		},
		//跳转到注册页
		registerJump:function(){
			this.$router.push({ path:'sign-up'}) 
		}
		
	},

	mounted: function() {
		// this.getVote();
		
	},
	beforeRouteLeave (to, from, next) {
	}
}