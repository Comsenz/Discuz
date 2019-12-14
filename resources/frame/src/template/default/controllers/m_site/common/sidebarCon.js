/**
 * 移动端header控制器
 */
import {Bus} from '../../../store/bus.js';
import browserDb from '../../../../../helpers/webDbHelper';
export default {
  //接收站点是否收费的值
  props: {
    isPayVal: String,
    required: true
  },
	data: function() {
    return {
        avatarUrl:'',
        username:'',
        mobile:'',
        // userId:'',
        userInfo:{},
		    sidebarList1: [
	        {
            text:'我的资料',
	          name: '我的资料',
	          path: 'login', // 跳转路径
	          enentType: ''
	        },
	        {
            text:'我的钱包',
	          name: 'my-wallet',
	          path: '/my-wallet', // 跳转路径
	          enentType: ''
	        },
	        {
            text:'我的收藏',
	          name: 'my-collection',
	          path: '/my-collection', // 跳转路径
	          enentType: ''
	        },
	        {
            text:'我的通知',
	          name: 'my-notice',
	          path: '/my-notice', // 跳转路径
	          enentType: ''
	        }
	      ],
	      sidebarList2: [
	        {
            text:'站点信息',
	          name: 'circle-info',
	          path: '/circle-info', // 跳转路径
	          enentType: ''
	        },
	        {
            text:'站点管理',
	          name: 'management-circles',
	          path: '/management-circles', // 跳转路径
	          enentType: ''
	        },
	        {
            text:'退出登录',
	          name: 'login-user',
	          path: '/login-user', // 跳转路径
	          enentType: 1 // 事件类型
	        }
	      ],
	      sidebarList3: [
	        {
            text:'邀请朋友',
	          name: '',
	          path: '', // 跳转路径
	          enentType: '2'
	        }

	      ],
        isPayValue:this.isPayVal
	  }
  },
  created: function() {
    this.isPayValue = this.isPayVal;
    this.getUserInfo();
    // console.log(this.isPayValue);
  },
  methods:{
  //获取用户信息
  getUserInfo(){
    var userId = browserDb.getLItem('tokenId');
      this.appFetch({
        url: 'users',
        method: 'get',
        splice:'/'+userId,
        data: {
          include: '',
        }
      }).then((res) => {
        this.userInfo = res.readdata;
        this.avatarUrl = res.readdata._data.avatarUrl;
        this.username = res.readdata._data.username;
        this.mobile = res.readdata._data.mobile;
      })

  },
  sidebarUrl(url,enentType){
    var userId = browserDb.getLItem('tokenId');
    if(enentType == 1){
      browserDb.removeLItem('tokenId');
      browserDb.removeLItem('Authorization');
      this.$router.push({ path:url});
    } else if(enentType == 2){
      let circlePath = this.sidebarList3[0].path;
      if(this.isPayValue == 'pay'){
        //如果是付费的站点
        // console.log('付费');
        this.sidebarList3[0].name = 'circle-invite';
        circlePath = '/circle-invite';
        this.$router.push({ path:circlePath});
      } else {
        //如果是公开的站点
        // console.log('公开');
        this.sidebarList3[0].name = 'open-circle';
        circlePath = '/open-circle';
        this.$router.push({ path:url});
      }
    } else {
      this.$router.push({ path:url});
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
