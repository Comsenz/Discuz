/**
 * 后台Index
 */


export default {
  data:function () {
    return {
      // activeIndex: '1',

      indexTitle:"管理中心首页",  //页面内容标题  /顶部导航下面
      sideTitle:"首页", //左侧菜单标题

      navList:[
        {
          id:0,
          title:'首页',
          name:'home',
          submenu:[{
            id:0,
            title:'管理中心首页',
            name:'controlCenter',
            icon:'iconshouye'
          }]
        },
        {
          id:1,
          title:'全局',
          name:'global',
          submenu:[
            {
              id:0,
              title:'站点设置',
              name:'siteSet',
              icon:'iconzhandianshezhi'
            },
            {
              id:1,
              title:'注册设置',
              name:'signUpSet',
              icon:'iconzhuceshezhi'
            },
            {
              id:2,
              title:'第三方登录设置',
              name:'worthMentioningSet',
              icon:'icondisanfangdenglushezhi'
            },
            {
              id:3,
              title:'支付设置',
              name:'paySet',
              icon:'iconzhifushezhi'
            },
            {
              id:4,
              title:'附件设置',
              name:'annexSet',
              icon:'iconfujianshezhi'
            },
            {
              id:5,
              title:'内容过滤设置',
              name:'contentFilteringSet',
              icon:'iconneirongguolvshezhi'
            },
            {
              id:6,
              title:'腾讯云设置',
              name:'tencentCloudSet',
              icon:'icontengxunyun'
            },
            {
              id:7,
              title:'后台用户管理',
              name:'userManage',
              icon:'iconyonghuguanli'
            },
            {
              id:8,
              title:'后台角色管理',
              name:'roleManage',
              icon:'iconjiaoseguanli'
            }]
        },
        {
          id:2,
          title:'用户',
          name:'user'
        },
        {
          id:3,
          title:'内容',
          name:'cont'
        },
        {
          id:4,
          title:'财务',
          name:'finance'
        }
      ],  //导航菜单列表
      navSelect:'',  //导航选中

      sideList:[{
        id:0,
        name:'管理中心首页',
        icon:'iconshouye'
      }],    //侧边菜单
      sideSelect:''    //侧边选中

    }
  },
  methods:{

    setDataStatus(){
      //设置页面刷新前状态，通过路由获取

      let attribution = this.$router.history.current.meta.attribution;  //导航名字
      let name = this.$router.history.current.meta.name;  //子菜单唯一标识符
      let title = this.$router.history.current.meta.title;  //子菜单名字

      switch (attribution){
        case '首页':
          this.navSelect = this.navList[0].name;
          this.indexTitle = title;
          this.sideTitle = attribution;
          this.sideSelect = name;
          this.sideList = this.navList[0].submenu;
          break;
        case '全局':
          this.navSelect = this.navList[1].name;
          this.indexTitle = title;
          this.sideTitle = attribution;
          this.sideSelect = name;
          this.sideList = this.navList[1].submenu;
          break;
        default :
          console.log("获取菜单出错");
      }

      console.log(this.sideList);

    },

    menuClick(item){
      this.sideTitle = item.title;

      this.navSelect = item.name;

      switch (item.name){
        case 'home':
          this.sideList = this.navList[0].submenu;
          this.sideSelect = this.navList[0].submenu[0].name;
          this.indexTitle = this.navList[0].submenu[0].title;
          this.$router.push({path:'/admin/home'});
          break;
        case 'global':
          this.sideList = this.navList[1].submenu;
          this.sideSelect = this.navList[1].submenu[0].name;
          this.indexTitle = this.navList[1].submenu[0].title;
          this.$router.push({path:'/admin/site-set'});
          break;
        default :
          this.sideList = [];
      }

    },

    sideClick(item){

      this.sideSelect = item.name;
      this.indexTitle = item.title;

      switch (item.name){
        case 'controlCenter':
          this.$router.push({path:'/admin/home'});
          break;
        case 'siteSet':
          this.$router.push({path:'/admin/site-set'});
          break;
        case 'signUpSet':
          this.$router.push({path:'/admin/sign-up-set'});
          break;
        case 'worthMentioningSet':
          this.$router.push({path:'/admin/worth-mentioning-set'});
          break;
        case 'paySet':
          this.$router.push({path:'/admin/pay-set'});
          break;
        case 'annexSet':
          this.$router.push({path:'/admin/annex-set'});
          break;
        case 'contentFilteringSet':
          this.$router.push({path:'/admin/content-filter-set'});
          break;
        case 'tencentCloudSet':
          this.$router.push({path:'/admin/tencent-cloud-set'});
          break;
        case 'userManage':
          this.$router.push({path:'/admin/user-manage-set'});
          break;
        case 'roleManage':
          this.$router.push({path:'/admin/role-manage-set'});
          break;
      }

    },

  },
  created(){
   this.setDataStatus();

  },

}
