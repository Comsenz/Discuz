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
		/**
		 * 给导航添加点击状态
		 */
		addClass:function(index,event){
            this.current=index;
             
　　　　　　 //获取点击对象      
           var el = event.currentTarget;
           // alert("当前对象的内容："+el.innerHTML);
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