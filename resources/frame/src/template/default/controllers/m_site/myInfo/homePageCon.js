/**
 * 个人主页
 */

import browserDb from '../../../../../helpers/webDbHelper';
import appConfig from '../../../../../../config/appConfig';
export default {
  data: function () {
    return {
      OthersThemeList: [],    //主题列表
      loading: false,  //是否处于加载状态
      finished: false, //是否已加载完所有数据
      isLoading: false, //是否处于下拉刷新状态
      pageIndex: 1,//页码
      pageLimit: 20,//每页20条
      offset: 100, //滚动条与底部距离小于 offset 时触发load事件
      token: '',
      userGroups: {
        status: false,
        name: ''
      },   //用户组
      userInfoAvatarUrl: '',
      followDet: '',      //当前访问的用户信息
      oldFollow: false,
      intiFollowVal: '',
      clickStatus: true,

    }
  },
  created() {
    this.imgUrl = "../../../../../../../static/images/noavatar.gif";
    this.token = browserDb.getLItem('Authorization');
    if (this.userId) {
      //当从路由获取到userId时，就加载当前用户信息
      this.loadUserFollowInfo();
    }
    //请求当前用户的主题列表
    this.loadTheme();
  },

  computed: {
    //获取当前路由参数（用户id）
    userId: function () {
      return this.$route.params.userId;
    },
  },
  methods: {
    //初始化请求用户信息
    loadUserFollowInfo() {
      this.appFetch({
        url: 'users',
        method: 'get',
        splice: '/' + this.userId,
        data: {
          include: ['groups'],
        }
      }).then((res) => {
        // console.log(res, '######');
        this.followDet = res.readdata;
        this.isReal = res.readdata._data.isReal;
        if (res.readdata._data.follow == '1') {
          this.followFlag = '已关注';
        } else if (res.readdata._data.follow == '0') {
          this.followFlag = '关注TA';
        } else {
          this.followFlag = '相互关注';
        }
        this.intiFollowVal = res.readdata._data.follow;
        this.userInfoAvatarUrl = res.readdata._data.avatarUrl;
        if (res.readdata._data.avatarUrl != '' && res.readdata._data.avatarUrl != null) {
          this.followDet._data.avatarUrl = res.readdata._data.avatarUrl;
        } else {
          this.followDet._data.avatarUrl = appConfig.staticBaseUrl + '/images/noavatar.gif';
        }
        // this.userGroups.status = res.readdata._data.showGroups;
        // this.userGroups.name = res.readdata.groups[0]._data.name;
      })
    },
    loadTheme(initStatus = false) {
      //主题接口
      this.appFetch({
        url: 'threads',
        method: 'get',
        data: {
          'filter[userId]': this.userId,
          include: ['user', 'firstPost', 'user.groups', 'firstPost.images', 'lastThreePosts', 'lastThreePosts.user', 'lastThreePosts.replyUser', 'firstPost.likedUsers', 'rewardedUsers', 'threadVideo'],
          'page[number]': this.pageIndex,
          'page[limit]': this.pageLimit,
          'filter[isDeleted]': 'no'
        }
      }).then((res) => {
        if (res.errors) {
          this.$toast.fail(res.errors[0].code);
          this.loading = false;
          throw new Error(res.error)
        } else {
          if (initStatus) {
            this.OthersThemeList = []
          }
          this.OthersThemeList = this.OthersThemeList.concat(res.readdata);
          this.loading = false;
          this.finished = res.data.length < this.pageLimit;
        }
      }).catch((err) => {
        if (this.loading && this.pageIndex !== 1) {
          this.pageIndex--;
        }
        this.loading = false;
      })

    },

    //管理关注操作
    followCli(intiFollowVal) {
      let methodType = '';
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
        if (intiFollowVal == '1' || intiFollowVal == '2') {
          attri.to_user_id = this.userId;
          methodType = 'delete';
          this.oldFollow = intiFollowVal;
        } else {
          attri.to_user_id = this.userId;
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
            if (res.readdata._data.is_mutual && res.readdata._data.is_mutual == 0) {
              this.followDet._data.fansCount = this.followDet._data.fansCount + 1;
              this.intiFollowVal = '1';
            } else if (res.readdata._data.is_mutual && res.readdata._data.is_mutual == 1) {
              this.followDet._data.fansCount = this.followDet._data.fansCount + 1;
              this.intiFollowVal = '2';
            }
            // this.intiFollowVal = intiFollowVal;
          }
          this.clickStatus = true;
        }
      })
    },

    onLoad() {    //上拉加载
      this.loading = true;
      this.pageIndex++;
      this.loadTheme();
    },
    onRefresh() {    //下拉刷新
      this.pageIndex = 1;
      this.loadTheme(true).then(() => {
        this.$toast('刷新成功');
        this.finished = false;
        this.isLoading = false;
      }).catch((err) => {
        this.$toast('刷新失败');
        this.isLoading = false;
      })
    }
  },
  beforeRouteLeave(to, from, next) {
    next()
  }
}
