/**
 * 邀请页控制器
 */
import browserDb from '../../../../../helpers/webDbHelper';
export default {
  data: function () {
    return {
      isfixNav: false,
      loginBtnFix: true,
      siteInfo: false,
      roleId: '',
      roleResult: '',
      finished: false, //是否已加载完所有数据
      isLoading: false, //是否处于下拉刷新状态
      pageIndex: 1,//页码
      pageLimit: 20,
      allowRegister: '',
      limitList: '',
      userInfo: '',
      tipsCode: '',
      tipsStatus: '',
    }
  },
  computed: {
    code: function () {
      return this.$route.query.code;
    }
  },
  //用于数据初始化
  created: function () {

    var roleId = '10';
    this.roleId = roleId;
    this.loadSite();
  },
  methods: {

    loadSite(initStatus = false) {
      //请求初始化站点信息数据
      this.$store.dispatch("appSiteModule/loadForum").then(res => {
        if (initStatus) {
          this.siteInfo = []
        }
        this.siteInfo = res.readdata;
        this.allowRegister = res.readdata._data.set_reg.register_close;
      });

      //请求初始化角色信息数据
      return this.appFetch({
        url: 'invite',
        method: 'get',
        splice: '/' + this.code,
        data: {
        }
      }).then((res) => {
        if (res.errors) {
          this.$toast.fail('该邀请码不存在，请联系站长获取新的邀请码,若继续注册将以默认角色加入本站！')
          this.$router.push({ path: '/' });
        } else {
          this.userInfo = res.readdata.user;
          this.roleResult = res.readdata.group._data.name;
          this.limitList = res.readdata.group;
          if (res.readdata._data.status == 0) {
            this.tipsStatus = true;
            this.tipsCode = '该邀请码已失效，请联系站长获取新的邀请码,若继续注册将以默认角色加入本站！'
          } else if (res.readdata._data.status == 2) {
            this.tipsStatus = true;
            this.tipsCode = '该邀请码已使用，请联系站长获取新的邀请码,若继续注册将以默认角色加入本站！'
          } else if (res.readdata._data.status == 3) {
            this.tipsStatus = true;
            this.tipsCode = '该邀请码已过期，请联系站长获取新的邀请码,若继续注册将以默认角色加入本站！'
          } else {
            this.tipsStatus = false;
          }
        }
      });
    },

    logBtnFix() {
      var scrollTop = window.pageYOffset || document.documentElement.scrollTop || document.body.scrollTop;
      if (scrollTop > 10) {
        this.loginBtnFix = false;
      } else {
        this.loginBtnFix = true;
      };
    },


    //跳转到登录页
    loginJump: function () {
      this.$router.push({ path: 'login-user' })
    },
    //跳转到注册页
    registerJump: function () {
      if (this.code != '' || this.code != null) {
        browserDb.setSItem('code', this.code)
      }
      this.$router.push({ path: 'sign-up' })
    },
		/**
		 * 给导航添加点击状态
		 */
    addClass: function (index, event) {
      this.current = index;

      //获取点击对象
      var el = event.currentTarget;
      // alert("当前对象的内容："+el.innerHTML);
    },

    onRefresh() {    //下拉刷新
      this.pageIndex = 1;
      this.loadSite(true).then(() => {
        this.$toast('刷新成功');
        this.finished = false;
        this.isLoading = false;
      }).catch((err) => {
        this.$toast('刷新失败');
        this.isLoading = false;
      })
    }

  },

  mounted: function () {
    // this.getVote();
    window.addEventListener('scroll', this.logBtnFix, true);
  },
  beforeRouteLeave(to, from, next) {
    window.removeEventListener('scroll', this.logBtnFix, true)
    next()
  }
}
