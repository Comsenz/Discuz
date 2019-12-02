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
        userId:'',
		    sidebarList1: [
	        {
	          name: '我的资料',
	          path: 'login', // 跳转路径
	          // query: { // 跳转参数
           //    index: 1
	          // },
            query:'',
	          enentType: ''
	        },
	        {
	          name: '我的钱包',
	          path: 'my-wallet', // 跳转路径
	          query:'',
	          enentType: ''
	        },
	        {
	          name: '我的收藏',
	          path: 'my-collection', // 跳转路径
	          query:'',
	          enentType: ''
	        },
	        {
	          name: '我的通知',
	          path: 'my-notice', // 跳转路径
	          query:'',
	          enentType: ''
	        }
	      ],
	      sidebarList2: [
	        {
	          name: '圈子信息',
	          path: 'circle-info', // 跳转路径
	          query:'',
	          enentType: ''
	        },
	        {
	          name: '圈子管理',
	          path: 'management-circles', // 跳转路径
	          query:'',
	          enentType: ''
	        },
	        {
	          name: '退出登录',
	          path: 'login-user', // 跳转路径
	          query:'',
	          enentType: 1 // 事件类型
	        }
	      ],
	      sidebarList3: [
	        {
	          name: '邀请朋友',
	          path: 'invite-join', // 跳转路径
	          query:'',
	          enentType: ''
	        }

	      ]
	  }
  },
  created: function() {
    this.getUserInfo();
    var userId = browserDb.getLItem('tokenId');
    for(var i=0;i<this.sidebarList1.length;i++){

      this.sidebarList1[i].query = userId;
      console.log(this.sidebarList1[i].query);

    };
    for(var j=0;j<this.sidebarList1.length;j++){
      this.sidebarList2[j].query = userId;
      console.log(this.sidebarList2[j].query);

    };
    for(var h=0;h<this.sidebarList3.length;h++){
      this.sidebarList3[h].query = userId;
    };
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
  sidebarUrl(url,query,enentType){
    if(enentType == 1){
      this.$router.push({ path:url});
    } else {
      this.$router.push({ path:url+'/'+query});
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
