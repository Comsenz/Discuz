/**
 * wap详情页控制器
 */
import appConfig from "../../../../../../../frame/config/appConfig";
// import {Bus} from '../../../store/bus.js';
// import Thread from '../../../../../common/models/Thread';
// import User from '../../../../../common/models/User';
import browserDb from '../../../../../helpers/webDbHelper';
// import Forum from '../../../../../common/models/Forum';
import appCommonH from '../../../../../helpers/commonHelper';
import {ImagePreview} from "vant";
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
      siteMode:'',
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
      pageLimit: 20,
      offset: 100, //滚动条与底部距离小于 offset 时触发load事件
      groupId: '',
      menuStatus: false, //默认不显示菜单按钮
      collectStatus: false,
      collectFlag: '',
      postCount: 0, //回复总条数
      postsList:'',
      likedUsers:[],
      rewardedUsers:[],
      token:false,
      isWeixin: false,
      isPhone: false,
      isAndroid: false,
      isiOS: false,
      orderSn:'',
      payStatus: false,   //支付状态
      payStatusNum: 0,//支付状态次数
      canViewPosts:'',
      canLike:'',
      canReply:'',
      themeUserId:'',
      userId:'',
      currentUserName:'',
      currentUserAvatarUrl: '',
      likedData: [],
      postsImages: [],
      allowRegister: '',
      loginWord:'登录 / 注册',
      viewportWidth: '',
    }
  },
  created() {
    this.viewportWidth = window.innerWidth;
    var u = navigator.userAgent;
    this.isAndroid = u.indexOf('Android') > -1 || u.indexOf('Adr') > -1; //android终端
    this.isiOS = !!u.match(/\(i[^;]+;( U;)? CPU.+Mac OS X/); //ios终端
    // alert('是否是Android：'+isAndroid);
    // alert('是否是iOS：'+isiOS);
    this.isWeixin = appCommonH.isWeixin().isWeixin;
    this.isPhone = appCommonH.isWeixin().isPhone;
    this.getInfo();
    this.userId = browserDb.getLItem('tokenId');
    this.getUser();
    this.detailsLoad(true);
    if (!this.themeCon) {
      this.themeShow = false;
    } else {
      this.themeShow = true
    }

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
    //判断设备，下载时提示
    downAttachment(url){
      if(this.isiOS){
        this.$message('因iphone系统限制，您的手机无法下载文件。请使用安卓手机或电脑访问下载');
      }
    },
    //点赞和打赏数组处理（用户名之间用逗号分隔）
    userArr(data){
      let datas = [];
      data.forEach((item)=>{
        datas.push('<a  href="/home-page/'+item._data.id+'">'+ item._data.username + '</a>');
      });
      // this.likedData = datas.join(',');
      // console.log(this.likedData);
      return datas.join(',');

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
        if (res.errors){
          this.$toast.fail(res.errors[0].code);
          throw new Error(res.error)
        } else {
           // console.log(res);
           this.siteInfo = res.readdata;
           //把站点是否收费的值存储起来，以便于传到父页面
           this.isPayVal = res.readdata._data.siteMode;
           this.allowRegister = res.readdata._data.allowRegister;
           if(!this.allowRegister){
             this.loginWord = '登录';
           }
           if (this.isPayVal != null && this.isPayVal != '') {
             this.isPayVal = res.readdata._data.siteMode;
             //   //判断站点信息是否付费，用户是否登录，用户是否已支付
             this.detailIf(this.isPayVal, false);
           }
         }
      });
    },
    //请求用户信息
    getUser() {
      //初始化请求User信息，用于判断当前用户是否已付费
      var userId = browserDb.getLItem('tokenId');
      this.userId = userId;
      console.log(this.userId+'ddddd');
      if(this.userId != '' && this.userId != null){
        this.appFetch({
          url: 'users',
          method: 'get',
          splice: '/' + userId,
          data: {
            include: 'groups',
          }
        }).then((res) => {
          if (res.errors){
            this.$toast.fail(res.errors[0].code);
            throw new Error(res.error)
          } else {
            this.currentUserName = res.readdata._data.username;
            this.currentUserAvatarUrl = res.readdata._data.avatarUrl;
            // console.log(this.currentUserAvatarUrl+'3334');
            this.groupId = res.readdata.groups[0]._data.id;
            // console.log(this.groupId,'uuuuu');
           }

        })
      }


    },
    detailIf(siteMode) {
      var token = browserDb.getLItem('Authorization');
      this.token = token;
      if (siteMode == 'public') {
        //当站点为公开站点时
        console.log('公开');
        if (token) {
          //当用户已登录时
          this.loginBtnFix = false;
          this.loginHide = true;
          this.menuStatus = true;
        } else {
          // console.log('公开，未登录');
          // //当用户未登录时
          this.loginBtnFix = true;
          this.loginHide = false;
        }
      }
    },
    //登录注册按钮悬浮时隐藏以及显示效果
    footFix() {
      var scrollTop = window.pageYOffset || document.documentElement.scrollTop || document.body.scrollTop
      if (this.loginBtnFix == true) {
        this.loginHide = true;
        if (scrollTop > 80) {
          this.loginHide = true;
        } else {
          this.loginHide = false;
        }
      }
    },

    //初始化请求主题详情数据
    detailsLoad(initFlag = false) {
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
        if (res.errors){
          this.$toast.fail(res.errors[0].code);
          throw new Error(res.error)
        }else{
          console.log(res.readdata);
          console.log('1234');
          this.finished = res.readdata.posts.length < this.pageLimit;
          if (initFlag) {
            this.collectStatus = res.readdata._data.isFavorite;
            this.essenceStatus = res.readdata._data.isEssence;
            this.stickyStatus = res.readdata._data.isSticky;
            if (this.collectStatus) {
              this.collectFlag = '已收藏';
            } else {
              this.collectFlag = '收藏';
            }
            if (this.essenceStatus) {
              this.essenceFlag = '取消加精';
            } else {
              this.essenceFlag = '加精';
            }
            if (this.stickyStatus) {
              this.stickyFlag = '取消置顶';
            } else {
              this.stickyFlag = '置顶';
            }
            this.themeShow = true;
            this.themeCon = res.readdata;
            this.canLike = res.readdata.firstPost._data.canLike;
            this.canViewPosts = res.readdata._data.canViewPosts;
            this.canReply = res.readdata._data.canReply;
            this.postsList = res.readdata.posts;
            this.likedUsers = res.readdata.firstPost.likedUsers;
            this.rewardedUsers = res.readdata.rewardedUsers;
            this.themeUserId = res.readdata.user._data.id;
            var firstpostImageLen = this.themeCon.firstPost.images.length;
            if (firstpostImageLen === 0) {
              return;
            } else {
              var firstpostImage = [];
              for (let i = 0; i < firstpostImageLen; i++) {
                // let src = 'https://2020.comsenz-service.com/api/attachments/';
                // firstpostImage.push(this.themeCon.firstPost.images[i]._data.url);
                firstpostImage.push(this.themeCon.firstPost.images[i]._data.thumbUrl);  //缩略图
              }
              this.firstpostImageList = firstpostImage;
            };

            this.postsList.map(post => {
              let urls = [];
              post.images.map(image => urls.push(image._data.url));
              this.postsImages.push(urls);
            });
            // console.log(this.postsImages);
          } else {
            this.themeCon.posts = this.themeCon.posts.concat(res.readdata.posts);
          }
        }

      }).catch((err) => {
        if (this.loading && this.pageIndex !== 1) {
          this.pageIndex--;
        }
      }).finally(()=>{
        this.loading = false;
      })

    },
    //主题详情图片放大轮播
    imageSwiper(imgIndex, typeclick, replyItem) {
      console.log(imgIndex, typeclick, replyItem);
      // console.log(this.firstpostImageList);
      // console.log(imgIndex);
      if(typeclick == 'detailImg'){
        //主题详情图片预览
        ImagePreview({
          images:this.firstpostImageList,
          startPosition:imgIndex,    //图片预览起始位置索引 默认 0
          showIndex: true,    //是否显示页码         默认 true
          showIndicators: true, //是否显示轮播指示器 默认 false
          loop:true,            //是否开启循环播放  貌似循环播放是不起作用的。。。
          // onClose:function (url) {  //回调参数,官方文档解释的不是很清楚。。。
          //   //回调参数类型 url:{ index:Number(当前图片的索引值), url:当前图片的URL }
          //   var num = url.index, url_link = url.url;
          //   console.log(url);
          // }
        })
      } else if(typeclick == 'replyImg') {
        // console.log(this.postsImages[replyItem]);
        // console.log('-------------------');
        //主题回复图片预览
        ImagePreview({
          images:this.postsImages[replyItem],
          startPosition:imgIndex,    //图片预览起始位置索引 默认 0
          showIndex: true,    //是否显示页码         默认 true
          showIndicators: true, //是否显示轮播指示器 默认 false
          loop:true,            //是否开启循环播放  貌似循环播放是不起作用的。。。
        })
      }

    },
    onChangeImgPreview() {
      this.index = index;
    },
    //主题详情图片放大轮播index值监听
    // onChange(index) {
    //   this.index = index + 1;
    // },
    //分享，复制浏览器地址
    shareTheme() {
      let Url= '';
      if(this.isPayVal === 'pay'){
        Url = appConfig.devApiUrl+'/pay-circle-con/'+ this.themeId + '/' + this.groupId;
      } else {
        Url = appConfig.devApiUrl+'/details/' + this.themeId
      }
      // var Url= appConfig.devApiUrl+'/pay-circle-con/'+ this.themeId + '/' + this.groupId;
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
      if(!this.token){
        this.$router.push({
          path:'/login-user',
          name:'login-user'
        })
      } else {
        this.$router.push({ path:'/home-page'+'/'+id});
      }
    },

    //主题管理
    bindScreen: function () {
      //是否显示筛选内容
      this.showScreen = !this.showScreen;
    },
    listenEvt(e){
      if(!this.$refs.screenBox.contains(e.target)){
        this.showScreen = false;
      }
    },
    //管理操作
    themeOpera(postsId, clickType, cateId, content) {
      if(!this.token){
        this.$router.push({
          path:'/login-user',
          name:'login-user'
        })
      } else {
        let attri = new Object();
        if (clickType == 1) {
          if (this.collectStatus) {
            attri.isFavorite = false;
          } else {
            attri.isFavorite = true;
          }
          content = '';
          this.themeOpeRequest(attri, cateId, '1');
        } else if (clickType == 2) {
          content = '';
          if (this.essenceStatus) {
            attri.isEssence = false;
          } else {
            attri.isEssence = true;
          }
          this.themeOpeRequest(attri, cateId, '2');
        } else if (clickType == 3) {
          content = '';
          if (this.stickyStatus) {
            attri.isSticky = false;
          } else {
            attri.isSticky = true;
          }
          this.themeOpeRequest(attri, cateId, '3');
        } else if (clickType == 4) {
          attri.isDeleted = true;
          content = '';
          this.themeOpeRequest(attri, cateId, '4');
        } else {
          // content = content
          // console.log(content);
          //跳转到发帖页
          this.$router.push({
            path: '/edit-topic' + '/' + this.themeId
          });
        }
      }
    },
    //主题操作接口请求
    themeOpeRequest(attri, cateId, clickType) {
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
        console.log(res);
        if (res.errors){
          this.$toast.fail(res.errors[0].code);
          throw new Error(res.error)
        }else{
          if(clickType == '1'){
            this.collectStatus = res.readdata._data.isFavorite;
            if (this.collectStatus) {
              this.collectFlag = "已收藏";
            } else {
              this.collectFlag = "收藏";
            }
          } else if(clickType == '2'){
            this.essenceStatus = res.readdata._data.isEssence;
            if (this.essenceStatus) {
              this.essenceFlag = "取消加精";
            } else {
              this.essenceFlag = "加精";
            }
          } else if(clickType == '3'){
            this.stickyStatus = res.readdata._data.isSticky;
            if (this.stickyStatus) {
              this.stickyFlag = "取消置顶";
            } else {
              this.stickyFlag = "置顶";
            }
          } else if(clickType == '4'){
            //删除
            this.deletedStatus = res.readdata._data.isDeleted;
            if (this.deletedStatus) {
              this.$toast.success('删除成功，跳转到首页');
              this.$router.push({
                path: '/circle',
                name: 'circle'
              })
            }
          }

        }
      })
    },

    //删除请求接口
    deleteOpear(postId,postIndex){
      console.log(postIndex);
      let attri = new Object();
      attri.isDeleted = true;
      this.appFetch({
        url: 'posts',
        splice:'/'+ postId,
        method: 'patch',
        data: {
          "data": {
            "type": "posts",
            "attributes": attri,
          }
        }
      }).then((res) => {
        if (res.errors){
          this.$toast.fail(res.errors[0].code);
          throw new Error(res.error)
        } else {
          this.$toast.success('删除成功');
          this.pageIndex = 1;
          console.log(this.postsList);
          this.postsList.splice(postIndex,1);
          console.log(this.postsList);
          // this.detailsLoad(true);
        }
      })
    },


    //回复点赞
    replyOpera(postId, type, isLike,postsCanLike,postIndex) {
      console.log(postId, type, isLike,postsCanLike);
      // console.log(this.token);
      if(!this.token){
        this.$router.push({
          path:'/login-user',
          name:'login-user'
        })
      } else {
        // console.log(isLike);
        let attri = new Object();
        if (type == 2) {
          console.log(postsCanLike);
          if(!postsCanLike){
            this.$toast.fail('没有权限，请联系站点管理员');
            return false;
          } else {
            if (isLike) {
              //如果已点赞
              attri.isLiked = false;
            } else {
              //如果未点赞
              attri.isLiked = true;
            }
          }
        }
        // else if (type == 3) {
        //   if(!this.canLike){
        //     this.$toast.fail('没有权限，请联系站点管理员');
        //     return false;
        //   } else {
        //     if (isLike) {
        //       //如果已点赞
        //       attri.isLiked = false;
        //     } else {
        //       //如果未点赞
        //       attri.isLiked = true;
        //     }
        //   }
        // }
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
          if (res.errors){
            this.$toast.fail(res.errors[0].code);
            throw new Error(res.error)
          } else {
            // this.$toast.success('修改成功');
            if(isLike){
              this.postsList[postIndex]._data.likeCount = this.postsList[postIndex]._data.likeCount - 1;
              this.postsList[postIndex]._data.isLiked = false;
            } else {
              this.postsList[postIndex]._data.likeCount = this.postsList[postIndex]._data.likeCount + 1;
              this.postsList[postIndex]._data.isLiked = true;
            }
            this.pageIndex = 1;
            // this.detailsLoad(true);
          }
        })
      }
    },

    //主题点赞
      footReplyOpera(postId, type, isLike,postsCanLike,postIndex) {
        console.log(postId, type, isLike,postsCanLike);
        // console.log(this.token);
        if(!this.token){
          this.$router.push({
            path:'/login-user',
            name:'login-user'
          })
        } else {
          // console.log(isLike);
          let attri = new Object();
          // if (type == 2) {
          //   console.log(postsCanLike);
          //   if(!postsCanLike){
          //     this.$toast.fail('没有权限，请联系站点管理员');
          //     return false;
          //   } else {
          //     if (isLike) {
          //       //如果已点赞
          //       attri.isLiked = false;
          //     } else {
          //       //如果未点赞
          //       attri.isLiked = true;
          //     }
          //   }
          // } else
          if (type == 3) {
            if(!this.canLike){
              this.$toast.fail('没有权限，请联系站点管理员');
              return false;
            } else {
              if (isLike) {
                //如果已点赞
                attri.isLiked = false;
              } else {
                //如果未点赞
                attri.isLiked = true;
              }
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
            if (res.errors){
              this.$toast.fail(res.errors[0].code);
              throw new Error(res.error)
            } else {
              if(isLike){
                // console.log('已点赞时，点击取消点赞');
                // this.likedUsers = this.likedUsers.filter(value => value._data.id !== this.userId);
                this.likedUsers.map((value, key, likedUsers) => {
                  value._data.id === this.userId && likedUsers.splice(key,1);
                });
                // for(var i = 0; i < this.likedUsers.length; i++){
                //   console.log('循环');
                //   if(this.likedUsers[i]._data.id === this.userId){
                //     console.log(this.likedUsers[i]._data.id);
                //       this.likedUsers.splice(i,1);
                //       console.log(this.likedUsers);
                //       console.log('123');
                //   }
                // }
                this.userArr(this.likedUsers);
                this.themeCon.firstPost._data.isLiked = false;
              } else {
                // console.log('未点赞时，点击点赞');
                this.likedUsers.push({_data:{username:this.currentUserName,id:this.userId}});
                this.themeCon.firstPost._data.isLiked = true;
                console.log(this.themeCon.firstPost._data.isLiked);
              }
              this.pageIndex = 1;
              // this.detailsLoad(true);
            }
          })
        }
      },



    //打赏
    showRewardPopup: function () {
      if(!this.token){
        this.$router.push({
          path:'/login-user',
          name:'login-user'
        })
      } else {
        console.log(this.userId);
        console.log(this.themeUserId);
        if(this.userId == this.themeUserId) {
          this.$toast.fail('不能打赏自己');
        } else {
          this.rewardShow = true;
          if(this.isWeixin != true && this.isPhone != true && this.rewardShow){
            // this.limitWidth('rewardPopup');
          }
        }

      }
    },
    //跳转到回复页
    replyToJump: function (themeId, replyId, quoteCon) {
      console.log(themeId, replyId, quoteCon);
      if(!this.token){
        this.$router.push({
          path:'/login-user',
          name:'login-user'
        })
      } else if(!this.canReply){
        this.$toast.fail('没有权限，请联系站点管理员');
      } else {
        this.$router.push({
          path:'/reply-to-topic'+'/'+themeId+'/'+replyId,
        });
        browserDb.setLItem('replyQuote', quoteCon);
      }
    },


     onBridgeReady(data){
       let that = this;
       WeixinJSBridge.invoke(
         'getBrandWCPayRequest', {
           "appId":data.data.attributes.wechat_js.appId,     //公众号名称，由商户传入
           "timeStamp":data.data.attributes.wechat_js.timeStamp,         //时间戳，自1970年以来的秒数
           "nonceStr":data.data.attributes.wechat_js.nonceStr, //随机串
           "package":data.data.attributes.wechat_js.package,
           "signType":"MD5",         //微信签名方式：
           "paySign":data.data.attributes.wechat_js.paySign //微信签名
         })

        const payWechat = setInterval(()=>{
          if (this.payStatus == '1' || this.payStatusNum > 10){
            clearInterval(payWechat);
          }
          this.getOrderStatus();
        },3000)

     },



    payClick(amount){
      // alert(amount);
      let isWeixin = this.appCommonH.isWeixin().isWeixin;
      let isPhone = this.appCommonH.isWeixin().isPhone;
      this.amountNum = amount;
      if (isWeixin){

        this.getOrderSn(amount).then(()=>{
          this.orderPay(12).then((res)=>{
            if (typeof WeixinJSBridge == "undefined"){
              if( document.addEventListener ){
                document.addEventListener('WeixinJSBridgeReady', this.onBridgeReady(res), false);
              }else if (document.attachEvent){
                document.attachEvent('WeixinJSBridgeReady', this.onBridgeReady(res));
                document.attachEvent('onWeixinJSBridgeReady', this.onBridgeReady(res));
              }
            }else{
              this.onBridgeReady(res);
            }
          })
        });
      } else if (isPhone){
        console.log('手机浏览器');
        this.getOrderSn(amount).then(()=>{
          this.orderPay(11).then((res)=>{
            this.wxPayHref = res.readdata._data.wechat_h5_link;
            window.location.href = this.wxPayHref;
            const payPhone = setInterval(()=>{
              if (this.payStatus == '1' || this.payStatusNum > 10){
                clearInterval(payPhone);
              }
              this.getOrderStatus();
            },3000)
          })
        });
      } else {
        console.log('pc');
        this.getOrderSn(amount).then(()=>{
          this.orderPay(10).then((res)=>{
            console.log(res);
            this.codeUrl = res.readdata._data.wechat_qrcode;
            this.qrcodeShow = true;
            const pay = setInterval(()=>{
              console.log(this.payStatusNum);
              this.getOrderStatus();
              if (this.payStatus == '1' || this.payStatusNum > 10){
                console.log('已达上限');
                clearInterval(pay);
              }
            },3000);


          })
        });
      }
    },

    getOrderSn(amount){
      return this.appFetch({
        url:'orderList',
        method:'post',
        data:{
          "type": 2,
          "thread_id": this.themeId,
          "amount": amount
        }
      }).then(res=>{
        console.log(res);
        this.orderSn = res.readdata._data.order_sn;
      })
    },

    orderPay(type){
      return this.appFetch({
        url:'orderPay',
        method:'post',
        splice:'/' + this.orderSn,
        data:{
          "payment_type":type
        }
      }).then(res=>{
        console.log(res);
        return res;
      }).catch(err=>{
        console.log(err);
      })
    },
    getOrderStatus(){
      // alert(this.orderSn);
      return this.appFetch({
        url:'order',
        method:'get',
        splice:'/' + this.orderSn,
        data:{
        },
      }).then(res=>{
        console.log(res);
        // const orderStatus = res.readdata._data.status;
        if (res.errors){
          this.$toast.fail(res.errors[0].code);
          throw new Error(res.error)
        } else {
          this.payStatus = res.readdata._data.status;
          console.log(res.readdata._data.status);
          this.payStatusNum ++;
          // console.log(this.payStatusNum);
          if (this.payStatus == '1' || this.payStatusNum > 10){
            this.rewardShow = false;
            this.qrcodeShow = false;
            this.rewardedUsers.push({_data:{avatarUrl:this.currentUserAvatarUrl,id:this.userId}});
            console.log(this.rewardedUsers);
            this.payStatusNum = 11;
            // this.detailsLoad(true);
            console.log('重新请求');
            clearInterval(pay);
          }
        }

        // return res;
      })
    },


    onLoad() { //上拉加载
      this.loading = true;
      this.pageIndex++;
      // console.log(123)
      this.detailsLoad();
    },
    onRefresh() { //下拉刷新
      this.pageIndex = 1;
      this.detailsLoad(true).then(()=>{
        this.$toast('刷新成功');
      }).catch((err)=>{
        this.$toast('刷新失败');
      })
    }


  },
  mounted: function() {
    // this.getVote();
    document.addEventListener('click',this.listenEvt, false);
  },
  destroyed: function() {
    document.removeEventListener('click',this.listenEvt, false);
  },
  beforeRouteLeave(to, from, next) {
    document.removeEventListener('click',this.listenEvt, false);
    next()
  }

}
