/**
 * 移动端header控制器
 */

export default {
	data: function() {
		return {
			isfixTab: false,
			isShow: false,
	      	showSidebar: false,
	      	sidebarList: [
		        {
		          	name: '我的资料',
		          	path: 'login', // 跳转路径
		          	query: { // 跳转参数
		            index: 1
		          },
		          	enentType: ''
		        },
		        {
		          	name: '退出登录',
		          	path: '', // 跳转路径
		          	query: { // 跳转参数
		            index: 1
		          },
		          	enentType: 1 // 事件类型
		        }
	      	]

		}
	},
	methods: {
		
	    // 先分别获得id为testNavBar的元素距离顶部的距离和页面滚动的距离
		// 比较他们的大小来确定是否添加fixedNavbar样式
		handleTabFix() {
		    var scrollTop = window.pageYOffset || document.documentElement.scrollTop || document.body.scrollTop
		    var offsetTop = document.querySelector('#testNavBar').offsetTop;
		    if(scrollTop > offsetTop){
		    	console.log('dayu');
		    };  
		    scrollTop > offsetTop ? this.isfixTab = true : this.isfixTab = false
	    },
	    searchJump () {

	    },
	    backUrl () {
	      // 返回上一级
	      window.history.go(-1)
	    },
	    bindSidebar () {
	      // 是否显示侧边栏
	      this.showSidebar = !this.showSidebar
	    },
	    bindEvent (typeName) {
	      if (typeName == 1) {
	        this.LogOut()
	      }
	    },
	    LogOut () {
	      console.log('测试')
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