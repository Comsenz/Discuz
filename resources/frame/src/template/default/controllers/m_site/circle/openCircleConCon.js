/**
 * wap详情页控制器
 */
import { Bus } from '../../../store/bus.js';
import browserDb from '../../../../../helpers/webDbHelper';
export default {
  data: function () {
    return {
      headBackShow: true,
      rewardShow: false,
      themeCon: false,
      themeShow: false,
      qrcodeShow: false,
      amountNum: '',
      codeUrl: '',
      themeChoList: [
        {
          typeWo: '加精',
          type: '2'
        },
        {
          typeWo: '置顶',
          type: '3'
        },
        {
          typeWo: '删除',
          type: '4'
        },
        {
          typeWo: '编辑',
          type: '5'
        }

      ],
      showScreen: false,
      request: false,
      isliked: '',
      likedClass: '',
      loading: false,  //是否处于加载状态
      finished: false, //是否已加载完所有数据
      isLoading: false, //是否处于下拉刷新状态
      pageIndex: 1,//页码
      pageLimit: 20,
    }
  },
  created() {
    if (!this.themeCon) {
      this.themeShow = false;
    } else {
      this.themeShow = true
    }
    this.detailsLoad();
  },

  computed: {
    themeId: function () {
      return this.$route.params.themeId;
    }
  },
  methods: {
    //初始化请求主题列表数据
    detailsLoad(initStatus = false) {
      // let threads = 'threads/'+this.themeId;
      return this.appFetch({
        url: 'threads',
        splice: '/' + this.themeId,
        method: 'get',
        data: {
          'filter[isDeleted]': 'no',
          include: ['user', 'posts', 'posts.user', 'posts.likedUsers', 'firstPost', 'firstPost.likedUsers', 'rewardedUsers', 'category'],
        }
      }).then((res) => {
        if (res.errors) {
          this.$toast.fail(res.errors[0].code);
          // throw new Error(res.error)
        } else {
          if (initStatus) {
            this.themeCon = []
          }
          this.themeShow = true;
          this.themeCon = this.themeCon.concat(res.readdata);
        }
      })
    },
    //分享
    shareTheme() {
      var userId = browserDb.getLItem('tokenId');
      this.appFetch({
        url: 'users',
        method: 'get',
        splice: '/' + userId,
        data: {
          include: '',
        }
      }).then((res) => {
        if (res.errors) {
          this.$toast.fail(res.errors[0].code);
          // throw new Error(res.error)
        } else {
          if (res.readdata._data.paid) {
            this.$router.push({
              path: '/pay-circle-con',
              name: 'pay-circle-con',
            })
          } else {
            this.$router.push({
              path: '/open-circle-con',
              name: 'open-circle-con'
            })
          }
        }
      })

    },

    //主题管理
    bindScreen: function () {
      //是否显示筛选内容
      this.showScreen = !this.showScreen;
    },
    //管理操作
    themeOpera(postsId, clickType, cateId, content) {
      let attri = new Object();
      if (clickType == 1) {
        attri.isFavorite = true;
        content = '';
        this.themeOpeRequest(attri, cateId);
      } else if (clickType == 2) {
        content = '';
        this.themeOpeRequest(attri, cateId);
        attri.isEssence = true;
      } else if (clickType == 3) {
        content = '';
        // request = true;
        attri.isSticky = true;
        this.themeOpeRequest(attri, cateId);
      } else if (clickType == 4) {
        attri.isDeleted = true;
        content = '';
        this.themeOpeRequest(attri, cateId);
      } else {
        // content = content
        //跳转到发帖页
        this.$router.push({
          path: '/post-topic',
          name: 'post-topic',
          params: { themeId: this.themeId, postsId: postsId, themeContent: content }
        })
      }
    },
    //主题操作接口请求
    themeOpeRequest(attri, cateId) {
      // let threads = 'threads/' + this.themeId;
      this.appFetch({
        url: 'threads',
        splice: '/' + this.themeId,
        method: 'patch',
        data: {
          "data": {
            "type": "threads",
            "attributes": attri
          },
          "relationships": {
            "category": {
              "data": {
                "type": "categories",
                "id": cateId
              }
            }
          }
        }
      }).then((res) => {
        if (res.errors) {
          this.$toast.fail(res.errors[0].code);
          // throw new Error(res.error)
        }
      })


    },
    //点赞/删除
    replyOpera(postId, type, isLike) {
      let attri = new Object();
      if (type == 1) {
        attri.isDeleted = true;
      } else if (type == 2) {
        if (isLike) {
          //如果已点赞
          attri.isLiked = false;
        } else {
          //如果未点赞
          attri.isLiked = true;
        }
      }
      // let posts = 'posts/' + postId;
      this.appFetch({
        url: 'posts',
        splice: '/' + postId,
        method: 'patch',
        data: {
          "data": {
            "type": "posts",
            "attributes": attri,
          }
        }
      }).then((res) => {
        this.$message('修改成功');
        this.detailsLoad();
      })
    },

    //跳转到回复页
    replyToJump: function (themeId, replyId, quoteCon) {
      this.$router.push({
        path: '/reply-to-topic',
        name: 'reply-to-topic',
        params: { themeId: themeId, replyQuote: quoteCon, replyId: replyId }
      })
    },


    onRefresh() {    //下拉刷新
      this.pageIndex = 1;
      this.detailsLoad(true).then(() => {
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
  }

}
