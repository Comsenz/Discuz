/**
 * 移动端header控制器
 */
import {Bus} from '../../../store/bus.js';
import Forum from '../../../../../common/models/Forum';
import browserDb from '../../../../../helpers/webDbHelper';
import appCommonH from '../../../../../helpers/commonHelper';
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
        siteInfo: false,
        username:'',
        isPayVal:'',
        isWeixin: false,
        isPhone: false,
        firstCategoriesId:'',
        logo:false
	  }
  },
	props: {
    // personInfo: { // 组件用户信息
    //   type: false
    // },
    // firstCategoryId:{
    //   type: String
    // },
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
  created(){
    this.isWeixin = appCommonH.isWeixin().isWeixin;
    this.isPhone = appCommonH.isWeixin().isPhone;
    // console.log(this.isWeixin+'0'+this.isPhone);
    // this.getUserInfo();
    this.loadCategories();
    //把第一个分类的id值传过去，便于请求初始化主题列表

  },
  watch: {
    'isfixNav': function(newVal,oldVal){
        this.isfixNav = newVal;
    }
  },
  methods:{
    //设置底部在pc里的宽度
    limitWidth(){
      document.getElementById('testNavBar').style.width = "640px";
      let viewportWidth = window.innerWidth;
      document.getElementById('testNavBar').style.marginLeft = (viewportWidth - 640)/2+'px';
    },
    //初始化请站点信息和分类接口
    loadCategories(){
      //请求站点信息
      this.appFetch({
        url: 'forum',
        method: 'get',
        data: {
          include: ['users'],
        }
      }).then((res) => {
        this.siteInfo = res.readdata;
        if(res.readdata._data.logo){
          this.logo = res.readdata._data.logo;
        }
        //把站点是否收费的值存储起来，以便于传到父页面
        this.isPayVal = res.readdata._data.siteMode;
      })
      //请求分类接口
      this.appFetch({
        url: 'categories',
        method: 'get',
        data: {
          include: [],
        }
      }).then((res) => {
        console.log('2222');
        console.log(res);
        this.categories = res.readdata;
        this.firstCategoriesId = res.readdata[0]._data.id;
        console.log(this.firstCategoriesId);
        this.$emit("update", this.firstCategoriesId);
        console.log('3456');
      })
    },
    backUrl () {
      // 返回上一级
      window.history.go(-1)
    },
    showPopup() {
      //侧边栏显示
      this.popupShow = true;
    },
    //给导航添加点击状态
    categoriesCho(cateId){
      this.$emit('categoriesChoice',cateId);
    },

    searchJump() {
      this.$router.push({ path:'/search'});
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
            if(this.isWeixin != true && this.isPhone != true){
              this.limitWidth();
            }
            //
            // scrollTop > offsetTop ? this.isfixHead = true : this.isfixHead = false;
            // scrollTop < offsetTop ? this.isfixNav = true : this.isfixNav = false
          } else {
            this.showHeader = false;
            // this.isfixHead = false;
            // console.log(this.isfixHead+'2');
            this.isfixNav = false;
            let viewportWidth = window.innerWidth;
            document.getElementById('testNavBar').style.marginLeft ='0px';
            // scrollTop > offsetTop ? this.isfixHead = false : this.isfixHead = true;
            // scrollTop < offsetTop ? this.isfixNav = false : this.isfixNav = true
          };
      }
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
    window.addEventListener('scroll', this.handleTabFix, true);
  },
  beforeDestroy() {
      window.removeEventListener('scroll', this.handleTabFix, true);
  },
  destroyed() {
      window.removeEventListener('scroll', this.handleTabFix, true);
  },
  beforeRouteLeave (to, from, next) {
     window.removeEventListener('scroll', this.handleTabFix, true);
     next();
  }
}
