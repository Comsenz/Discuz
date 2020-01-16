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
      userId:'',
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
      isPayValue:this.isPayVal,
      canBatchEditThreads: false,
      canEditUserGroup: false,
      canCreateInvite: false
    }
  },
  created: function() {
    // console.log(appConfig.devApiUrl);
    this.isPayValue = this.isPayVal;
    this.getUserInfo();
    this.getInfo();
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
        this.userId = res.readdata._data.id;
        console.log(res.readdata._data.id,'是id')
      })

  },
  getInfo() {
    //请求站点信息，用于判断站点是否是付费站点
    this.appFetch({
      url: 'forum',
      method: 'get',
      data: {
      }
    }).then((res) => {
      if (res.errors){
        this.$toast.fail(res.errors[0].code);
        throw new Error(res.error)
      } else {
        this.canBatchEditThreads = res.readdata._data.canBatchEditThreads;
        this.canEditUserGroup = res.readdata._data.canEditUserGroup;
        this.canCreateInvite = res.readdata._data.canCreateInvite;

        // 判断当用户组拥有批量管理主题、修改用户组、邀请加入权限中的任意一项时才会显示该菜单
        if (!(this.canBatchEditThreads || this.canEditUserGroup || this.canCreateInvite)) {
          this.sidebarList2.splice(1,1);
        }
      }
    });
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
        console.log(this.isPayValue,'付费站点')
        //复制邀请链接
        var shareUrl= 'http://10.0.10.210:8883/circle-invite';
        // var shareUrl= appConfig.devApiUrl+'/circle-invite';
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
        // var shareUrl= 'http://10.0.10.210:8883/open-circle/'+this.userId;
        var shareUrl= appConfig.devApiUrl+'/open-circle/'+this.userId;
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
