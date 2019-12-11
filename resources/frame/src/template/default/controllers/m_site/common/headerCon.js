/**
 * 移动端header控制器
 */
import {Bus} from '../../../store/bus.js';
import Forum from '../../../../../common/models/Forum';
import browserDb from '../../../../../helpers/webDbHelper';
export default {
	data: function() {
    return {
		    headBackShow: false,
		    oneHeader: false,
		    twoHeader: false,
		    threeHeader: false,
		    fourHeader: false,
		    isfixNav: false,
		    // isfixHead: false,
		    isShow: false,
		    isHeadShow: false,
		    showHeader: false,
	      showMask: false,
	      title:'',
	      // invitePerDet: false,
	      // menuIconShow:false,
	      // searchIconShow: false,
		    // navShow: false,
		    navActi:0,
		    perDet:{
		    	themeNum: '1222',
		    	memberNum: '1222',
		    	circleLeader: '站长名称'
		    },
        avatarUrl:'',
        // username:'',
        mobile:'',
        userId:'',
	      isfixNav: false,
	      popupShow: false,
        current:0,
        userDet:[],
        categories:[],
        siteInfo: new Forum(),
        username:''
	  }
  },
	props: {
    userInfoAvatarUrl: { // 组件用户信息
      type: String
    },
    userInfoName: { // 组件用户信息
      type: String
    },
    headFixed: { // 组件是否悬浮头部
      headFixed: false
    },
    invitePerDet: { // 组件是否显示邀请人头像以及名称
      invitePerDet: false
    },
    searchIconShow: { // 组件是否显示搜索按钮
      searchIconShow: false

    },
    menuIconShow: { // 组件是否显示菜单按钮
      menuIconShow: false
    },
    navShow: { // 组件是否显示导航菜单
      navShow: false
    },
    invitationShow: { // 组件是否显示邀请这几个字
      invitationShow: false
    },
    perDetShow: { //组件是否显示站点信息
      perDet: false
    },
    logoShow: { //组件是否显示站点图标
      logoShow: false
    }
  },
  created: function() {
    // this.getUserInfo();
    this.loadCategories();
  },
  methods:{
    //初始化请求分类接口
    loadCategories(){
      //请求站点信息
      this.appFetch({
        url: 'forum',
        method: 'get',
        data: {
          include: ['users'],
          page: {
            offset: 20,
            num: 3
          }
        }
      }).then((res) => {
        console.log(res, 'res1111');
        this.siteInfo = res.readdata;
      })
      this.appFetch({
        url: 'categories',
        method: 'get',
        data: {
          include: [],
          // page: {
          //   offset: 20,
          //   num: 3
          // }
        }
      }).then((res) => {
        console.log(res, 'res3333');
        this.themeListCon = res.readdata;
      })




      // const params = {};
      //  params.include='users';
      //  this.apiStore.find('forum').then(data => {
      //    console.log(data.readdata+'222');
      //    this.siteInfo = data.readdata;
      //    // this.username = data.readdata.siteAuthor.username;
      //    // console.log(data.user());
      // });
      // this.apiStore.find('categories').then(data => {
      //   console.log(data[0].name());
      //   // console.log(data[0].user().username());
      //   this.categories = data;
      // });
    },
    // //获取用户信息
    // getUserInfo(){
    //     var userId = browserDb.getLItem('tokenId');
    //     this.apiStore.find('users', userId).then(data => {
    //       console.log(data.data.attributes.mobile);
    //       this.avatarUrl = data.data.attributes.avatarUrl;
    //       this.username = data.data.attributes.username;
    //       this.mobile = data.data.attributes.mobile;
    //     });
    // },

    backUrl () {
      // 返回上一级
      window.history.go(-1)
    },
    showPopup() {
      //侧边栏显示
      this.popupShow = true;
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
    // 先分别获得id为testNavBar的元素距离顶部的距离和页面滚动的距离
    // 比较他们的大小来确定是否添加fixedHead样式
    // 比较他们的大小来确定是否添加fixedNavBar样式
    handleTabFix(){
      // console.log(this.$route.meta.oneHeader);
      if(this.headFixed){
        var scrollTop = window.pageYOffset || document.documentElement.scrollTop || document.body.scrollTop
          var offsetTop = document.querySelector('#testNavBar').offsetTop;
          if(scrollTop > offsetTop){
            this.showHeader = true;
            // this.isfixHead = true;
            // console.log(this.isfixHead+'1');
            this.isfixNav = true;
            // scrollTop > offsetTop ? this.isfixHead = true : this.isfixHead = false;
            // scrollTop < offsetTop ? this.isfixNav = true : this.isfixNav = false
          } else {
            this.showHeader = false;
            // this.isfixHead = false;
            // console.log(this.isfixHead+'2');
            this.isfixNav = false;
            // scrollTop > offsetTop ? this.isfixHead = false : this.isfixHead = true;
            // scrollTop < offsetTop ? this.isfixNav = false : this.isfixNav = true
          };
      }
    },
    searchJump(){

    },
    backUrl(){
    // 返回上一级
    window.history.go(-1)
    },
    LogOut(){
      console.log('测试');
    },
    // bindSidebar () {
   //  // 是否显示侧边栏
    //   this.showSidebar = !this.showSidebar;
    //   this.showMask =  !this.showMask;
    // },
    // hideSidebar(){
    //   this.showSidebar = false;
    //   this.showMask =  false;
    // },
    bindEvent (typeName) {
      if (typeName == 1) {
        this.LogOut()
      }
    },
    // LogOut () {
    //   console.log('测试');
    // }

  },

  mounted: function() {
    // this.getVote();
    window.addEventListener('scroll', this.handleTabFix, true);
  },
  beforeRouteLeave (to, from, next) {
     window.removeEventListener('scroll', this.handleTabFix, true)
     next()
  }
}
