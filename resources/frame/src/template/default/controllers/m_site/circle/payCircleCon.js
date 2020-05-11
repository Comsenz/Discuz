/**
 * pay-circle控制器
 */
import appCommonH from '../../../../../helpers/commonHelper';
export default {
  data: function () {
    return {
      isfixNav: false,
      current: 0,
      todos: [
        { text: '选项一111' },
        { text: '选项二' },
        { text: '选项三' },
        { text: '选项四' },
        { text: '选项五' },
        { text: '选项六' },
        { text: '选项七' },
        { text: '选项八' }
      ],
      siteInfo: false,
      siteUsername: '',  //站长
      joinedAt: '',    //加入时间
      sitePrice: '',   //加入价格
      username: '', //当前用户名
      loading: false,  //是否处于加载状态
      finished: false, //是否已加载完所有数据
      isLoading: false, //是否处于下拉刷新状态
      pageIndex: 1,//页码
      pageLimit: 20,
      allowRegister: '',
      isWeixin: '',
    }
  },
  created() {
    this.isWeixin = appCommonH.isWeixin().isWeixin;
    this.getInfo();
  },
  methods: {
    getInfo(initStatus = false) {
      //请求站点信息，用于判断站点是否是付费站点
      return this.appFetch({
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
          if (initStatus) {
            this.siteInfo = []
          }
          this.siteInfo = res.readdata;
          this.allowRegister = res.readdata._data.set_reg.register_close;
          if (res.readdata._data.set_site.site_author) {
            this.siteUsername = res.readdata._data.set_site.site_author.username;
          } else {
            this.siteUsername = '暂无站长信息';
          }
          this.sitePrice = res.readdata._data.set_site.site_price;
          //把站点是否收费的值存储起来，以便于传到父页面
          // this.isPayVal = res.readdata._data.siteMode;
          // if(this.isPayVal != null && this.isPayVal != ''){
          //   this.isPayVal = res.readdata._data.siteMode;
          //   //判断站点信息是否付费，用户是否登录，用户是否已支付
          //   this.detailIf(this.isPayVal,false);
          // }
        }
      });
    },
    //跳转到登录页
    loginJump: function () {
      if (this.isWeixin) {
        this.$router.push({ path: '/wx-login-bd' })
      } else {
        this.$router.push({ path: '/login-user' })
      }

    },
    //跳转到注册页
    registerJump: function () {
      if (this.isWeixin) {
        this.$router.push({ path: '/wx-sign-up-bd' })
      } else {
        this.$router.push({ path: '/sign-up' })
      }

    },
    onRefresh() {    //下拉刷新
      this.pageIndex = 1;
      this.getInfo(true).then(() => {
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

  },
  beforeRouteLeave(to, from, next) {
    next()
  },
}
