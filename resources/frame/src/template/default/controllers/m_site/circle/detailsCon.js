/**
 * wap详情页控制器
 */
import appConfig from "../../../../../../../frame/config/appConfig";
import browserDb from "../../../../../helpers/webDbHelper";
import appCommonH from "../../../../../helpers/commonHelper";
// import appConfig from '../../../../../../config/appConfig';
import { ImagePreview } from "vant";
import { wxShare, noShare } from "../../../viewConfig/tplConfig";
export default {
  data: function() {
    return {
      headBackShow: true,
      rewardShow: false,
      themeCon: false,
      themeShow: false,
      examineNum: "qqqq",
      rewardNumList: [
        {
          rewardNum: "0.1"
        },
        {
          rewardNum: "2"
        },
        {
          rewardNum: "5"
        },
        {
          rewardNum: "10"
        },
        {
          rewardNum: "20"
        },
        {
          rewardNum: "50"
        },
        {
          rewardNum: "88"
        },
        {
          rewardNum: "128"
        },
        {
          rewardNum: "666"
        }
      ],
      amountNum: "",
      showScreen: false,
      request: false,
      isliked: "",
      likedClass: "",
      imageShow: false,
      index: 1,
      firstpostImageList: [], //主题详情页图片（缩略图）
      firstpostImageListOriginal: [], //主题详情页图片（原图）
      siteMode: "",
      isPaid: "",
      situation1: false,
      loginBtnFix: false,
      loginHide: false,
      siteInfo: false,
      siteUsername: "", //站长
      joinedAt: "", //加入时间
      sitePrice: "", //加入价格
      username: "", //当前用户名
      roleList: [],
      loading: false, //是否处于加载状态
      finished: false, //是否已加载完所有数据
      isLoading: false, //是否处于下拉刷新状态
      pageIndex: 1, //页码
      pageLimit: 20,
      offset: 100, //滚动条与底部距离小于 offset 时触发load事件
      groupId: "",
      menuStatus: false, //默认不显示菜单按钮
      collectStatus: false,
      collectFlag: "",
      postCount: 0, //回复总条数
      postsList: "",
      likedUsers: [],
      rewardedUsers: [],
      token: false,
      isWeixin: false,
      isPhone: false,
      isAndroid: false,
      isiOS: false,
      orderSn: "",
      payStatus: false, //支付状态
      payStatusNum: 0, //支付状态次数
      canViewPosts: "",
      canLike: "",
      canReply: "",
      themeUserId: "",
      userId: "",
      currentUserName: "",
      currentUserAvatarUrl: "",
      likedData: [],
      postsImages: [],
      allowRegister: "",
      loginWord: "登录 / 注册",
      viewportWidth: "",
      themeIsLiked: "",
      themeTitle: "",
      wxpay: "",
      twoChi: "",
      show: false, //是否显示支付方式
      payList: [
        {
          name: "钱包",
          icon: "icon-wallet"
        }
      ],
      qrcodeShow: false,
      walletBalance: "", //钱包余额
      errorInfo: "", //密码错误提示
      value: "", //密码
      // pwdVal: '',
      codeUrl: "", //支付url，base64
      type: false,
      userDet: "",
      hideStyle: "",
      likeTipShow: true,
      likeTipFlag: "展开",
      likeLen: "",
      limitLen: 7,
      rewardTipFlag: "展开",
      userArrStatus: false,
      rewardTipShow: true,
      payLoading: false,
      clickStatus: true,
      contentExamine: false, //内容审核提示
      examineWord: "", //内容审核提示文字
      ExamineStatus: "", //审核中状态
      logo: "", //站点logo
      wxShareTip: false,
      siteName: "" //站点名称
    };
  },
  created() {
    this.viewportWidth = window.innerWidth;
    var u = navigator.userAgent;
    this.isAndroid = u.indexOf("Android") > -1 || u.indexOf("Adr") > -1; //android终端
    this.isiOS = !!u.match(/\(i[^;]+;( U;)? CPU.+Mac OS X/); //ios终端
    this.isWeixin = appCommonH.isWeixin().isWeixin;
    this.isPhone = appCommonH.isWeixin().isPhone;
    this.siteName = browserDb.getLItem("siteInfo")._data.set_site.site_name;
    this.getInfo();
    // this.onLoad();
    this.userId = browserDb.getLItem("tokenId");
    this.token = browserDb.getLItem("Authorization");
    // this.shareTheme();
    this.getUser();
    this.detailsLoad(true); //初始化详情页列表数据
    window.likeIsFold = this.likeIsFold;
    if (!this.themeCon) {
      this.themeShow = false;
    } else {
      this.themeShow = true;
    }

    if (browserDb.getSItem("beforeState") === 1) {
      this.$router.go(0);
      browserDb.setSItem("beforeState", 2);
    }
  },

  computed: {
    themeId: function() {
      return this.$route.params.themeId;
    }
  },
  updated() {
    //设置在pc的宽度
    if (this.isWeixin != true && this.isPhone != true) {
      this.limitWidth("detailsFooter");
    }
  },

  methods: {
    //判断设备，下载时提示
    downAttachment(url) {
      if (this.isiOS) {
        this.$toast('因iPhone系统限制，您的手机无法下载文件。请使用安卓手机或电脑访问下载');
      }
    },
    //点赞和打赏数组处理（用户名之间用逗号分隔）
    userArr(data, hideStatus) {
      let datas = [];
      // if(hideStatus){
      //   this.hideStyle = '';
      // } else {
      //   this.hideStyle = 'display:none';
      // }
      data = data.slice(0, 10);
      data.forEach((item, key) => {
        // datas.push('<a  href="/pages/profile/index?userId='+item._data.id+'" style="'+(key>10?this.hideStyle:'')+'">'+ item._data.username  +'</a>');
        datas.push(
          '<a  href="/pages/profile/index?userId=' +
            item._data.id +
            '">' +
            item._data.username +
            "</a>"
        );
      });
      // return datas;
      datas = datas.join("，");
      if (this.likeLen > 10) {
        datas = datas + "等" + this.likeLen + "人觉得很赞";
        // datas+="<span class='foldTip'>等"+this.likeLen+"人觉得很赞</span>";
        // datas+="<span onclick='likeIsFold(event)' class='foldTag'>"+ this.likeTipFlag+"<i class='icon iconfont icon-down-menu' :class='{'rotate180':likeTipShow}'></i></span>";
      }
      return datas;
    },
    likeIsFold() {
      this.likeTipShow = !this.likeTipShow;
      this.likeTipFlag = this.likeTipShow ? "展开" : "收起";
      this.hideStyle = this.likeTipShow ? "" : "display:none";
      document.getElementById("likedUserList").innerHTML = this.userArr(
        this.themeCon.firstPost.likedUsers,
        true
      );
    },
    rewardIsFold(allLen) {
      this.rewardTipShow = !this.rewardTipShow;
      this.rewardTipFlag = this.rewardTipShow ? "展开" : "收起";
      this.limitLen = this.rewardTipShow ? 5 : allLen;
    },
    //设置底部在pc里的宽度
    limitWidth(limitId) {
      let viewportWidth = window.innerWidth;
      let elem = document.getElementById(limitId);
      if (elem) {
        elem.style.width = "640px";
        elem.style.marginLeft = (viewportWidth - 640) / 2 + "px";
      }
    },
    getInfo() {
      //请求站点信息，用于判断站点是否是付费站点
      this.$store.dispatch("appSiteModule/loadForum").then(res => {
        if (res.errors) {
          this.$toast.fail(res.errors[0].code);
          throw new Error(res.error);
        } else {
          this.siteInfo = res.readdata;
          // this.siteName = this.siteInfo._data.set_site.site_name;
          this.logo = res.readdata._data.set_site.site_logo;
          this.wxpay = res.readdata._data.paycenter.wxpay_close;
          if (this.wxpay == "0" || this.wxpay == false) {
            this.twoChi = true;
          }
          //把站点是否收费的值存储起来，以便于传到父页面
          this.isPayVal = res.readdata._data.set_site.site_mode;
          this.allowRegister = res.readdata._data.set_reg.register_close;
          if (!this.allowRegister) {
            this.loginWord = "登录";
          }
          if (this.isPayVal != null && this.isPayVal != "") {
            this.isPayVal = res.readdata._data.set_site.site_mode;
            //   //判断站点信息是否付费，用户是否登录，用户是否已支付
            this.detailIf(this.isPayVal, false);
          }
          if (res.readdata._data.paycenter.wxpay_close == true) {
            this.payList.unshift({
              name: "微信支付",
              icon: "icon-wxpay"
            });
          }
        }
      });
    },
    //请求用户信息
    getUser() {
      //初始化请求User信息，用于判断当前用户是否已付费
      this.$store
        .dispatch("appSiteModule/loadUser")
        .then(res => {
          if (res.errors) {
            this.$toast.fail(res.errors[0].code);
            throw new Error(res.error);
          } else {
            this.userDet = res.readdata;
            this.currentUserName = res.readdata._data.username;
            this.currentUserAvatarUrl = res.readdata._data.avatarUrl;
            this.walletBalance = res.readdata._data.walletBalance;
            this.groupId = res.readdata.groups[0]._data.id;
          }
        })
        .catch(() => {});
    },
    detailIf(siteMode) {
      var token = browserDb.getLItem("Authorization");
      this.token = token;
      if (siteMode == "public") {
        //当站点为公开站点时
        if (token) {
          //当用户已登录时
          this.loginBtnFix = false;
          this.loginHide = true;
          this.menuStatus = true;
        } else {
          // //当用户未登录时
          this.loginBtnFix = true;
          this.loginHide = false;
        }
      }
    },
    //登录注册按钮悬浮时隐藏以及显示效果
    footFix() {
      var scrollTop =
        window.pageYOffset ||
        document.documentElement.scrollTop ||
        document.body.scrollTop;
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
      // this.loading = true;
      return this.appFetch({
        url: "threads",
        splice: "/" + this.themeId,
        method: "get",
        data: {
          "filter[isDeleted]": "no",
          include: [
            "posts.replyUser",
            "user.groups",
            "user",
            "posts",
            "posts.user",
            "posts.likedUsers",
            "posts.images",
            "firstPost",
            "firstPost.likedUsers",
            "firstPost.images",
            "firstPost.attachments",
            "rewardedUsers",
            "category",
            "threadVideo"
          ],
          "page[number]": this.pageIndex,
          "page[limit]": this.pageLimit
        }
      })
        .then(res => {
          if (res.errors) {
            if (res.errors[0].code.includes("没有权限")) {
              if (this.userId) {
                this.$toast.fail("您没有权限访问此内容");
              } else {
                browserDb.setSItem("beforeVisiting", this.$route.path);
                if (this.isWeixin) {
                  this.$router.push({ path: "/wx-sign-up-bd" });
                } else {
                  this.$router.push({ path: "/login-user" });
                }
              }
            } else {
              this.$toast.fail(res.errors[0].code);
            }
            throw new Error(res.error);
          } else {
            appCommonH.setPageTitle("detail", res);
            this.likeLen = res.readdata.firstPost.likedUsers.length;
            this.finished = res.readdata.posts.length < this.pageLimit;
            if (initFlag) {
              this.collectStatus = res.readdata._data.isFavorite;
              this.essenceStatus = res.readdata._data.isEssence;
              this.stickyStatus = res.readdata._data.isSticky;
              if (
                res.readdata.threadVideo &&
                res.readdata.threadVideo._data.status == 0
              ) {
                this.contentExamine = true;
                this.examineWord = "视频转码中，转码成功后才能正常播放";
              } else if (
                res.readdata._data.isApproved === 0 ||
                res.readdata._data.isApproved === 2
              ) {
                this.contentExamine = true;
                this.examineWord = "内容正在审核中，审核通过后才能正常显示！";
              } else {
                this.contentExamine = false;
              }
              if (res.readdata._data.type == 1) {
                this.themeTitle = res.readdata._data.title;
              } else if (res.readdata._data.type == 0) {
                this.themeTitle = res.readdata.firstPost._data.contentHtml;
              }

              if (this.collectStatus) {
                this.collectFlag = "已收藏";
              } else {
                this.collectFlag = "收藏";
              }
              if (this.essenceStatus) {
                this.essenceFlag = "取消加精";
              } else {
                this.essenceFlag = "加精";
              }
              if (this.stickyStatus) {
                this.stickyFlag = "取消置顶";
              } else {
                this.stickyFlag = "置顶";
              }
              this.themeShow = true;
              this.themeCon = res.readdata;
              console.log(this.themeCon, "~~~~~~~~~~~~~~~~~");
              // alert(this.themeCon._data.type, 'dddd222222222');
              this.canLike = res.readdata.firstPost._data.canLike;
              this.canViewPosts = res.readdata._data.canViewPosts;
              this.canReply = res.readdata._data.canReply;
              this.postsList = res.readdata.posts;
              this.likedUsers = res.readdata.firstPost.likedUsers;
              this.rewardedUsers = res.readdata.rewardedUsers;
              this.themeUserId = res.readdata.user._data.id;
              this.type = res.readdata._data.type;

              if (res.readdata.firstPost._data.isLiked) {
                this.themeIsLiked = true;
              } else {
                this.themeIsLiked = false;
              }
              this.themeIsLiked = res.readdata.firstPost._data.isLiked;
              var firstpostImageLen = this.themeCon.firstPost.images.length;
              this.postsList.map(post => {
                let urls = [];
                post.images.map(image => urls.push(image._data.url));
                this.postsImages.push(urls);
              });

              if (firstpostImageLen > 0) {
                // return;
                var firstpostImage = [];
                var firstpostImageOriginal = [];
                for (let i = 0; i < firstpostImageLen; i++) {
                  firstpostImage.push(
                    this.themeCon.firstPost.images[i]._data.thumbUrl
                  ); //缩略图
                  firstpostImageOriginal.push(
                    this.themeCon.firstPost.images[i]._data.url
                  );
                }
                this.firstpostImageList = firstpostImage;
                this.firstpostImageListOriginal = firstpostImageOriginal;
              }
              // 初始化时获取微信分享数据
              // this.wxShare();
            } else {
              // this.themeCon.posts = res.readdata.posts;
              this.themeCon.posts = this.themeCon.posts.concat(
                res.readdata.posts
              );
              this.loading = false;
              this.likeLen = this.themeCon.firstPost.likedUsers.length;
            }
          }
          this.wxShareDetail(); //调用微信分享
        })
        .catch(err => {
          if (this.loading && this.pageIndex !== 1) {
            this.pageIndex--;
          }
        })
        .finally(() => {
          this.loading = false;
        });
    },
    //主题详情图片放大轮播
    imageSwiper(imgIndex, typeclick, replyItem) {
      if (typeclick == "detailImg") {
        //当点击详情页内容图片时
      } else if (typeclick == "replyImg") {
        //主题回复图片预览
        ImagePreview({
          images: this.postsImages[replyItem],
          startPosition: imgIndex, //图片预览起始位置索引 默认 0
          showIndex: true, //是否显示页码         默认 true
          showIndicators: true, //是否显示轮播指示器 默认 false
          loop: true, //是否开启循环播放  貌似循环播放是不起作用的。。。
          closeOnPopstate: true
        });
      }
    },
    onChangeImgPreview() {
      this.index = index;
    },
    cutString(str, len) {
      if (str.length <= len) {
        return str;
      }
      return str.substring(0, len - 3) + "...";
    },
    removeHtmlTag(str) {
      return str.replace(/<[^>]+>|\n/g, ""); //正则去掉所有的html标记
    },
    copyFocus(obj) {
      obj.blur;
      document.body.removeChild(obj);
    },
    //分享，复制浏览器地址
    shareTheme() {
      if (this.isWeixin) {
        this.wxShareTip = true;
      } else {
        let Url = "";
        if (this.isPayVal === "pay") {
          Url =
            appConfig.baseUrl +
            "/pay-circle-con/" +
            this.themeId +
            "/" +
            this.groupId;
        } else {
          Url = appConfig.baseUrl + "/details/" + this.themeId;
        }
        // var Url= appConfig.baseUrl+'/pay-circle-con/'+ this.themeId + '/' + this.groupId;
        var oInput = document.createElement("input");
        var reTag = /<img(?:.|\s)*?>/g;
        var reTag2 = /(<\/?br.*?>)/gi;
        var reTag3 = /(<\/?p.*?>)/gi;
        this.themeTitle = this.themeTitle.replace(reTag, "");
        this.themeTitle = this.themeTitle.replace(reTag2, "");
        this.themeTitle = this.themeTitle.replace(reTag3, "");
        this.themeTitle = this.themeTitle.replace(/\s+/g, "");
        this.themeTitle = this.cutString(this.themeTitle, 20);
        oInput.value = this.themeTitle + "  " + Url;
        document.body.appendChild(oInput);
        oInput.select(); // 选择对象
        oInput.readOnly = true;
        oInput.id = "copyInp";
        document.execCommand("Copy");
        oInput.setAttribute("onfocus", this.copyFocus(oInput));
        // 执行浏览器复制命令
        oInput.className = "oInput";
        oInput.style.display = "none";
        this.$toast.success("分享链接已复成功");
        document.body.removeChild(oInput);
      }
    },
    //关闭微信分享提示
    wxShareClose() {
      this.wxShareTip = false;
    },

    stopKeyborad() {
      // this.showcount = true;
      // this.$refs.address.setAttribute('readonly', 'readonly');
      document.activeElement.blur();
    },
    //退出登录
    signOut() {
      browserDb.removeLItem("tokenId");
      browserDb.removeLItem("Authorization");
      this.$router.push({
        path: "/login-user"
      });
    },
    //跳转到登录页
    loginJump: function() {
      browserDb.setSItem("beforeVisiting", this.$route.path);
      this.$router.replace({ path: "/login-user" });
      browserDb.setLItem("themeId", this.themeId);
    },
    //跳转到注册页
    registerJump: function() {
      this.$router.push({
        path: "/sign-up"
      });
      browserDb.setLItem("themeId", this.themeId);
    },
    //点击用户名称，跳转到用户主页
    jumpPerDet: function(id) {
      this.$router.push({ path: "/home-page" + "/" + id });
    },

    //主题管理
    bindScreen: function() {
      //是否显示筛选内容
      this.showScreen = !this.showScreen;
    },
    listenEvt(e) {
      if (this.$refs.screenBox) {
        if (!this.$refs.screenBox.contains(e.target)) {
          this.showScreen = false;
        }
      }
    },
    //管理操作
    themeOpera(postsId, clickType, cateId, content) {
      if (!this.token) {
        this.$router.push({
          path: "/login-user",
          name: "login-user"
        });
      } else {
        let attri = new Object();
        if (clickType == 1) {
          if (this.collectStatus) {
            attri.isFavorite = false;
          } else {
            attri.isFavorite = true;
          }
          content = "";
          this.themeOpeRequest(attri, cateId, "1");
        } else if (clickType == 2) {
          content = "";
          if (this.essenceStatus) {
            attri.isEssence = false;
          } else {
            attri.isEssence = true;
          }
          this.themeOpeRequest(attri, cateId, "2");
        } else if (clickType == 3) {
          content = "";
          if (this.stickyStatus) {
            attri.isSticky = false;
          } else {
            attri.isSticky = true;
          }
          this.themeOpeRequest(attri, cateId, "3");
        } else if (clickType == 4) {
          attri.isDeleted = true;
          content = "";
          this.themeOpeRequest(attri, cateId, "4");
        } else {
          //跳转到编辑页页
          if (this.type == 1) {
            this.$router.replace({
              path: "/edit-long-text" + "/" + this.themeId
            });
          } else if (this.type == 0) {
            this.$router.replace({
              path: "/edit-topic" + "/" + this.themeId
            });
          } else if (this.type == 2) {
            this.$router.replace({
              path: "/edit-video" + "/" + this.themeId
            });
          }
        }
      }
    },
    //主题操作接口请求
    themeOpeRequest(attri, cateId, clickType) {
      this.appFetch({
        url: "threads",
        splice: "/" + this.themeId,
        method: "patch",
        data: {
          data: {
            type: "threads",
            attributes: attri
          },
          relationships: {
            category: {
              data: {
                type: "categories",
                id: cateId
              }
            }
          }
        }
      }).then(res => {
        if (res.errors) {
          this.$toast.fail(res.errors[0].code);
          throw new Error(res.error);
        } else {
          if (clickType == "1") {
            this.collectStatus = res.readdata._data.isFavorite;
            if (this.collectStatus) {
              this.collectFlag = "已收藏";
            } else {
              this.collectFlag = "收藏";
            }
          } else if (clickType == "2") {
            this.essenceStatus = res.readdata._data.isEssence;
            if (this.essenceStatus) {
              this.essenceFlag = "取消加精";
            } else {
              this.essenceFlag = "加精";
            }
          } else if (clickType == "3") {
            this.stickyStatus = res.readdata._data.isSticky;
            if (this.stickyStatus) {
              this.stickyFlag = "取消置顶";
            } else {
              this.stickyFlag = "置顶";
            }
          } else if (clickType == "4") {
            //删除
            this.deletedStatus = res.readdata._data.isDeleted;
            if (this.deletedStatus) {
              this.$toast.success("删除成功，跳转到首页");
              this.$router.push({
                path: "/circle",
                name: "circle"
              });
            }
          }
        }
      });
    },

    //删除请求接口
    deleteOpear(postId, postIndex) {
      let attri = new Object();
      attri.isDeleted = true;
      this.appFetch({
        url: "posts",
        splice: "/" + postId,
        method: "patch",
        data: {
          data: {
            type: "posts",
            attributes: attri
          }
        }
      }).then(res => {
        if (res.errors) {
          this.$toast.fail(res.errors[0].code);
          throw new Error(res.error);
        } else {
          this.$toast.success("删除成功");
          this.pageIndex = 1;
          this.postsList.splice(postIndex, 1);
        }
      });
    },

    //回复点赞
    replyOpera(postId, type, isLike, postsCanLike, postIndex) {
      if (!this.token) {
        this.$router.push({
          path: "/login-user",
          name: "login-user"
        });
      } else {
        if (!this.clickStatus) {
          return false;
        }
        this.clickStatus = false;
        let attri = new Object();
        if (type == 2) {
          if (!postsCanLike) {
            this.$toast.fail("没有权限，请联系站点管理员");
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
        this.appFetch({
          url: "posts",
          splice: "/" + postId,
          method: "patch",
          data: {
            data: {
              type: "posts",
              attributes: attri
            }
          }
        }).then(res => {
          if (res.errors) {
            this.$toast.fail(res.errors[0].code);
            throw new Error(res.error);
          } else {
            // isLike = res.readdata._data.isLiked;
            if (isLike) {
              this.postsList[postIndex]._data.likeCount =
                this.postsList[postIndex]._data.likeCount - 1;
              this.postsList[postIndex]._data.isLiked = false;
            } else {
              this.postsList[postIndex]._data.likeCount =
                this.postsList[postIndex]._data.likeCount + 1;
              this.postsList[postIndex]._data.isLiked = true;
            }
            this.pageIndex = 1;
            this.clickStatus = true;
          }
        });
      }
    },

    //主题点赞
    footReplyOpera(postId, type, isLike, postsCanLike, postIndex) {
      if (!this.token) {
        this.$router.push({
          path: "/login-user",
          name: "login-user"
        });
      } else {
        let attri = new Object();
        if (type == 3) {
          if (!this.canLike) {
            this.$toast.fail("没有权限，请联系站点管理员");
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
        this.appFetch({
          url: "posts",
          splice: "/" + postId,
          method: "patch",
          data: {
            data: {
              type: "posts",
              attributes: attri
            }
          }
        }).then(res => {
          if (res.errors) {
            this.$toast.fail(res.errors[0].code);
            throw new Error(res.error);
          } else {
            if (isLike) {
              // this.likedUsers = this.likedUsers.filter(value => value._data.id !== this.userId);
              this.likedUsers.map((value, key, likedUsers) => {
                value._data.id === this.userId && likedUsers.splice(key, 1);
              });
              this.likeLen = this.likeLen - 1;
              this.userArr(this.likedUsers);
              this.themeCon.firstPost._data.isLiked = false;
              this.themeIsLiked = false;
            } else {
              // 未点赞时，点击点赞'
              this.likedUsers.unshift({
                _data: { username: this.currentUserName, id: this.userId }
              });
              this.themeCon.firstPost._data.isLiked = true;
              this.likeLen = this.likeLen + 1;
              this.themeIsLiked = true;
            }
            this.pageIndex = 1;
            // this.detailsLoad(true);
          }
        });
      }
    },

    //打赏
    showRewardPopup() {
      if (!this.token) {
        this.$router.push({
          path: "/login-user",
          name: "login-user"
        });
      } else {
        if (this.userId == this.themeUserId) {
          this.$toast.fail("不能打赏自己");
        } else {
          this.rewardShow = true;
          if (
            this.isWeixin != true &&
            this.isPhone != true &&
            this.rewardShow
          ) {
          }
        }
      }
    },
    //跳转到回复页
    replyToJump: function(themeId, replyId, quoteCon) {
      if (!this.token) {
        this.$router.push({
          path: "/login-user",
          name: "login-user"
        });
      } else if (!this.canReply) {
        this.$toast.fail("没有权限，请联系站点管理员");
      } else {
        this.$router.replace({
          path: "/reply-to-topic" + "/" + themeId + "/" + replyId,
          replace: true
        });
        browserDb.setLItem("replyQuote", quoteCon);
      }
    },

    onBridgeReady(data) {
      let that = this;
      WeixinJSBridge.invoke(
        "getBrandWCPayRequest",
        {
          appId: data.data.attributes.wechat_js.appId, //公众号名称，由商户传入
          timeStamp: data.data.attributes.wechat_js.timeStamp, //时间戳，自1970年以来的秒数
          nonceStr: data.data.attributes.wechat_js.nonceStr, //随机串
          package: data.data.attributes.wechat_js.package,
          signType: "MD5", //微信签名方式：
          paySign: data.data.attributes.wechat_js.paySign //微信签名
        },
        function(res) {
          // alert('支付唤醒');

          if (res.err_msg == "get_brand_wcpay_request:cancel") {
            that.payLoading = false;
            resolve;
          } else if (res.err_msg == "get_brand_wcpay_request:fail") {
            that.payLoading = false;
            resolve;
          }
        }
      );

      const payWechat = setInterval(() => {
        if (this.payStatus == "1" || this.payStatusNum > 10) {
          clearInterval(payWechat);
          return;
        }
        this.getOrderStatus();
      }, 3000);
    },
    payClick(amount) {
      this.amountNum = amount;
      this.show = !this.show;
      //  this.payImmediatelyClick();
    },
    payImmediatelyClick(data) {
      this.rewardShow = false;
      //data返回选中项

      let isWeixin = this.appCommonH.isWeixin().isWeixin;
      let isPhone = this.appCommonH.isWeixin().isPhone;

      if (data.name === "微信支付") {
        this.show = false;
        if (isWeixin && isPhone) {
          //微信
          this.getOrderSn(this.amountNum).then(() => {
            this.orderPay(12).then(res => {
              if (typeof WeixinJSBridge == "undefined") {
                if (document.addEventListener) {
                  document.addEventListener(
                    "WeixinJSBridgeReady",
                    this.onBridgeReady(res),
                    false
                  );
                } else if (document.attachEvent) {
                  document.attachEvent(
                    "WeixinJSBridgeReady",
                    this.onBridgeReady(res)
                  );
                  document.attachEvent(
                    "onWeixinJSBridgeReady",
                    this.onBridgeReady(res)
                  );
                }
              } else {
                this.onBridgeReady(res);
              }
            });
          });
        } else if (isPhone) {
          //手机浏览器
          this.getOrderSn(this.amountNum).then(() => {
            this.orderPay(11).then(res => {
              this.wxPayHref = res.readdata._data.wechat_h5_link;
              window.location.href = this.wxPayHref;
              const payPhone = setInterval(() => {
                if (this.payStatus && this.payStatusNum > 10) {
                  clearInterval(payPhone);
                  return;
                }
                this.getOrderStatus();
              }, 3000);
            });
          });
        } else {
          // pc
          this.getOrderSn(this.amountNum).then(() => {
            this.orderPay(10).then(res => {
              this.codeUrl = res.readdata._data.wechat_qrcode;
              this.qrcodeShow = true;
              const pay = setInterval(() => {
                if (this.payStatus && this.payStatusNum > 10) {
                  clearInterval(pay);
                  return;
                }
                this.getOrderStatus();
              }, 3000);
            });
          });
        }
      }
    },
    onInput(key) {
      this.value = this.value + key;
      if (this.value.length === 6) {
        this.errorInfo = "";
        this.getOrderSn(this.amountNum).then(() => {
          this.orderPay(20, this.value).then(res => {
            if (res.errors) {
            } else {
              const pay = setInterval(() => {
                if (this.payStatus && this.payStatusNum > 10) {
                  clearInterval(pay);
                  return;
                }
                this.getOrderStatus();
              }, 3000);
            }
          });
        });
      }
    },
    //刪除
    onDelete() {
      this.value = this.value.slice(0, this.value.length - 1);
    },
    //关闭
    onClose() {
      this.value = "";
      this.errorInfo = "";
      this.payLoading = false;
    },

    //创建订单
    getOrderSn(amount) {
      return this.appFetch({
        url: "orderList",
        method: "post",
        data: {
          data: {
            attributes: {
              type: 2,
              thread_id: this.themeId,
              amount: amount
            }
          }
        }
      }).then(res => {
        this.orderSn = res.readdata._data.order_sn;
      });
    },
    //订单支付
    orderPay(type, value) {
      return this.appFetch({
        url: "orderPay",
        method: "post",
        splice: "/" + this.orderSn,
        data: {
          data: {
            attributes: {
              payment_type: type,
              pay_password: value
            }
          }
        }
      }).then(res => {
        if (res.errors) {
          this.value = "";
          if (res.errors[0].detail) {
            this.$toast.fail(
              res.errors[0].code + "\n" + res.errors[0].detail[0]
            );
          } else {
            this.$toast.fail(res.errors[0].code);
          }
        } else {
          this.payLoading = true;
        }
        return res;
      });
    },
    getOrderStatus() {
      return this.appFetch({
        url: "order",
        method: "get",
        splice: "/" + this.orderSn,
        data: {}
      }).then(res => {
        if (res.errors) {
          if (res.errors[0].detail) {
            this.$toast.fail(
              res.errors[0].code + "\n" + res.errors[0].detail[0]
            );
          } else {
            this.$toast.fail(res.errors[0].code);
            throw new Error(res.error);
          }
        } else {
          this.payStatus = res.readdata._data.status;
          this.payStatusNum++;
          if (this.payStatus == "1" || this.payStatusNum > 10) {
            this.rewardShow = false;
            this.qrcodeShow = false;
            this.payLoading = false;
            this.show = false;
            if (this.payStatus == "1") {
              this.rewardedUsers.unshift({
                _data: { avatarUrl: this.currentUserAvatarUrl, id: this.userId }
              });
              this.$toast.success("支付成功");
            }
            this.payStatusNum = 11;
          }
        }
        // return res;
      });
    },
    //打赏过程中关闭pc端微信扫码支付
    closeQrCode() {
      this.qrcodeShow = false;
      this.payLoading = false;
    },
    //上拉加载
    onLoad() {
      this.loading = true;
      this.pageIndex++;
      this.detailsLoad();
    },
    //下拉刷新
    onRefresh() {
      this.pageIndex = 1;
      this.detailsLoad(true)
        .then(res => {
          this.$toast("刷新成功");
          this.isLoading = false;
          this.finished = false;
        })
        .catch(err => {
          this.$toast("刷新失败");
          this.isLoading = false;
        });
    },
    // 微信分享
    wxShareDetail() {
      var title = "";
      var desc = "";
      var logo = "";
      if (this.themeCon._data.type == 0) {
        //普通主题
        var strippedContent = this.removeHtmlTag(
          this.themeCon.firstPost._data.contentHtml
        );
        desc = this.cutString(strippedContent, 60);
        title = this.cutString(strippedContent, 20) + " - " + this.siteName;
        if (this.firstpostImageList.length > 0) {
          logo = this.firstpostImageList[0];
        } else {
          logo = appConfig.baseUrl + "/static/images/wxshare.png";
        }
      } else if (this.themeCon._data.type == 1) {
        //长文类型
        if (this.themeCon._data.price > 0) {
          desc = "";
        } else {
          desc = this.cutString(
            this.removeHtmlTag(this.themeCon.firstPost._data.contentHtml),
            60
          );
        }
        title = this.themeCon._data.title + " - " + this.siteName;
        if (this.firstpostImageList.length > 0) {
          logo = this.firstpostImageList[0];
        } else {
          logo = appConfig.baseUrl + "/static/images/wxshare.png";
        }
      } else if (this.themeCon._data.type == 2) {
        //视频类型
        var strippedContent = this.removeHtmlTag(
          this.themeCon.firstPost._data.contentHtml
        );
        desc = this.cutString(strippedContent, 60);
        title = this.cutString(strippedContent, 20) + " - " + this.siteName;
        if (this.themeCon.threadVideo._data.cover_url) {
          logo = this.themeCon.threadVideo._data.cover_url;
        } else {
          logo = appConfig.baseUrl + "/static/images/wxshare.png";
        }
      }
      let data = {
        title: title,       // 分享标题
        desc: desc,         // 分享描述
        link: window.location.href.split("#")[0],// 分享链接，该链接域名或路径必须与当前页面对应的公众号JS安全域名一致
        logo: logo               // 分享图标
      }
      wxShare(data, { name: 'circle' })
    },
    urlify(text) {
      var urlRegex = /(https?:\/\/[^\s]+)/g;
      return text.replace(urlRegex, function(url) {
          if (url.includes('/emoji/qq/')) {
            return url;
          }
          return '<a href="' + url + '">' + url + '</a>';
      })
    }
  },
  mounted: function() {
    document.addEventListener("click", this.listenEvt, false);
  },
  destroyed: function() {
    document.removeEventListener("click", this.listenEvt, false);
  },
  beforeRouteLeave(to, from, next) {
    document.removeEventListener("click", this.listenEvt, false);
    next();
  }
};
