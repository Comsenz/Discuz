/**
 * pc 端首页控制器
 */

export default {
	data: function() {
		return {
			showScreen: false
		}
	},
	
	methods: {
	    //跳转到登录页
	    loginJump:function(){
	    	// alert('跳转到登录页');
	    	this.$router.push({ path:'m_site/open-circle'});
	    	// console.log(this.$router);
	    },
	    //跳转到注册页
	    registerJump:function(){
	    	// alert('跳转到注册页');
	    	this.$router.push({ path:'m_site/sign-up'});
	    },
	    postTopic:function(){
	    	// alert('跳转到发布主题页');
	    	this.$router.push({ path:'m_site/post-topic'});
	    },
		/**
		 * 给导航添加点击状态
		 */
		addClass:function(index,event){
	    this.current=index;
     
　　　　　　 //获取点击对象      
		var el = event.currentTarget;
		   // alert("当前对象的内容："+el.innerHTML);
		},
	    //筛选
	    bindScreen:function(){
	        //是否显示筛选内容
	        this.showScreen = !this.showScreen;
	    },
	      
	    hideScreen(){
	        //是否显示筛选内容
	        this.showScreen = false;
	    },
		
	},

	mounted: function() {
		// this.getVote();
		// window.addEventListener('scroll', this.handleTabFix, true);
	},
	beforeRouteLeave (to, from, next) {
	   // window.removeEventListener('scroll', this.handleTabFix, true)
	   // next()
	}
}