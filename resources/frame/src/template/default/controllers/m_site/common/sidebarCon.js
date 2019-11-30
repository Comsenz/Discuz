/**
 * 移动端header控制器
 */
import {Bus} from '../../../store/bus.js';
import browserDb from '../../../../../helpers/webDbHelper';
export default {
	data: function() {
    return {
        avatarUr:'',
        username:'',
        mobile:'',
        userId:'',
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

	      ]
	  }
  },
	props: {
    userAvatarUrl: { // 组件用户信息
      type: String
    },
    userName: { // 组件用户名
      type: String
    },
    userMobile: { // 组件用户手机号
      type: String
    }

  },
  created: function() {
    this.getCircle();
  },
  methods:{
  //获取圈子主题数，成员数，圈主名称
    getCircle(){

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
