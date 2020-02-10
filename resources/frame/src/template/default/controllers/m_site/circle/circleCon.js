
/**
 * 移动端站点首页控制器
 */
import browserDb from '../../../../../helpers/webDbHelper';
import appCommonH from '../../../../../helpers/commonHelper';
export default {
  data: function() {
    return {
      showScreen: false,
      loginBtnFix: false,
      loginHide:false,
      // footShow: true,
      fourHeader: true,
      isWx:'1',
      // replyTag: false,
      themeChoList: [
        {
          typeWo: '全部主题',
          type:'1',
          themeType:'allThemes'
        },
        {
          typeWo: '精华主题',
          type:'2',
          themeType:'isEssence'
        },
        {
          typeWo: '关注用户的',
          type:'3',
          themeType:'fromUserId'
        }
      ],
      // themeListCon:false,
      themeListCon:[],
      themeNavListCon:[],
      currentData:{},
      replyTagShow: false,
      firstpostImageListCon:[],
      loading: false,  //是否处于加载状态
      finished: false, //是否已加载完所有数据
      isLoading: false, //是否处于下拉刷新状态
      pageIndex: 0,//页码
      pageLimit: 20,
      offset: 100, //滚动条与底部距离小于 offset 时触发load事件
      canEdit:true,
      firstCategoriesId:'',
      Initialization:false,     //当请求到默认分类id时，允许初始化开关
      searchStatus: false,  //默认不显示搜索按钮
      menuStatus: false,     //默认不显示菜单按钮
      categoryId:false,
      filterInfo: {
        filterCondition: 'allThemes',
        typeWo: '全部主题'
      },
      canCreateThread:'',
      canViewThreads:'',
      nullTip:false,
      nullWord:'',
      allowRegister:'',
      loginWord:'登录 / 注册',
      isWeixin: false,
      isPhone: false,

    }
  },
  created:function(){
      this.getInfo();
      this.load();
      this.isWeixin = appCommonH.isWeixin().isWeixin;
      this.isPhone = appCommonH.isWeixin().isPhone;
      this.onLoad();
      this.detailIf();
  },

  methods: {
    receive: function (val_1) {
      this.firstCategoriesId = val_1;
      // this.Initialization = true;
      // console.log(this.Initialization);
      // this.loadThemeList();
    },
    //设置发表主题按钮在pc里的位置
    limitWidth(limitId){
      // alert('设置');
      console.log(limitId);
      console.log(limitId);
      let viewportWidth = window.innerWidth;
      document.getElementById(limitId).style.right = ((viewportWidth - 640)/2 + 30) +'px';
      // document.getElementById('fixedEdit').style.right = "100px";
    },
    getInfo(){
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
          throw new Error(res.error);
        } else {
          console.log('44443');
          console.log(res);
          this.siteInfo = res.readdata;
          this.canCreateThread = res.readdata._data.other.can_create_thread;
          this.canViewThreads = res.readdata._data.other.can_view_threads;
          this.allowRegister = res.readdata._data.set_reg.register_close;
          if(!this.allowRegister){
            this.loginWord = '登录';
          }
          console.log(res.readdata._data.set_site.site_mode+'请求');
          // this.siteUsername = res.readdata._data.siteAuthor.username;
          this.sitePrice = res.readdata._data.set_site.site_price;
          //把站点是否收费的值存储起来，以便于传到父页面
          this.isPayVal = res.readdata._data.set_site.site_mode;
          if(this.isPayVal != null && this.isPayVal != ''){
            this.isPayVal = res.readdata._data.set_site.site_mode;
            console.log('可以访问');
            //判断站点信息是否付费，用户是否登录，用户是否已支付
            // this.detailIf(this.isPayVal,false);

          }
        }
      });
    },
    //请求用户信息
    // getUser(){
    // //初始化请求User信息，用于判断当前用户是否已付费
    //   var userId = browserDb.getLItem('tokenId');
    //   this.appFetch({
    //     url: 'users',
    //     method: 'get',
    //     splice:'/'+userId,
    //     data: {
    //       include: 'groups',
    //     }
    //   }).then((res) => {
    //     // console.log(res.readdata._data.username);
    //     this.username = res.readdata._data.username;
    //     this.isPaid = res.readdata._data.paid;
    //     this.roleList = res.readdata.groups;
    //     if(res.readdata._data.joinedAt=='' || res.readdata._data.joinedAt == null){
    //       this.joinedAt = res.readdata._data.createdAt;
    //     } else {
    //       this.joinedAt = res.readdata._data.joinedAt;
    //     }
    //     if(this.isPaid != null && this.isPaid != ''){
    //       this.detailIf(this.isPayVal,false);
    //     }
    //     // this.detailIf(false,this.isPaid);
    //   })

    // },

    //首页，逻辑判断
    detailIf(){
      var token = browserDb.getLItem('Authorization');
      if(token){
        console.log('已登录');
        //当用户已登录时
        // this.loadThemeList();
        this.loginBtnFix = false;
        this.loginHide = true;
        this.canEdit = true;
        this.searchStatus = true;
        this.menuStatus = true;
        if(this.canEdit){
          if(this.isWeixin != true && this.isPhone != true){
            this.limitWidth('fixedEdit');
          }
        }
      }  else {
        console.log('未登录');
        // //当用户未登录时
        this.loginBtnFix = true;
        this.loginHide = false;
        this.canEdit = false;
      }
    },
    // detailIf(isPayVal){
    //   if(isPayVal == 'public'){
    //     //当站点为公开站点时
    //     console.log('公开');
    //     var token = browserDb.getLItem('Authorization');
    //     if(token){
    //       console.log('公开，已登录');
    //       //当用户已登录时
    //       // this.loadThemeList();
    //       this.loginBtnFix = false;
    //       this.loginHide = true;
    //       this.canEdit = true;
    //       this.searchStatus = true;
    //       this.menuStatus = true;
    //       if(this.canEdit){
    //         if(this.isWeixin != true && this.isPhone != true){
    //           this.limitWidth('fixedEdit');
    //         }
    //         // this.limitWidth('fixedEdit');
    //       }
    //     }  else {
    //       console.log('公开，未登录');
    //       // this.loadThemeList();
    //       // //当用户未登录时
    //       this.loginBtnFix = true;
    //       this.loginHide = false;
    //       this.canEdit = false;
    //     }
    //   } else {
    //     if(this.isWeixin != true && this.isPhone != true){
    //       this.limitWidth('fixedEdit');
    //     }
    //     this.searchStatus = true;
    //     this.menuStatus = true;
    //   }
    // },




    //初始化请求主题列表数据
    load(){
      let isWeixin =this.appCommonH.isWeixin().isWeixin;
      if(isWeixin == true){
        //微信登录时
        // alert('微信登录');
        this.isWx = 2;

      } else {
        //手机浏览器登录时
        // console.log('手机浏览器登录');
        this.isWx = 1;
      }
      return this.isWx;
    },
    // //接收站点是否收费的值
    // isPayFn (data) {
    //   // if (data == 'log') {
    //     console.log(data);
    //     // this.isPay = data;
    //   // }
    // },
    //初始化请求主题列表数据

    loadThemeList(filterCondition,filterVal){
      console.log(filterCondition,'123~~~~');
      var userId = browserDb.getLItem('tokenId');
      // if(!this.categoryId){
      //   this.categoryId = this.firstCategoriesId;
      // }

      if(filterVal){
        this.categoryId = filterVal;
      } else {
        // this.categoryId = this.firstCategoriesId;
        this.categoryId = 0;
      }
      let data = {
        'filter[isEssence]':'yes',
        'filter[fromUserId]':userId,
        'filter[categoryId]':this.categoryId,
        'filter[isApproved]':1,
        'filter[isDeleted]':'no',
        include: ['user', 'firstPost', 'firstPost.images', 'lastThreePosts', 'lastThreePosts.user', 'lastThreePosts.replyUser', 'firstPost.likedUsers', 'rewardedUsers'],
        'page[number]': this.pageIndex,
        'page[limit]': this.pageLimit
      }
      if(filterVal == 0){
        delete data['filter[categoryId]'];
      }
      if(filterCondition !== 'isEssence'){
        delete data['filter[isEssence]'];
      }
      if(filterCondition !== 'fromUserId'){
        delete data['filter[fromUserId]'];
      }
      console.log(data,'data数据');
      return this.appFetch({
        url: 'threads',
        method: 'get',
        data:data,
      }).then((res) => {
        console.log(res);
        console.log('3443431111');
        if (res.errors){
          // console.log(res);
          if(res.rawData[0].code == 'permission_denied'){
            this.nullTip = true;
            this.nullWord = res.errors[0].code;
          } else {
            this.$toast.fail(res.errors[0].code);
            throw new Error(res.error)
          }
        } else {
          console.log('正确请求');
          if(!this.canViewThreads){
            this.nullTip = true;
            this.nullWord = res.errors[0].code;
          } else {
            if(this.themeListCon.length<0){
              this.nullTip = true
            }
            this.themeListCon = this.themeListCon.concat(res.readdata);
            console.log(this.themeListCon);
            console.log('66544');
            this.loading = false;
            this.finished = res.readdata.length < this.pageLimit;
          }

        }
      }).catch((err)=>{
        if(this.loading && this.pageIndex !== 1){
          this.pageIndex--;
        }
        this.loading = false;
      })
    },

    //把图片url取出，组成一个新的数组（用户主题图片预览）
    pushImgArray(){
      //   var themeListLen = this.themeListCon.length;
      //   var firstpostImage = [];
      //   for (let h = 0; h < themeListLen; h++) {
      //     var firstpostImageLen = this.themeListCon[h].firstPost.images.length;
      //     firstpostImage.push(h,this.themeListCon[h]);
      //     console.log(this.themeListCon[h].firstPost.images);
      //     // console.log(this.themeListCon[h].firstPost.images);
      //     // if (firstpostImageLen === 0) return;
      //     // for (let i = 0; i < firstpostImageLen; i++) {
      //     //   firstpostImage.push(this.themeListCon[h].firstPost.images[i]._data.fileName);
      //     //   console.log(firstpostImage+'3333');
      //     //   // firstpostImage.push('https://img.yzcdn.cn/2.jpg');
      //     // }
      //   }
      // console.log(firstpostImage+'343434');
      // this.firstpostImageListCon = firstpostImage;
      // console.log(this.firstpostImageListCon+'5555');
    },
    // 先分别获得id为testNavBar的元素距离顶部的距离和页面滚动的距离
    // 比较他们的大小来确定是否添加fixedHead样式
    // 比较他们的大小来确定是否添加fixedNavBar样式
    footFix() {
        var scrollTop = window.pageYOffset || document.documentElement.scrollTop || document.body.scrollTop;
        var offsetTop = document.querySelector('#testNavBar').offsetTop;
          if(this.loginBtnFix == true){
            this.loginHide = true;
            // console.log(scrollTop+'1111');
            // console.log(offsetTop+'2222');
            if(scrollTop > offsetTop){
              // console.log('大于');
              this.loginHide = true;
              // console.log(this.loginHide);
            } else {
              // console.log('小于');
              this.loginHide = false;
            }
        }
      },
      //筛选
      choTheme(themeType) {
      console.log(themeType);
      console.log('筛选');
      // this.filterInfo.typeWo = themeType === 'isEssence' ? '精华主题' : '全部主题';
      // this.filterInfo.typeWo = themeType === 'isEssence' ? '精华主题' : '全部主题';
      if(themeType === 'isEssence') {
        this.filterInfo.typeWo = '精华主题';
      } else if(themeType === 'fromUserId') {
        this.filterInfo.typeWo = '关注用户的';
      } else {
        this.filterInfo.typeWo = '全部主题';
      }
      this.filterInfo.filterCondition = themeType;
      console.log(this.filterInfo.filterCondition,'类型');
      this.pageIndex = 1;
      this.themeListCon = [];

      this.loadThemeList(this.filterInfo.filterCondition,this.categoryId);
      },

      //点击分类
      categoriesChoice(cateId) {
      this.pageIndex = 1;
      this.themeListCon = [];
      this.loadThemeList(this.filterInfo.filterCondition,cateId);
      },
      //跳转到登录页
      loginJump:function(isWx){
      let wxCode =this.load();

      const that = this;
      that.$router.push({
        path:'wechat',
      });
      if(wxCode ==1){
        this.$router.push({ path:'login-user'});
      } else if(wxCode ==2){
        this.appFetch({
          url:"weixin",
          method:"get",
          data:{
            // attributes:this.attributes,
          }
        }).then(res=>{
          if (res.errors){
                this.$toast.fail(res.errors[0].code);
                throw new Error(res.error)
              } else {
            // alert(this.showScreen);
            // console.log(res.data.attributes.location);
            // window.location.href = res.data.attributes.location;
            this.$router.push({ path:'wechat'});
          }

        });

      }
      },
      postTopic:function(){
      if(this.canCreateThread){
        // alert('跳转到发布主题页');
        this.$router.push({ path:'/post-topic'});
      } else {
        this.$toast.fail('没有权限，请联系站点管理员');
      }

      },
      /**
      * 给导航添加点击状态
      */
      addClass:function(index,event){
      this.current=index;
      　　　　//获取点击对象
      var el = event.currentTarget;
       // alert("当前对象的内容："+el.innerHTML);
      },
      //筛选
      bindScreen:function(){
        //是否显示筛选内容
        this.showScreen = !this.showScreen;
      },
      listenEvt(e){
      if(!this.$refs.screenBox.contains(e.target)){
        this.showScreen = false;
      }
      },
      hideScreen(){
        //是否显示筛选内容
        this.showScreen = false;
      },
      onLoad(){    //上拉加载
      this.loading = true;
      this.pageIndex++;
      this.loadThemeList(this.filterCondition,this.categoryId);
      },
      onRefresh(){    //下拉刷新
        this.pageIndex = 1;
        this.themeListCon = [];
        this.nullTip = false;
        this.loadThemeList(this.filterCondition,this.categoryId).then(()=>{
          this.$toast('刷新成功');
          this.finished = false;
          this.isLoading = false;
        }).catch((err)=>{
          this.$toast('刷新失败');
          this.isLoading = false;
        })
      }
      },
  mounted: function() {
    // this.getVote();
    window.addEventListener('scroll', this.footFix, true);
    document.addEventListener('click',this.listenEvt, false);
  },
  destroyed: function() {
    // this.getVote();
    window.removeEventListener('scroll', this.footFix, true);
    document.removeEventListener('click',this.listenEvt, false);
  },
  beforeRouteLeave (to, from, next) {
     window.removeEventListener('scroll', this.footFix, true);
     document.removeEventListener('click',this.listenEvt, false);
     next()
  },
}
