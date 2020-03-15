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
      examineNum: 'qqqq',
      rewardNumList: [
        { rewardNum: '0.01' },
        { rewardNum: '2' },
        { rewardNum: '5' },
        { rewardNum: '10' },
        { rewardNum: '20' },
        { rewardNum: '50' },
        { rewardNum: '88' },
        { rewardNum: '128' },
        { rewardNum: '666' }
      ],
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
    // detailsLoad(){
    //   const params = {
    //     'filter[isDeleted]':'no'
    //   };
    //   params.include = 'user,posts,posts.user,posts.likedUsers,firstPost,rewardedUsers,category';
    //   let threads= 'threads/'+this.themeId;
    //   this.apiStore.find(threads, params).then(data => {
    //     this.themeCon = data;
    //     this.themeShow = true;
    //   });
    // },
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
    //打赏
    showRewardPopup: function () {
      this.rewardShow = true;
    },
    //跳转到回复页
    replyToJump: function (themeId, replyId, quoteCon) {
      this.$router.push({
        path: '/reply-to-topic',
        name: 'reply-to-topic',
        params: { themeId: themeId, replyQuote: quoteCon, replyId: replyId }
      })
    },
    //打赏 生成订单
    rewardPay(amount) {
      this.appFetch({
        url: "orderList",
        method: "post",
        data: {
          "type": "2",
          "thread_id": this.themeId,
          "amount": amount
        },
      }).then(data => {
        const orderSn = data.data.attributes.order_sn;
        this.orderPay(orderSn, amount);

      })
    },

    //打赏，生成订单成功后支付
    orderPay(orderSn, amount) {
      let isWeixin = this.appCommonH.isWeixin().isWeixin;
      let isPhone = this.appCommonH.isWeixin().isPhone;
      let payment_type = '';
      if (isWeixin == true) {
        //微信登录时
        alert('微信支付');
        // this.appFetch({
        //   url:"weixin",
        //   method:"get",
        //   data:{
        //   }
        // }).then(data=>{
        //   window.location.href = data.data.attributes.location;
        // });
        payment_type = "12";
      } else if (isPhone == true) {
        //手机浏览器登录时
        payment_type = "11";
      } else {
        //pc登录
        payment_type = "10";

      }
      let orderPay = 'trade/pay/order/' + orderSn;
      this.appFetch({
        url: orderPay,
        method: "post",
        data: {
          'payment_type': payment_type
        },
      }).then(data => {
        if (isWeixin) {
          //如果是微信支付
        } else if (isPhone) {
          //如果是h5支付
          window.location.href = data.data.attributes.wechat_h5_link;
        } else {
          //如果是pc支付
          this.qrcodeShow = true;
          this.amountNum = amount;
          this.codeUrl = data.data.attributes.wechat_qrcode;
        }

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
