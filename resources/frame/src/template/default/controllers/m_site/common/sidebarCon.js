/**
 * 移动端header控制器
 */
import { Bus } from '../../../store/bus.js';
import browserDb from '../../../../../helpers/webDbHelper';
import appConfig from "../../../../../../../frame/config/appConfig";
import appCommonH from '../../../../../helpers/commonHelper';
export default {
  //接收站点是否收费的值
  props: {
    isPayVal: String,
    required: true
  },
  data: function () {
    return {
      avatarUrl: '',
      username: '',
      userId: '',
      mobile: '',
      isReal: false,           //实名认证
      // userId:'',
      userInfo: {},
      isWeixin: false,
      sidebarList1: [
        {
          text: '我的资料',
          name: '我的资料',
          path: '/modify-data', // 跳转路径
          enentType: ''
        },
        {
          text: '我的钱包',
          name: 'my-wallet',
          path: '/my-wallet', // 跳转路径
          enentType: ''
        },
        {
          text: '我的收藏',
          name: 'my-collection',
          path: '/my-collection', // 跳转路径
          enentType: ''
        },
        {
          text: '我的通知',
          name: 'my-notice',
          path: '/my-notice', // 跳转路径
          enentType: '',
          noticeSum: 0
        },
        {
          text: '我的关注',
          name: 'my-follow',
          path: '/my-follow', // 跳转路径
          enentType: ''
        }
      ],
      sidebarList2: [
        {
          text: '站点信息',
          name: 'circle-info',
          path: '/circle-info', // 跳转路径
          enentType: ''
        },
        // {
        //   text:'站点管理',
        //   name: 'management-circles',
        //   path: '/management-circles', // 跳转路径
        //   enentType: ''
        // },
        {
          text: '退出登录',
          name: 'circle',
          path: '/circle', // 跳转路径
          enentType: 1 // 事件类型
        }
      ],
      sidebarList3: [
        {
          text: '邀请朋友',
          name: '',
          path: '', // 跳转路径
          enentType: '2'
        }

      ],
      isPayValue: this.isPayVal,
      canBatchEditThreads: false,
      canEditUserGroup: false,
      canCreateInvite: false,
      noticeSum: 0,   //新通知总数
      // wxOfficial:''  //微信公众号
    }
  },
  created: function () {
    this.isPayValue = this.isPayVal;
    this.getUserInfo();
    this.getInfo();
    this.onLoad()
  },
  methods: {
    //获取用户信息
    getUserInfo() {
      var userId = browserDb.getLItem('tokenId');
      this.appFetch({
        url: 'users',
        method: 'get',
        splice: '/' + userId,
        data: {
          include: '',
        }
      }).then((res) => {
        if (!res.data.attributes.typeUnreadNotifications.liked) {
          res.data.attributes.typeUnreadNotifications.liked = 0;
        }
        if (!res.data.attributes.typeUnreadNotifications.replied) {
          res.data.attributes.typeUnreadNotifications.replied = 0;
        }
        if (!res.data.attributes.typeUnreadNotifications.rewarded) {
          res.data.attributes.typeUnreadNotifications.rewarded = 0;
        }
        if (!res.data.attributes.typeUnreadNotifications.system) {
          res.data.attributes.typeUnreadNotifications.system = 0;
        }
        this.noticeSum = res.data.attributes.typeUnreadNotifications.liked + res.data.attributes.typeUnreadNotifications.replied + res.data.attributes.typeUnreadNotifications.rewarded + res.data.attributes.typeUnreadNotifications.system;
        this.sidebarList1[3].noticeSum = this.noticeSum;
        this.userInfo = res.readdata;
        this.avatarUrl = res.readdata._data.avatarUrl;
        this.username = res.readdata._data.username;
        this.mobile = res.readdata._data.mobile;
        this.userId = res.readdata._data.id;
        this.isReal = res.readdata._data.isReal;
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
        if (res.errors) {
          this.$toast.fail(res.errors[0].code);
          throw new Error(res.error)
        } else {
          this.canBatchEditThreads = res.readdata._data.other.can_batch_edit_threads;
          this.canEditUserGroup = res.readdata._data.other.can_editUser_group;
          this.canCreateInvite = res.readdata._data.other.can_create_invite;
          var manaObj = {
            text: '站点管理',
            name: 'management-circles',
            path: '/management-circles', // 跳转路径
            enentType: ''
          };
          // 判断当用户组拥有批量管理主题、修改用户组、邀请加入权限中的任意一项时才会显示该菜单
          if (this.canBatchEditThreads || this.canEditUserGroup || this.canCreateInvite) {
            // alert('执行2')
            // this.sidebarList2.splice(1,1);
            this.sidebarList2.splice(1, 0, manaObj);
          }
        }
      });
    },
    onLoad() {
      let wxOfficial = browserDb.getLItem('siteInfo')._data.passport.offiaccount_close;
      //微信内登录
      let isWeixin = this.appCommonH.isWeixin().isWeixin;
      if (isWeixin && wxOfficial == '1') {
        this.sidebarList2.splice(1, 1);
      }
    },

    copyFocus(obj) {
      obj.blur;
      document.body.removeChild(obj);
    },
    //分享
    sidebarUrl(url, enentType) {

      var userId = browserDb.getLItem('tokenId');
      if (enentType == 1) {
        // browserDb.removeLItem('tokenId');
        // browserDb.removeLItem('Authorization');
        localStorage.clear();

        this.$router.push({ path: url });
        if (url === '/circle') {
          this.$router.go(0);
        }

      } else if (enentType == 2) {

        let circlePath = this.sidebarList3[0].path;
        if (this.isPayValue == 'pay') {
          //复制邀请链接
          var shareUrl = appConfig.baseUrl + '/circle-invite';
          var oInput = document.createElement('input');
          oInput.value = shareUrl;
          document.body.appendChild(oInput);
          oInput.select(); // 选择对象
          oInput.readOnly = true;
          oInput.id = 'copyInp';
          document.execCommand("Copy");
          oInput.setAttribute('onfocus', this.copyFocus(oInput));
          // 执行浏览器复制命令
          oInput.className = 'oInput';
          oInput.style.display = 'none';
          this.$toast.success('分享链接已复成功');
          // document.body.removeChild(oInput);
        } else {
          //如果是公开的站点
          //复制邀请链接
          var shareUrl = appConfig.baseUrl + '/open-circle/' + this.userId;
          var oInput = document.createElement('input');
          oInput.value = shareUrl;
          document.body.appendChild(oInput);
          oInput.select(); // 选择对象
          oInput.readOnly = true;
          oInput.id = 'copyInp';
          document.execCommand("Copy");
          oInput.setAttribute('onfocus', this.copyFocus(oInput));
          // 执行浏览器复制命令
          oInput.className = 'oInput';
          oInput.style.display = 'none';
          this.$toast.success('分享链接已复成功');
          // document.body.removeChild(oInput);

        }
      } else {
        this.$router.push({ path: url });
      }
    },

  },

  mounted: function () {
    // this.getVote();
    window.addEventListener('scroll', this.handleTabFix, true);
  },
  beforeRouteLeave(to, from, next) {
    window.removeEventListener('scroll', this.handleTabFix, true)
    next()
  }
}
