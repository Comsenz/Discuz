/**
 * pc 端首页控制器
 */

export default {
	data: function() {
		return {
			showScreen: false,
			isfixFoot: true,
			padBfoot: true
		}
	},
	
	methods: {

		// 先分别获得id为testNavBar的元素距离顶部的距离和页面滚动的距离
    	// 比较他们的大小来确定是否添加fixedHead样式
    	// 比较他们的大小来确定是否添加fixedNavBar样式
		footFix() {
	    	// console.log(this.$route.meta.oneHeader);
	    	if(this.$route.meta.oneHeader){
	    		var scrollTop = window.pageYOffset || document.documentElement.scrollTop || document.body.scrollTop
		        var offsetTop = document.querySelector('#testNavBar').offsetTop;
		        if(scrollTop > offsetTop){
		          this.isfixFoot = false;
		          this.padBfoot = false;  
		          console.log(this.padBfoot);
		        } else {
		          this.isfixFoot = true;
		          this.padBfoot = true;
		          // console.log(this.padBfoot)
		        };
	    	}

	    },




	    //跳转到登录页
	    loginJump:function(){
	    	console.log(this.oneHeader);
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
		window.addEventListener('scroll', this.footFix, true);
	},
	beforeRouteLeave (to, from, next) {
	   window.removeEventListener('scroll', this.footFix, true)
	   // next()
	}
}