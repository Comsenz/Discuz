/**
 * 移动端header控制器
 */

export default {
  
	data: function() {
	    return {
	      isfixNav: false,
	      isfixHead: false,
	      isShow: false,
	      isHeadShow: false,
	      showHeader: false,
	      showSidebar: true,
	      showMask: false,
	      navShow: true,
	      sidebarList1: [
	        {
	          name: '我的资料',
	          path: 'login', // 跳转路径
	          query: { // 跳转参数
	          index: 1
	          },
	            enentType: ''
	        },
	        {
	          name: '我的钱包',
	          path: 'wallent', // 跳转路径
	          query: { // 跳转参数
	          index: 2
	          },
	            enentType: ''
	        },
	        {
	          name: '我的收藏',
	          path: 'collection', // 跳转路径
	          query: { // 跳转参数
	          index: 3
	          },
	            enentType: ''
	        }
	      ],
	      sidebarList2: [
	        {
	          name: '圈子信息',
	          path: 'login', // 跳转路径
	          query: { // 跳转参数
	          index: 1
	          },
	            enentType: ''
	        },
	        {
	          name: '圈子管理',
	          path: 'login', // 跳转路径
	          query: { // 跳转参数
	            index: 2
	          },
	          enentType: ''
	        },
	        {
	          name: '退出登录',
	          path: '', // 跳转路径
	          query: { // 跳转参数
	            index: 3
	          },
	          enentType: 1 // 事件类型
	        }
	      ],
	      sidebarList3: [
	        {
	          name: '邀请朋友',
	          path: 'login', // 跳转路径
	          query: { // 跳转参数
	          index: 1
	          },
	            enentType: ''
	        }

	      ],
	      isfixNav: false,
	      show: false,
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
		showPopup() {
      this.show = true;
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
	 	// 先分别获得id为testNavBar的元素距离顶部的距离和页面滚动的距离
    	// 比较他们的大小来确定是否添加fixedHead样式
    	// 比较他们的大小来确定是否添加fixedNavBar样式
	    handleTabFix() {
	    	if(this.navShow){
	    		// console.log(this.navShow);
	    		var scrollTop = window.pageYOffset || document.documentElement.scrollTop || document.body.scrollTop
		        var offsetTop = document.querySelector('#testNavBar').offsetTop;
		        if(scrollTop > offsetTop){
		          // console.log('大于');
		          this.showHeader = true;
		          this.isfixHead = true;
		          this.isfixNav = true;
		          // scrollTop > offsetTop ? this.isfixHead = true : this.isfixHead = false;
		          // scrollTop < offsetTop ? this.isfixNav = true : this.isfixNav = false
		        } else {
		          // console.log('小于');
		          this.showHeader = false;
		          this.isfixHead = false;
		          this.isfixNav = false;
		          // scrollTop > offsetTop ? this.isfixHead = false : this.isfixHead = true;
		          // scrollTop < offsetTop ? this.isfixNav = false : this.isfixNav = true
		        };
	    	}


	    },
	    searchJump () {

	    },
      	backUrl () {
        // 返回上一级
        window.history.go(-1)
      	},
      	bindSidebar () {
        // 是否显示侧边栏
	        this.showSidebar = !this.showSidebar;
	        this.showMask =  !this.showMask;
      	},
      	hideSidebar(){
	        this.showSidebar = false;
	        this.showMask =  false;
      	},
      	bindEvent (typeName) {
	        if (typeName == 1) {
	          this.LogOut()
	        }
      	},
	    LogOut () {
	        console.log('测试');
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
