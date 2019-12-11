/**
 * 移动端header控制器
 */
import {Bus} from '../../../store/bus.js';
import browserDb from '../../../../../helpers/webDbHelper';
export default {
	data: function() {
    return {
        avatarUrl:'',
        username:'',
        mobile:'',
        // userId:'',
		    sidebarList1: [
	        {
            text:'我的资料',
	          name: '我的资料',
	          path: 'login', // 跳转路径
	          // query: { // 跳转参数
           //    index: 1
	          // },
            // query:{
            //   userId:''
            // },
	          enentType: ''
	        },
	        {
            text:'我的钱包',
	          name: 'my-wallet',
	          path: '/my-wallet', // 跳转路径
	          // query:{
	          //   userId:''
	          // },
	          enentType: ''
	        },
	        {
            text:'我的收藏',
	          name: 'my-collection',
	          path: '/my-collection', // 跳转路径
	          // query:{
	          //   userId:''
	          // },
	          enentType: ''
	        },
	        {
            text:'我的通知',
	          name: 'my-notice',
	          path: '/my-notice', // 跳转路径
	          // query:{
	          //   userId:''
	          // },
	          enentType: ''
	        }
	      ],
	      sidebarList2: [
	        {
            text:'站点信息',
	          name: 'circle-info',
	          path: '/circle-info', // 跳转路径
	          // query:{
	          //   userId:''
	          // },
	          enentType: ''
	        },
	        {
            text:'站点管理',
	          name: 'management-circles',
	          path: '/management-circles', // 跳转路径
	          // query:{
	          //   userId:''
	          // },
	          enentType: ''
	        },
	        {
            text:'退出登录',
	          name: 'login-user',
	          path: '/login-user', // 跳转路径
	          // query:{
	          //   userId:''
	          // },
	          enentType: 1 // 事件类型
	        }
	      ],
	      sidebarList3: [
	        {
            text:'邀请朋友',
	          name: 'invite-join',
	          path: '/invite-join', // 跳转路径
	          // query:{
	          //   userId:''
	          // },
	          enentType: ''
	        }

	      ]
	  }
  },
  created: function() {
    this.getUserInfo();
  },
  methods:{
  //获取用户信息
  getUserInfo(){
    var userId = browserDb.getLItem('tokenId');
      this.apiStore.find('users', userId).then(data => {
        // console.log(data.data.attributes.mobile);
        this.avatarUrl = data.data.attributes.avatarUrl;
        this.username = data.data.attributes.username;
        this.mobile = data.data.attributes.mobile;
      });
  },
  sidebarUrl(url,enentType){
    var userId = browserDb.getLItem('tokenId');
    if(enentType == 1){
      this.$router.push({ path:url});
    } else {
      this.$router.push({ path:url+'/'+userId});
    }
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
