/**
 * wap详情页控制器
 */
import appConfig from "../../../../../../../frame/config/appConfig";
// import {Bus} from '../../../store/bus.js';
// import Thread from '../../../../../common/models/Thread';
// import User from '../../../../../common/models/User';
import browserDb from '../../../../../helpers/webDbHelper';
// import Forum from '../../../../../common/models/Forum';
export default {
  data: function () {
    return {
      headBackShow: true,
      rewardShow: false,
      themeCon: false,
      themeShow: false,
      examineNum: 'qqqq',
      rewardNumList: [{
          rewardNum: '0.01'
        },
        {
          rewardNum: '2'
        },
        {
          rewardNum: '5'
        },
        {
          rewardNum: '10'
        },
        {
          rewardNum: '20'
        },
        {
          rewardNum: '50'
        },
        {
          rewardNum: '88'
        },
        {
          rewardNum: '128'
        },
        {
          rewardNum: '666'
        }
      ],
      qrcodeShow: false,
      amountNum: '',
      codeUrl: '',
      showScreen: false,
      request: false,
      isliked: '',
      likedClass: '',
      imageShow: false,
      index: 1,
      firstpostImageList: [
        // 'https://img.yzcdn.cn/2.jpg',
        // 'https://img.yzcdn.cn/2.jpg'
      ],
      isPayVal: '',
      isPaid: '',
      situation1: false,
      loginBtnFix: false,
      loginHide: false,
      siteInfo: false,
      siteUsername: '', //站长
      joinedAt: '', //加入时间
      sitePrice: '', //加入价格
      username: '', //当前用户名
      roleList: [],
      loading: false, //是否处于加载状态
      finished: false, //是否已加载完所有数据
      isLoading: false, //是否处于下拉刷新状态
      pageIndex: 1, //页码
      pageLimit: 5,
      offset: 100, //滚动条与底部距离小于 offset 时触发load事件
      groupId: '',
      menuStatus: false, //默认不显示菜单按钮
      collectStatus: false,
      collectFlag: '',
      postCount: 0 //回复总条数
    }
  },
  created() {
    this.isWeixin = appCommonH.isWeixin().isWeixin;
    this.isPhone = appCommonH.isWeixin().isPhone;
    this.getInfo();
    this.getUser();
    this.detailsLoad();
    if (!this.themeCon) {
      this.themeShow = false;
    } else {
      this.themeShow = true
    }
    if (this.collectStatus) {
      this.collectFlag = '已收藏';
    } else {
      this.collectFlag = '收藏';
    }
    // this.detailsLoad();
  },

  computed: {
    themeId: function () {
      return this.$route.params.themeId;
    }
  },
  updated () {
    //设置在pc的宽度
    if(this.isWeixin != true && this.isPhone != true){
      this.limitWidth('detailsFooter');
    }
  },

  methods: {
    //点赞和打赏数组处理（用户名之间用逗号分隔）
    userArr(data){
      let datas = [];
      data.forEach((item)=>{
        datas.push(item._data.username)
      });
      return datas.join(',')
    },
    //设置底部在pc里的宽度
    limitWidth(limitId){
      console.log(limitId);
      let viewportWidth = window.innerWidth;
      // if(limitId){
        document.getElementById(limitId).style.width = "640px";
        document.getElementById(limitId).style.marginLeft = (viewportWidth - 640)/2+'px';
      // }
      // document.getElementById('detailsFooter').style.width = "640px";
      // document.getElementById('detailsFooter').style.marginLeft = (viewportWidth - 640)/2+'px';
    },
    getInfo() {
      //请求站点信息，用于判断站点是否是付费站点
      this.appFetch({
        url: 'forum',
        method: 'get',
        data: {
          include: ['users'],
        }
      }).then((res) => {
        console.log(res);
        this.siteInfo = res.readdata;
        // console.log(res.readdata._data.siteMode+'请求');
        // this.siteUsername = res.readdata._data.siteAuthor.username;
        // this.sitePrice = res.readdata._data.sitePrice
        //把站点是否收费的值存储起来，以便于传到父页面
        this.isPayVal = res.readdata._data.siteMode;
        if (this.isPayVal != null && this.isPayVal != '') {
          this.isPayVal = res.readdata._data.siteMode;
          //   //判断站点信息是否付费，用户是否登录，用户是否已支付
          this.detailIf(this.isPayVal, false);
        }
      });
    },
    //请求用户信息
    getUser() {
      //初始化请求User信息，用于判断当前用户是否已付费
      var userId = browserDb.getLItem('tokenId');
      this.appFetch({
        url: 'users',
        method: 'get',
        splice: '/' + userId,
        data: {
          include: 'groups',
        }
      }).then((res) => {
        this.groupId = res.readdata.groups[0]._data.id;
        console.log(this.groupId);
        // this.username = res.readdata._data.username;
        // this.isPaid = res.readdata._data.paid;
        // this.roleList = res.readdata.groups;
        // if(res.readdata._data.joinedAt=='' || res.readdata._data.joinedAt == null){
        //   this.joinedAt = res.readdata._data.createdAt;
        // } else {
        //   this.joinedAt = res.readdata._data.joinedAt;
        // }
        // if(this.isPaid != null && this.isPaid != ''){
        //   this.detailIf(this.isPayVal,false);
        // }
        // this.detailIf(false,this.isPaid);
      })

    },
    detailIf(siteMode) {
      var token = browserDb.getLItem('Authorization', token);
      this.token = token;
      if (siteMode == 'public') {
        //当站点为公开站点时
        console.log('公开');

        if (token) {
          console.log('公开，已登录2222s');
          //当用户已登录时
          // this.loadThemeList();
          this.loginBtnFix = false;
          this.loginHide = true;
          this.menuStatus = true;
        } else {
          console.log('公开，未登录');
          // this.loadThemeList();
          // //当用户未登录时
          this.loginBtnFix = true;
          this.loginHide = false;
          // this.menuStatus = false;
        }
      }
    },
    footFix() {
      var scrollTop = window.pageYOffset || document.documentElement.scrollTop || document.body.scrollTop
      if (this.loginBtnFix == true) {
        this.loginHide = true;
        // console.log(scrollTop+'1111');
        if (scrollTop > 80) {
          // console.log('大于');
          this.loginHide = true;
          // console.log(this.loginHide);
        } else {
          // console.log('小于');
          this.loginHide = false;
        }
      }
    },

    //初始化请求主题详情数据
    detailsLoad() {
      let threads = 'threads/' + this.themeId;
      return this.appFetch({
        url: threads,
        method: 'get',
        data: {
          'filter[isDeleted]': 'no',
          include: ['user', 'posts', 'posts.user', 'posts.likedUsers', 'posts.images', 'firstPost', 'firstPost.likedUsers', 'firstPost.images', 'firstPost.attachments', 'rewardedUsers', 'category'],
          'page[number]': this.pageIndex,
          'page[limit]': this.pageLimit
        }
      }).then((res) => {
        if (res.error) {
          throw new Error(res.error);
        }

        console.log(res.readdata);
        console.log('1234');
        if (!this.loading) {
          this.collectStatus = res.readdata._data.isFavorite;
          this.themeShow = true;
          this.themeCon = res.readdata;
          var firstpostImageLen = this.themeCon.firstPost.images.length;
          if (firstpostImageLen === 0) return;
          var firstpostImage = [];
          for (let i = 0; i < firstpostImageLen; i++) {
            // let src = 'https://2020.comsenz-service.com/api/attachments/';
            // firstpostImage.push(this.themeCon.firstPost.images[i]._data.url);
            firstpostImage.push(this.themeCon.firstPost.images[i]._data.thumbUrl);  //缩略图
          }
          this.firstpostImageList = firstpostImage;
          console.log(134, this.firstpostImageList);
        } else {
          if(res.readdata.posts.length === 0){
            this.finished = true;
          }
          this.themeCon.posts = this.themeCon.posts.concat(res.readdata.posts);
        }
        // this.themeCon = res.readdata;
        console.log(1, this.firstpostImageList);
      }).catch((err) => {
        if (this.loading && this.pageIndex !== 1) {
          this.pageIndex--;
        }
      }).finally(()=>{
        console.log('22222222222222')
        this.loading = false;
      })
    },
    //主题详情图片放大轮播
    imageSwiper() {
      this.imageShow = true;
    },
    //主题详情图片放大轮播index值监听
    onChange(index) {
      this.index = index + 1;
    },
    //分享，复制浏览器地址
    shareTheme() {
      // var Url= appConfig.devApiUrl+'/pay-circle-con/'+this.groupId;
      var Url = 'http://10.0.10.210:8883/pay-circle-con/' + this.themeId + '/' + this.groupId;
      console.log(Url);
      var oInput = document.createElement('input');
      oInput.value = Url;
      document.body.appendChild(oInput);
      oInput.select(); // 选择对象
      document.execCommand("Copy");
      // 执行浏览器复制命令
      oInput.className = 'oInput';
      oInput.style.display = 'none';
      // alert('复制成功');
      this.$toast.success('分享链接已复制成功');
    },
    //退出登录
    signOut() {
      browserDb.removeLItem('tokenId');
      browserDb.removeLItem('Authorization');
      this.$router.push({
        path: '/login-user'
      });
    },
    //跳转到登录页
    loginJump: function () {
      this.$router.push({
        path: '/login-user'
      });
      browserDb.setLItem('themeId', this.themeId);
    },
    //跳转到注册页
    registerJump: function () {
      this.$router.push({
        path: '/sign-up'
      });
      browserDb.setLItem('themeId', this.themeId);
    },
    //点击用户名称，跳转到用户主页
    jumpPerDet:function(id){
      this.$router.push({ path:'/home-page'+'/'+id});
    },
    //付费，获得成员权限
    sitePayClick(amount) {
      this.appFetch({
        url: "orderList",
        method: "post",
        data: {
          "type": "1",
          "thread_id": this.themeId,
          "amount": amount
        },
      }).then(data => {
        // console.log(data.data.attributes.order_sn);
        const orderSn = data.data.attributes.order_sn;
        this.orderPay(orderSn, amount);

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
        this.collectStatus = !this.collectStatus
        if (this.collectStatus == true) {
          this.collectFlag = "已收藏"
        } else if (this.collectStatus == false) {
          this.collectFlag = "收藏"
        }

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
        this.$router.push({
          path: '/circle',
          name: 'circle'
        })
      } else {
        // content = content
        // console.log(content);
        //跳转到发帖页
        this.$router.push({
          path: '/edit-topic' + '/' + this.themeId
        });
      }
    },
    //主题操作接口请求
    themeOpeRequest(attri, cateId) {
      // console.log(attri);
      let threads = 'threads/' + this.themeId;
      this.appFetch({
        url: threads,
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

      })


    },
    //点赞/删除
    replyOpera(postId, type, isLike) {
      // console.log(isLike);
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
      // console.log(attri);
      let posts = 'posts/' + postId;
      this.appFetch({
        url: posts,
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
      if(!this.token){
        this.$router.push({
          path:'/login-user',
          name:'login-user'
        })
      } else {
        this.rewardShow = true;
        if(this.isWeixin != true && this.isPhone != true && this.rewardShow){
          // this.limitWidth('rewardPopup');
        }
      }
    },
    //跳转到回复页
    replyToJump: function (themeId, replyId, quoteCon) {
      this.$router.push({
        path: '/reply-to-topic',
        name: 'reply-to-topic',
        params: {
          themeId: themeId,
          replyQuote: quoteCon,
          replyId: replyId
        }
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
        // console.log(data.data.attributes.order_sn);
        const orderSn = data.data.attributes.order_sn;
        this.orderPay(orderSn, amount);

      })
    },

    //打赏，生成订单成功后支付
    orderPay(orderSn, amount) {
      // console.log(amount+'101010');
      let isWeixin = this.appCommonH.isWeixin().isWeixin;
      let isPhone = this.appCommonH.isWeixin().isPhone;
      // console.log(isWeixin+'1111')
      // console.log(isPhone+'2222')
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
        //   console.log(data.data.attributes.location)
        //   window.location.href = data.data.attributes.location;
        // });
        payment_type = "12";
      } else if (isPhone == true) {
        //手机浏览器登录时
        // console.log('手机浏览器登录');
        payment_type = "11";
      } else {
        payment_type = "10";
        // console.log('pc登录');
      }
      let orderPay = 'trade/pay/order/' + orderSn;
      this.appFetch({
        url: orderPay,
        method: "post",
        data: {
          'payment_type': payment_type
        },
      }).then(data => {
        // console.log(data);
        if (isWeixin) {
          //如果是微信支付
          // console.log(data.data.attributes.wechat_js);
        } else if (isPhone) {
          //如果是h5支付
          // console.log(data.data.attributes.wechat_h5_link);
          window.location.href = data.data.attributes.wechat_h5_link;
        } else {
          // console.log('pc');
          //如果是pc支付
          // console.log(data.data.attributes.wechat_qrcode);
          this.qrcodeShow = true;
          // console.log(this.qrcodeShow);
          this.amountNum = amount;
          // console.log(this.amountNum);
          this.codeUrl = data.data.attributes.wechat_qrcode;
        }

      })
    },
    onLoad() { //上拉加载
      this.loading = true;
      this.finished = false;
      this.pageIndex++;
      // console.log(123)
      this.detailsLoad();
    },
    onRefresh() { //下拉刷新
      this.pageIndex = 1;
      this.finished = false;
      this.themeCon.posts = [];
      this.detailsLoad().then(()=>{
        this.$toast('刷新成功');
      }).catch((err)=>{
        this.$toast('刷新失败');
      })
    }


  },

  mounted: function () {},

  beforeRouteLeave(to, from, next) {
    next()
  }

}
