/**
 * pc 端首页控制器
 */
import browserDb from '../../../../../helpers/webDbHelper';
export default {
  data: function () {
    return {
      isfixNav: false,
      siteInfo: false,
      username: '',
      limitList: false,
      allowRegister: '',
    }
  },
  //用于数据初始化
  created: function () {
    this.loadSite();
    var userId = browserDb.getLItem('tokenId');
  },
  methods: {
    //请求初始化用户信息数据
    loadSite() {
      //请求初始化站点信息数据
      this.appFetch({
        url: 'forum',
        method: 'get',
        data: {
          include: ['users'],
        }
      }).then((res) => {
        if (res.errors) {
          this.$toast.fail(res.errors[0].code);
          throw new Error(res.error)
        } else {
          this.siteInfo = res.readdata;
          this.allowRegister = res.readdata._data.set_reg.register_close;
        }

      });

      //请求权限列表数据
      this.appFetch({
        url: 'groups',
        method: 'get',
        data: {
          include: ['permission'],
          'filter[isDefault]': 1
        }
      }).then((res) => {
        if (res.errors) {
          this.$toast.fail(res.errors[0].code);
          throw new Error(res.error)
        } else {
          this.limitList = res.readdata[0];
        }
      });
      var userId = browserDb.getLItem('tokenId');
      if (!userId) {
        return
      } else {
        this.appFetch({
          url: 'users',
          method: 'get',
          splice: '/' + userId,
          data: {
            include: 'groups',
          }
        }).then((res) => {
          if (res.errors) {
            this.$toast.fail(res.errors[0].code);
            throw new Error(res.error)
          } else {
            this.roleList = res.readdata.groups;
            if (res.readdata._data.joinedAt == '' || res.readdata._data.joinedAt == null) {
              this.joinedAt = res.readdata._data.createdAt;
            } else {
              this.joinedAt = res.readdata._data.joinedAt;
            }
          }
        })
      }


    },
    //查看更多站点成员
    moreCilrcleMembers() {
      this.$router.push({ path: 'circle-members' });
    },
    //点击站点成员头像，跳转到用户主页
    membersJump(userId) {
      this.$router.push({ path: '/home-page/' + userId });
    },

    //跳转到登录页
    loginJump: function () {
      this.$router.push({ path: '/login-user' });
    },
    //跳转到注册页
    registerJump: function () {
      this.$router.push({ path: '/sign-up' });
    },
		/**
		 * 给导航添加点击状态
		 */
    // 		addClass:function(index,event){
    //         this.current=index;

    // 　　　　　　 //获取点击对象
    //        var el = event.currentTarget;
    //        // alert("当前对象的内容："+el.innerHTML);
    //     }

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
