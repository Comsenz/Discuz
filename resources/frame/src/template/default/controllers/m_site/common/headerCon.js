/**
 * 移动端header控制器
 */
import { Bus } from '../../../store/bus.js';
import browserDb from '../../../../../helpers/webDbHelper';
import appCommonH from '../../../../../helpers/commonHelper';
import appConfig from '../../../../../../config/appConfig';
export default {
  data: function () {
    return {
      headBackShow: false,
      oneHeader: false,
      twoHeader: false,
      threeHeader: false,
      fourHeader: false,
      isfixNav: false,
      // isfixHead: false,
      isShow: false,
      isHeadShow: false,
      showHeader: false,
      showMask: false,
      title: '',
      // invitePerDet: false,
      // menuIconShow:false,
      // searchIconShow: false,
      // navShow: false,
      navActi: 0,
      perDet: {
        themeNum: '1222',
        memberNum: '1222',
        circleLeader: '站长名称'
      },
      avatarUrl: '',
      // username:'',
      mobile: '',
      popupShow: false,
      current: 0,
      userDet: [],
      categories: [],
      siteInfo: false,
      username: '',
      isPayVal: '',
      isWeixin: false,
      isPhone: false,
      firstCategoriesId: '',
      logo: false,
      viewportWidth: '',
      userId: '',
      followDet: '',
      followFlag: '',
      intiFollowVal: '0',
      noticeSum: 0,
      intiFollowChangeVal: '0',
      oldFollow: false,
      equalId: false,
      clickStatus: true,
    }
  },
  props: {
    // personInfo: { // 组件用户信息
    //   type: false
    // },
    // firstCategoryId:{
    //   type: String
    // },
    userInfoAvatarUrl: { // 组件用户信息
      type: String
    },
    userInfoName: { // 组件用户信息
      type: String
    },
    headFixed: { // 组件是否悬浮头部
      headFixed: false
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
    },
    invitationShow: { // 组件是否显示邀请这几个字
      invitationShow: false
    },
    perDetShow: { //组件是否显示站点信息
      perDet: false
    },
    logoShow: { //组件是否显示站点图标
      logoShow: false
    },
    followShow: { //组件是否显示关注信息
      followShow: false
    },
  },
  computed: {
    personUserId: function () {
      return this.$route.params.userId;
    }
  },
  created() {
    this.userId = browserDb.getLItem('tokenId');
    if (this.userId == this.personUserId) {
      this.equalId = true;
    } else {
      this.equalId = false;
    }
    this.viewportWidth = window.innerWidth;
    this.isWeixin = appCommonH.isWeixin().isWeixin;
    this.isPhone = appCommonH.isWeixin().isPhone;
    this.loadCategories();
    if (this.followShow) {
      this.loadUserFollowInfo();
    }
    if (this.userId) {
      this.loadUserInfo();
    }

    // this.loadUserInfo();
    //把第一个分类的id值传过去，便于请求初始化主题列表

  },
  watch: {
    'isfixNav': function (newVal, oldVal) {
      this.isfixNav = newVal;
    }
  },
  methods: {
    //设置底部在pc里的宽度
    limitWidth() {
      document.getElementById('testNavBar').style.width = "640px";
      let viewportWidth = window.innerWidth;
      document.getElementById('testNavBar').style.marginLeft = (viewportWidth - 640) / 2 + 'px';
    },
    //初始化请站点信息和分类接口
    loadCategories() {
      //请求站点信息
      this.appFetch({
        url: 'forum',
        method: 'get',
        data: {
          include: ['users'],
        }
      }).then((res) => {
        console.log(res.readdata._data.set_site.site_logo);
        this.siteInfo = res.readdata;

        this.logo = res.readdata._data.set_site.site_logo;
        if(res.readdata._data.set_site.site_logo == '' || res.readdata._data.set_site.site_logo == null){
          console.log('空');
          this.logo = appConfig.staticBaseUrl+'/images/logo.png';
        }
        //把站点是否收费的值存储起来，以便于传到父页面
        this.isPayVal = res.readdata._data.set_site.site_mode;
      })
      if (this.navShow) {
        //请求分类接口
        this.appFetch({
          url: 'categories',
          method: 'get',
          data: {
            include: [],
          }
        }).then((res) => {
          this.categories = res.readdata;
          this.firstCategoriesId = res.readdata[0]._data.id;
          this.$emit("update", this.firstCategoriesId);
        })
      }
    },

    //初始化请求用户关注信息
    loadUserFollowInfo() {
      this.appFetch({
        url: 'users',
        method: 'get',
        splice: '/' + this.personUserId,
        data: {
        }
      }).then((res) => {
        this.followDet = res.readdata;
        if (res.readdata._data.follow == '1') {
          this.followFlag = '已关注';
        } else if (res.readdata._data.follow == '0') {
          this.followFlag = '关注TA';
        } else {
          this.followFlag = '相互关注';
        }
        this.intiFollowVal = res.readdata._data.follow;
      })
    },
    //初始化请求用户信息
    loadUserInfo() {
      if (!this.userId) {
        return false;
      }
      this.appFetch({
        url: 'users',
        method: 'get',
        splice: '/' + this.userId,
        data: {
        }
      }).then((res) => {
        if (res.errors) {
          this.$toast.fail(res.errors[0].code);
          // throw new Error(res.error);
        } else {
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
        }
      })
    },

    //管理关注操作
    followCli(intiFollowVal) {
      var token = browserDb.getLItem('Authorization');
      if (!token) {
        browserDb.setSItem('beforeVisiting', this.$route.path);
        this.$router.push({
          path: '/login-user'
        });
      } else {
        if (!this.clickStatus) {
          return false;
        }
        this.clickStatus = false;
        let attri = new Object();
        let methodType = '';
        if (intiFollowVal == '1' || intiFollowVal == '2') {
          attri.to_user_id = this.personUserId;
          methodType = 'delete';
          this.oldFollow = intiFollowVal;
        } else {
          attri.to_user_id = this.personUserId;
          methodType = 'post';
          // this.oldFollow =  '0';
        }

        this.followRequest(methodType, attri, intiFollowVal);
      }
    },

    //关注，取消关注
    followRequest(methodType, attri, intiFollowVal) {
      this.appFetch({
        url: 'follow',
        method: methodType,
        data: {
          "data": {
            "type": "user_follow",
            "attributes": attri
          },

        }
      }).then((res) => {
        if (res.errors) {
          this.$toast.fail(res.errors[0].code);
          throw new Error(res.error)
        } else {
          if (methodType == 'delete') {
            this.intiFollowVal = '0';
            this.followDet._data.fansCount = this.followDet._data.fansCount - 1;
          } else {
            if (this.oldFollow == '1' || this.oldFollow == '0') {
              this.followDet._data.fansCount = this.followDet._data.fansCount + 1;
              this.intiFollowVal = '1';
            } else {
              this.followDet._data.fansCount = this.followDet._data.fansCount + 1;
              this.intiFollowVal = '2';
            }
            // this.intiFollowVal = intiFollowVal;
          }
          this.clickStatus = true;
        }
      })
    },
    backUrl() {
      // 返回上一级
      window.history.go(-1)
    },
    showPopup() {
      //侧边栏显示
      this.popupShow = true;
    },
    //给导航添加点击状态
    categoriesCho(cateId) {
      this.$emit('categoriesChoice', cateId);
    },

    searchJump() {
      this.$router.push({ path: '/search' });
    },

    // 先分别获得id为testNavBar的元素距离顶部的距离和页面滚动的距离
    // 比较他们的大小来确定是否添加fixedHead样式
    // 比较他们的大小来确定是否添加fixedNavBar样式
    handleTabFix() {
      if (this.headFixed) {
        var scrollTop = window.pageYOffset || document.documentElement.scrollTop || document.body.scrollTop
        var offsetTop = document.querySelector('#testNavBar').offsetTop;
        if (scrollTop > offsetTop) {
          this.showHeader = true;
          // this.isfixHead = true;
          this.isfixNav = true;
          if (this.isWeixin != true && this.isPhone != true) {
            this.limitWidth();
          }
          //
          // scrollTop > offsetTop ? this.isfixHead = true : this.isfixHead = false;
          // scrollTop < offsetTop ? this.isfixNav = true : this.isfixNav = false
        } else {
          this.showHeader = false;
          // this.isfixHead = false;
          this.isfixNav = false;
          let viewportWidth = window.innerWidth;
          document.getElementById('testNavBar').style.marginLeft = '0px';
          // scrollTop > offsetTop ? this.isfixHead = false : this.isfixHead = true;
          // scrollTop < offsetTop ? this.isfixNav = false : this.isfixNav = true
        };
      }
    },
    LogOut() {
    },
    bindEvent(typeName) {
      if (typeName == 1) {
        this.LogOut()
      }
    },
  },

  mounted: function () {
    window.addEventListener('scroll', this.handleTabFix, true);
  },
  beforeDestroy() {
    // alert('销毁');
    window.removeEventListener('scroll', this.handleTabFix);
  },
  destroyed() {
    // alert('销毁2');
    window.removeEventListener('scroll', this.handleTabFix);
  },
  beforeRouteLeave(to, from, next) {
    // alert('销毁3');
    window.removeEventListener('scroll', this.handleTabFix);
    next();
  },
  activated() {
    this.popupShow = false;
  },
}
