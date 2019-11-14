/**
 * 后台Index
 */


export default {
  data:function () {
    return {
      activeIndex: '1',
      navList:[
        {
          id:0,
          name:'首页'
        },
        {
          id:1,
          name:'全局'
        },
        {
          id:2,
          name:'用户'
        },
        {
          id:3,
          name:'内容'
        },
        {
          id:4,
          name:'财务'
        }
      ],
      navSelect:0,  //导航选中
      indexTitle:"管理中心首页",
      sideTitle:"首页",

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
      //设置页面刷新前状态
      this.data = JSON.parse(localStorage.getItem('data'));
      this.indexTitle = this.data.indexTitle;
      this.navSelect = this.data.navSelect;
      this.sideTitle = this.data.sideTitle;
      this.sideList = this.data.sideList;
      this.sideSelect = this.data.sideSelect;
    },
    getDataStatus(){
      //存到本地页面刷新后读取状态
      localStorage.setItem('data',JSON.stringify({
        navSelect:this.navSelect,
        sideTitle:this.sideTitle,
        indexTitle:this.indexTitle,
        sideSelect:this.sideSelect,
        sideList:this.sideList
      }));
    },

    menuClick(item){
      this.sideTitle = item.name;

      this.navSelect = item.id;

      switch (item.id){
        case 0:
          this.sideList = [
            {
              id:0,
              name:'管理中心首页',
              icon:'iconshouye'
            }
          ];
          break;
        case 1:
          this.sideList = [
            {
              id:0,
              name:'站点设置',
              icon:'iconzhandianshezhi'
            },
            {
              id:1,
              name:'注册设置',
              icon:'iconzhuceshezhi'
            },
            {
              id:2,
              name:'第三方登录设置',
              icon:'icondisanfangdenglushezhi'
            },
            {
              id:3,
              name:'支付设置',
              icon:'iconzhifushezhi'
            },
            {
              id:4,
              name:'附件设置',
              icon:'iconfujianshezhi'
            },
            {
              id:5,
              name:'内容过滤设置',
              icon:'iconneirongguolvshezhi'
            },
            {
              id:6,
              name:'腾讯云设置',
              icon:'icontengxunyun'
            },
            {
              id:7,
              name:'后台用户管理',
              icon:'iconyonghuguanli'
            },
            {
              id:8,
              name:'后台角色管理',
              icon:'iconjiaoseguanli'
            }
          ];
          break;
        default :
          this.sideList = [];
      }

      this.getDataStatus();

    },

    sideClick(item){
      this.sideSelect = item.name;
      this.indexTitle = item.name;

      switch (item.name){
        case '管理中心首页':
          this.$router.push({path:'/admin/home'});
              break;
        case '站点设置':
          this.$router.push({path:'/admin/site-set'});
          break;
        case '注册设置':
          this.$router.push({path:'/admin/sign-up-set'});
          break;
        case '第三方登录设置':
          this.$router.push({path:'/admin/worth-mentioning-set'});
          break;
        case '支付设置':
          this.$router.push({path:'/admin/pay-set'});
          break;
        case '附件设置':
          this.$router.push({path:'/admin/annex-set'});
          break;
        case '内容过滤设置':
          this.$router.push({path:'/admin/content-filter-set'});
          break;
        case '腾讯云设置':
          this.$router.push({path:'/admin/tencent-cloud-set'});
          break;
        case '后台用户管理':
          this.$router.push({path:'/admin/user-manage-set'});
          break;
        case '后台角色管理':
          this.$router.push({path:'/admin/role-manage-set'});
          break;
      }

      this.getDataStatus();
    },

  },
  created(){
   this.setDataStatus();
  },

  mounted(){

  }

}
