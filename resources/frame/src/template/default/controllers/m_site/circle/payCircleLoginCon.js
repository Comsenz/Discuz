/**
 * 付费站点-已支付-未登录控制器
 */
import Header from '../../../view/m_site/common/headerView'
export default {
	data: function() {
		return {
			headOpeShow: false,
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
	components:{
    	Header
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
		window.addEventListener('scroll', this.handleTabFix, true);
	},
	beforeRouteLeave (to, from, next) {
	   window.removeEventListener('scroll', this.handleTabFix, true)
	   next()
	}
}