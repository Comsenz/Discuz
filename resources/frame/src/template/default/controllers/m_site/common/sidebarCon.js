/**
 * 移动端header控制器
 */
import {Bus} from '../../../store/bus.js';
import browserDb from '../../../../../helpers/webDbHelper';
import appConfig from "../../../../../../../frame/config/appConfig";
import appCommonH from '../../../../../helpers/commonHelper';
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
        isWeixin:false,
		    sidebarList1: [
	        {
            text:'我的资料',
	          name: '我的资料',
	          path: '/modify-data', // 跳转路径
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
	          name: 'circle',
	          path: '/circle', // 跳转路径
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
    // console.log(appConfig.devApiUrl);
    this.isPayValue = this.isPayVal;
    this.getUserInfo();
    this.onLoad()
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
  onLoad(){
    let isWeixin =this.appCommonH.isWeixin().isWeixin;
    if(isWeixin){
      this.sidebarList2.splice(2,1);
    }
  },
  sidebarUrl(url,enentType){

    var userId = browserDb.getLItem('tokenId');
    if(enentType == 1){
      // browserDb.removeLItem('tokenId');
      // browserDb.removeLItem('Authorization');
      localStorage.clear();
      this.$router.push({ path:url});
      if (url === '/circle'){
        this.$router.go(0);
      }

    } else if(enentType == 2){
      let circlePath = this.sidebarList3[0].path;
      if(this.isPayValue == 'pay'){
        //复制邀请链接
        // var shareUrl= 'http://10.0.10.210:8883/circle-invite';
        var shareUrl= appConfig.devApiUrl+'/circle-invite';
        var oInput = document.createElement('input');
        oInput.value = shareUrl;
        document.body.appendChild(oInput);
        oInput.select(); // 选择对象
        document.execCommand("Copy");
        // 执行浏览器复制命令
        oInput.className = 'oInput';
        oInput.style.display='none';
        // alert('复制成功');
        this.$toast.success('邀请链接已复制成功');
      } else {
        //如果是公开的站点
        // console.log('公开');
        //复制邀请链接
        // var shareUrl= 'http://10.0.10.210:8883/open-circle';
        var shareUrl= appConfig.devApiUrl+'/open-circle';
        var oInput = document.createElement('input');
        oInput.value = shareUrl;
        document.body.appendChild(oInput);
        oInput.select(); // 选择对象
        document.execCommand("Copy");
        // 执行浏览器复制命令
        oInput.className = 'oInput';
        oInput.style.display='none';
        // alert('复制成功');
        this.$toast.success('邀请链接已复制成功');
        // this.sidebarList3[0].name = 'open-circle';
        // circlePath = '/open-circle';
        // this.$router.push({ path:url});
      }
    } else {
      this.$router.push({ path:url});
    }
  },

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
