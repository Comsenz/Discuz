/**
 * 移动端header控制器
 */
import {Bus} from '../../../store/bus.js';
export default {
  
	data: function() {
	    return {
		    headBackShow: false,
		    oneHeader: false,
		    twoHeader: false,
		    threeHeader: false,
		    fourHeader: false,
		    isfixNav: false,
		    isfixHead: false,
		    isShow: false,
		    isHeadShow: false,
		    showHeader: false,
	        showMask: false,
	        title:'',
	        // invitePerDet: false,
	        // menuIconShow:false,
	        // searchIconShow: false,
		    // navShow: false,
		    navActi:0,
		    perDet:{
		    	themeNum: '1222',
		    	memberNum: '1222',
		    	circleLeader: '圈主名称'
		    },
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
	        },
	        {
	          name: '我的通知',
	          path: 'notice', // 跳转路径
	          query: { // 跳转参数
	          index: 4
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
	      popupShow: false,
			current:0,
	        todos: [
	            { text: '选项' },
	            { text: '选项二' },
	            { text: '选项三' },
	            { text: '选项四dsdsddsd' },
	            { text: '选项五' },
	            { text: '选项六' }
	    	]

	    }
	},
	props: {
        // title: { // 组件的标题
        //   type: String,
        //   default: () => {
        //     return '';
        //   }
        // },
        headFixed: { // 组件是否悬浮头部
          headFixed: false
          // default: () => {
          //   return 'false';
          // }
        },
        invitePerDet: { // 组件是否显示邀请人头像以及名称
          invitePerDet: false
        },
        searchIconShow: { // 组件是否显示搜索按钮
          searchIconShow: false
          
        },
        menuIconShow: { // 组件是否显示菜单按钮
          menuIconShow: false
        },
        navShow: { // 组件是否显示导航菜单
          navShow: false
        }
    },
	created(){
        // Bus.$on('setHeader', function (title, headOpeShow, navShow) {
        // 	this.title = title;
        //     this.headOpeShow = headOpeShow;
        //     this.navShow = navShow;
        //     console.log(headOpeShow);
        //     // if (typeof(curMenu) !== 'undefined') {
        //     //     console.log('curMenu', curMenu)
        //     //     this.currentMenu = curMenu;
        //     // }
        // }.bind(this));

    },
	beforeDestroy () {
        // Bus.$off('setHeader');
    },
	mounted: function() {
		this.getCircle();
	},
	methods: {
		//获取圈子主题数，成员数，圈主名称
		getCircle(){
			console.log('1234');
			this.appFetch({
		        url:'getCircle',
		        method:'post',
		        data:{
		          themeNum:this.themeNum,
		          memberNum:this.memberNum,
		          circleLeader:this.circleLeader
		        }
		    }, (res) => {
		        if (res== "200"){
		          _this.perDet = res.data;
		          console.log("报错")
		        } else {
		          console.error("获取圈子信息失败");
		        }

		    }, function(error) {
		        // console.log(error, 'error')
		    });
		},

		backUrl () {
	      	// 返回上一级
	      	window.history.go(-1)
	    },
		showPopup() {
			//侧边栏显示
	      	this.popupShow = true;
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
	    	// console.log(this.$route.meta.oneHeader);
	    	if(this.headFixed){
	    		var scrollTop = window.pageYOffset || document.documentElement.scrollTop || document.body.scrollTop
		        var offsetTop = document.querySelector('#testNavBar').offsetTop;
		        if(scrollTop > offsetTop){
		          this.showHeader = true;
		          this.isfixHead = true;
		          // console.log(this.isfixHead+'1');
		          this.isfixNav = true;
		          // scrollTop > offsetTop ? this.isfixHead = true : this.isfixHead = false;
		          // scrollTop < offsetTop ? this.isfixNav = true : this.isfixNav = false
		        } else {
		          this.showHeader = false;
		          this.isfixHead = false;
		          // console.log(this.isfixHead+'2');
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
      	// bindSidebar () {
       //  // 是否显示侧边栏
	      //   this.showSidebar = !this.showSidebar;
	      //   this.showMask =  !this.showMask;
      	// },
      	// hideSidebar(){
	      //   this.showSidebar = false;
	      //   this.showMask =  false;
      	// },
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
