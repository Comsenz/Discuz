/**
 * 后台Index
 */
import webDb from "../../../helpers/webDbHelper";
import appConfig from "../../../../../frame/config/appConfig";
export default {
  data: function() {
    return {
      indexTitle: "管理中心首页", //页面内容标题  /顶部导航下面
      sideTitle: "首页", //左侧菜单标题

      /*菜单列表里的name必须和路由里面的name一致，*/
      navList: [
        {
          id: 0,
          title: "首页",
          name: "home",
          submenu: [
            {
              id: 0,
              title: "管理中心首页",
              name: "controlCenter",
              icon: "iconshouye"
            }
          ]
        },
        {
          id: 1,
          title: "全局",
          name: "global",
          submenu: [
            {
              id: 0,
              title: "站点设置",
              name: "siteSet",
              icon: "iconzhandianshezhi"
            },
            {
              id: 1,
              title: "注册与登录设置",
              name: "signUpSet",
              icon: "iconzhuceshezhi"
            },
            {
              id: 2,
              title: "第三方登录设置",
              name: "worthMentioningSet",
              icon: "icondisanfangdenglushezhi"
            },
            {
              id: 3,
              title: "支付设置",
              name: "paySet",
              icon: "iconzhifushezhi"
            },
            {
              id: 4,
              title: "附件设置",
              name: "annexSet",
              icon: "iconfujianshezhi"
            },
            {
              id: 5,
              title: "水印设置",
              name: "waterMarkSet",
              icon: "iconwatermark"
            },
            {
              id: 6,
              title: "内容过滤设置",
              name: "contentFilteringSet",
              icon: "iconneirongguolvshezhi"
            },
            {
              id: 7,
              title: "腾讯云设置",
              name: "tencentCloudSet",
              icon: "icontengxunyun"
            },
            {
              id: 8,
              title: "通知设置",
              name: "noticeSet",
              icon: "icontongzhi",
              submenu: [
                {
                  id: 81,
                  title: "系统通知",
                  name: "noticeSet",
                  icon: "icontongzhi"
                },
                {
                  id: 82,
                  title: "微信通知",
                  name: "noticeSet",
                  icon: "icontongzhi"
                }
              ]
            },
            {
              id: 9,
              title: "其他服务设置",
              name: "otherServiceSet",
              icon: "iconqitafuwushezhi"
            },

            // {
            //   id:7,
            //   title:'后台用户管理',
            //   name:'adminUserManage',
            //   icon:'iconyonghuguanli'
            // },
            // {
            //   id:8,
            //   title:'后台角色管理',
            //   name:'adminRoleManage',
            //   icon:'iconjiaoseguanli'
            // }
          ]
        },
        {
          id: 2,
          title: "用户",
          name: "user",
          submenu: [
            {
              id: 20,
              title: "用户管理",
              name: "userManage",
              icon: "iconyonghuguanli"
            },
            {
              id: 21,
              title: "用户角色",
              name: "userRol",
              icon: "iconjiaoseguanli"
            },
            {
              id: 22,
              title: "用户审核",
              name: "userReview",
              icon: "iconyonghushenhe"
            }
          ]
        },
        {
          id: 3,
          title: "内容",
          name: "cont",
          submenu: [
            {
              id: 0,
              title: "内容分类",
              name: "contClass",
              icon: "iconneirongfenlei"
            },
            {
              id: 1,
              title: "内容管理",
              name: "contManage",
              icon: "iconneirongguanli",
              submenu: [
                {
                  id: 11,
                  title: "最新主题",
                  name: "contManage",
                  icon: "iconneirongguanli"
                },
                // {
                //   id:12,
                //   title:'搜索',
                //   name:'contManage',
                //   icon:'iconneirongguanli',
                // },
                {
                  id: 13,
                  title: "最新回复",
                  name: "contManage",
                  icon: "iconneirongguanli"
                }
              ]
            },
            {
              id: 2,
              title: "内容审核",
              name: "contReview",
              icon: "iconneirongshenhe",
              submenu: [
                {
                  id: 21,
                  title: "主题审核",
                  name: "contReview",
                  icon: "iconneirongshenhe"
                },
                {
                  id: 22,
                  title: "回复审核",
                  name: "contReview",
                  icon: "iconneirongshenhe"
                }
              ]
            },
            {
              id: 3,
              title: "话题管理",
              name: "topicManagement",
              icon: "icontopicmanagement",
            },
            {
              id: 4,
              title: "举报管理",
              name: "reportManage",
              icon: "iconjubaoguanli",
              submenu: [
                {
                  id: 41,
                  title: "最新举报",
                  name: "reportManage",
                  icon: "iconjubaoguanli"
                },
                {
                  id: 42,
                  title: "已处理记录",
                  name: "reportManage",
                  icon: "iconjubaoguanli"
                }
              ]
            },
            {
              id: 5,
              title: "回收站",
              name: "recycleBin",
              icon: "iconhuishouzhan",
              submenu: [
                {
                  id: 51,
                  title: "主题",
                  name: "recycleBin",
                  icon: "iconhuishouzhan"
                },
                {
                  id: 52,
                  title: "回复",
                  name: "recycleBin",
                  icon: "iconhuishouzhan"
                }
              ]
            },
          ]
        },
        {
          id: 4,
          title: "财务",
          name: "finance",
          submenu: [
            {
              id: 40,
              title: "资金明细",
              name: "fundDetails",
              icon: "iconzijinmingxi"
            },
            {
              id: 41,
              title: "订单记录",
              name: "orderRecord",
              icon: "icondingdanjilu"
            },
            {
              id: 42,
              title: "提现管理",
              name: "withdrawMange",
              icon: "icontixianguanli",
              submenu: [
                {
                  id: 421,
                  title: "提现申请",
                  name: "withdrawMange",
                  icon: "icontixianguanli"
                },
                {
                  id: 422,
                  title: "提现设置",
                  name: "withdrawMange",
                  icon: "icontixianguanli"
                }
              ]
            },
            {
              id: 43,
              title: "财务统计",
              name: "financialStatistics",
              icon: "iconcaiwutongji"
            }
          ]
        }
      ], //导航菜单列表
      navSelect: "", //导航选中

      sideList: [], //侧边菜单
      sideSelect: "", //侧边选中

      sideSubmenu: [], //侧边栏子菜单
      sideSubmenuSelect: "", //侧边栏子菜单选中

      userName: "" //用户名
    };
  },
  methods: {
    // 清空缓存
    clearCache() {
      this.appFetch({
        url: "clearCache",
        method: "delete",
        data: {}
      }).then(data => {
        if (data.errors) {
          if (data.errors[0].detail) {
            this.$message.error(
              data.errors[0].code + "\n" + data.errors[0].detail[0]
            );
          } else {
            this.$message.error(data.errors[0].code);
          }
        } else {
          this.$message({
            message: "缓存清空完毕",
            type: "success"
          });
        }
      })
      .catch(error => {});
    },
    /*
     *  导航菜单点击事件
     * */
    menuClick(item) {
      this.sideTitle = item.title;

      this.navSelect = item.name;

      switch (item.name) {
        case "home":
          this.sideList = this.navList[0].submenu;
          this.sideSelect = this.navList[0].submenu[0].name;
          this.indexTitle = this.navList[0].submenu[0].title;
          this.sideSubmenu =
            this.navList[0].submenu[0].submenu === undefined || null
              ? []
              : this.navList[0].submenu[0].submenu;
          this.$router.push({ path: "/admin/home" });
          break;
        case "global":
          this.sideList = this.navList[1].submenu;
          this.sideSelect = this.navList[1].submenu[0].name;
          this.indexTitle = this.navList[1].submenu[0].title;
          this.sideSubmenu =
            this.navList[0].submenu[0].submenu === undefined || null
              ? []
              : this.navList[0].submenu[0].submenu;
          this.$router.push({ path: "/admin/site-set" });
          break;
        case "user":
          this.sideList = this.navList[2].submenu;
          this.sideSelect = this.navList[2].submenu[0].name;
          this.indexTitle = this.navList[2].submenu[0].title;
          this.sideSubmenu =
            this.navList[2].submenu[0].submenu === undefined || null
              ? []
              : this.navList[0].submenu[0].submenu;
          this.$router.push({ path: "/admin/user-manage" });
          break;
        case "cont":
          this.sideList = this.navList[3].submenu;
          this.sideSelect = this.navList[3].submenu[0].name;
          this.indexTitle = this.navList[3].submenu[0].title;
          this.sideSubmenu =
            this.navList[0].submenu[0].submenu === undefined || null
              ? []
              : this.navList[0].submenu[0].submenu;
          this.$router.push({ path: "/admin/cont-class" });
          break;
        case "finance":
          this.sideList = this.navList[4].submenu;
          this.sideSelect = this.navList[4].submenu[0].name;
          this.indexTitle = this.navList[4].submenu[0].title;
          this.sideSubmenu =
            this.navList[0].submenu[0].submenu === undefined || null
              ? []
              : this.navList[0].submenu[0].submenu;
          this.$router.push({ path: "/admin/fund-details" });
          break;
        default:
          this.sideList = [];
      }
    },

    /*
     *  左侧菜单点击事件
     * */
    sideClick(item) {
      this.sideSelect = item.name;
      this.indexTitle = item.title;

      this.sideSubmenu = [];

      switch (item.name) {
        case "controlCenter":
          this.$router.push({ path: "/admin/home" });
          break;
        case "siteSet":
          this.$router.push({
            path: "/admin/site-set",
            query: { name: "站点设置" }
          });
          break;
        case "signUpSet":
          this.$router.push({ path: "/admin/sign-up-set" });
          break;
        case "worthMentioningSet":
          this.$router.push({ path: "/admin/worth-mentioning-set" });
          break;
        case "paySet":
          this.$router.push({ path: "/admin/pay-set" });
          break;
        case "annexSet":
          this.$router.push({ path: "/admin/annex-set" });
          break;
        case "waterMarkSet":
          this.$router.push({ path: "/admin/water-mark-set" });
          break;
        case "contentFilteringSet":
          this.$router.push({ path: "/admin/content-filter-set" });
          break;
        case "tencentCloudSet":
          this.$router.push({ path: "/admin/tencent-cloud-set" });
          break;
        case "noticeSet":
          this.sideSubmenu = this.navList[1].submenu[8].submenu;
          this.sideSubmenuSelect = this.navList[1].submenu[8].submenu[0].title;
          this.$router.push({ path: "/admin/system-notice" });
          break;
        case "adminUserManage":
          this.$router.push({ path: "/admin/user-manage-set" });
          break;
        case "adminRoleManage":
          this.$router.push({ path: "/admin/role-manage-set" });
          break;

        case "userManage":
          this.$router.push({ path: "/admin/user-manage" });
          break;
        case "userRol":
          this.$router.push({ path: "/admin/user-rol" });
          break;
        case "userReview":
          this.$router.push({ path: "/admin/user-review" });
          break;

        case "contClass":
          this.$router.push({ path: "/admin/cont-class" });
          break;

        case "contManage":
          this.sideSubmenu = this.navList[3].submenu[1].submenu;
          this.sideSubmenuSelect = this.navList[3].submenu[1].submenu[0].title;
          this.$router.push({ path: "/admin/cont-manage" });
          break;
        case "contReview":
          this.sideSubmenu = this.navList[3].submenu[2].submenu;
          this.sideSubmenuSelect = this.navList[3].submenu[2].submenu[0].title;
          this.$router.push({ path: "/admin/cont-review" });
          break;
        case "recycleBin":
          this.sideSubmenu = this.navList[3].submenu[4].submenu;
          this.sideSubmenuSelect = this.navList[3].submenu[4].submenu[0].title;
          this.$router.push({ path: "/admin/recycle-bin" });
          break;
        case "topicManagement":
          this.$router.push({ path: "/admin/topic-management" });
          break;
        case "reportManage": //举报管理
          this.sideSubmenu = this.navList[3].submenu[4].submenu;
          this.sideSubmenuSelect = this.navList[3].submenu[4].submenu[0].title;
          this.$router.push({ path: "/admin/report-manage" });
          break;
        case "fundDetails":
          this.$router.push({ path: "/admin/fund-details" });
          break;
        case "orderRecord":
          this.$router.push({ path: "/admin/order-record" });
          break;
        case "withdrawMange":
          this.sideSubmenu = this.navList[4].submenu[2].submenu;
          this.sideSubmenuSelect = this.navList[4].submenu[2].submenu[0].title;
          this.$router.push({ path: "/admin/withdrawal-application" });
          break;
        case "financialStatistics":
          this.$router.push({ path: "/admin/financial-statistics" });
          break;
        case "otherServiceSet":
          this.$router.push({ path: "/admin/other-service-set" });
          break;
      }
    },

    /*
     *  跳转到站点首页
     * */
    jumpIndex() {
      let Url = "";
      Url = appConfig.baseUrl;

      this.$router.push({
        path: "/admin"
      });
    },

    /*
     *  左侧菜单的子菜单(位置对应：横向导航下面)点击事件
     *  配置点击子菜单后跳转页面
     * */
    sideSubmenuClick(title) {
      switch (title) {
        case "系统通知":
          this.sideSubmenuSelect = title;
          this.$router.push({ path: "/admin/system-notice" });
          break;
        case "微信通知":
          this.sideSubmenuSelect = title;
          this.$router.push({ path: "/admin/wx-notice" });
          break;

        case "最新主题":
          this.sideSubmenuSelect = title;
          this.$router.push({ path: "/admin/cont-manage" });
          break;
        case "最新回复":
          this.sideSubmenuSelect = title;
          this.$router.push({ path: "/admin/latest-reply" });
          break;
        // case '搜索':
        //   this.sideSubmenuSelect = title;
        //   this.$router.push({path:'/admin/cont-manage/search'});
        //   break;
        case "主题审核":
          this.sideSubmenuSelect = title;
          this.$router.push({ path: "/admin/cont-review" });
          break;
        case "回复审核":
          this.sideSubmenuSelect = title;
          this.$router.push({ path: "/admin/reply-review" });
          break;
        case "最新举报": // 举报管理-最新举报
          this.sideSubmenuSelect = title;
          this.$router.push({ path: "/admin/report-manage" });
          break;
        case "已处理记录": // 举报管理-已处理记录
          this.sideSubmenuSelect = title;
          this.$router.push({ path: "/admin/report-processed" });
          break;
        case "主题":
          this.sideSubmenuSelect = title;
          this.$router.push({ path: "/admin/recycle-bin" });
          break;
        case "回复":
          this.sideSubmenuSelect = title;
          this.$router.push({ path: "/admin/recycle-bin-reply" });
          break;
        case "提现申请":
          this.sideSubmenuSelect = title;
          this.$router.push({ path: "/admin/withdrawal-application" });
          break;
        case "提现设置":
          this.sideSubmenuSelect = title;
          this.$router.push({ path: "/admin/withdrawal-setting" });
          break;
        default:
          this.$message.error("没有当前页面，跳转404页面");
          // this.$router.push({path:'/admin/home'});
          console.log("没有当前页面，跳转404页面");
      }
    },

    /*
     *  配置页面刷新后菜单保持选中
     * */
    setDataStatus() {
      //设置页面刷新前状态，通过路由获取

      let attribution = this.$router.history.current.meta.attribution; //导航名字
      let name = this.$router.history.current.meta.name; //子菜单唯一标识符
      let title = this.$router.history.current.meta.title; //子菜单名字

      switch (attribution) {
        case "首页":
          this.navSelect = this.navList[0].name;
          this.indexTitle = title;
          this.sideTitle = attribution;
          this.sideSelect = name;
          this.sideList = this.navList[0].submenu;
          break;
        case "全局":
          this.navSelect = this.navList[1].name;
          this.indexTitle = title;
          this.sideTitle = attribution;
          this.sideSelect = name;
          this.sideList = this.navList[1].submenu;
          break;
        case "用户":
          this.navSelect = this.navList[2].name;
          this.indexTitle = title;
          this.sideTitle = attribution;
          this.sideSelect = name;
          this.sideList = this.navList[2].submenu;
          break;
        case "内容":
          this.navSelect = this.navList[3].name;
          this.indexTitle = title;
          this.sideTitle = attribution;
          this.sideSelect = name;
          this.sideList = this.navList[3].submenu;
          break;
        case "财务":
          this.navSelect = this.navList[4].name;
          this.indexTitle = title;
          this.sideTitle = attribution;
          this.sideSelect = name;
          this.sideList = this.navList[4].submenu;
          break;
        default:
          console.log("获取菜单出错");
          this.$message.error("获取菜单出错");
      }

      /*
       * 获取子菜单别名，对应位置导航下子菜单，刷新后设置子菜单选中状态
       * */
      let sideSubmenu = this.$router.history.current.meta.alias;

      if (sideSubmenu) {
        switch (sideSubmenu) {
          case "系统通知":
            this.sideSubmenu = this.navList[1].submenu[8].submenu;
            this.sideSubmenuSelect = this.navList[1].submenu[8].submenu[0].title;
            break;
          case "微信通知":
            this.sideSubmenu = this.navList[1].submenu[8].submenu;
            this.sideSubmenuSelect = this.navList[1].submenu[8].submenu[1].title;
            break;

          case "最新主题":
            this.sideSubmenu = this.navList[3].submenu[1].submenu;
            this.sideSubmenuSelect = this.navList[3].submenu[1].submenu[0].title;
            break;
          case "最新回复":
            this.sideSubmenu = this.navList[3].submenu[1].submenu;
            this.sideSubmenuSelect = this.navList[3].submenu[1].submenu[1].title;
            break;
          // case "搜索":
          //   this.sideSubmenu = this.navList[3].submenu[1].submenu;
          //   this.sideSubmenuSelect = this.navList[3].submenu[1].submenu[1].title;
          //   break;
          case "主题审核":
            this.sideSubmenu = this.navList[3].submenu[2].submenu;
            this.sideSubmenuSelect = this.navList[3].submenu[2].submenu[0].title;
            break;
          case "回复审核":
            this.sideSubmenu = this.navList[3].submenu[2].submenu;
            this.sideSubmenuSelect = this.navList[3].submenu[2].submenu[1].title;
            break;
          case "最新举报": // 举报管理-最新举报
            this.sideSubmenu = this.navList[3].submenu[4].submenu;
            this.sideSubmenuSelect = this.navList[3].submenu[4].submenu[0].title;
            break;
          case "已处理记录": // 举报管理-已处理记录
            this.sideSubmenu = this.navList[3].submenu[4].submenu;
            this.sideSubmenuSelect = this.navList[3].submenu[4].submenu[1].title;
            break;
          case "主题":
            this.sideSubmenu = this.navList[3].submenu[5].submenu;
            this.sideSubmenuSelect = this.navList[3].submenu[5].submenu[0].title;
            break;
          case "回复":
            this.sideSubmenu = this.navList[3].submenu[5].submenu;
            this.sideSubmenuSelect = this.navList[3].submenu[5].submenu[1].title;
            break;
          case "提现申请":
            this.sideSubmenu = this.navList[4].submenu[2].submenu;
            this.sideSubmenuSelect = this.navList[4].submenu[2].submenu[0].title;
            break;
          case "提现设置":
            this.sideSubmenu = this.navList[4].submenu[2].submenu;
            this.sideSubmenuSelect = this.navList[4].submenu[2].submenu[1].title;
            break;
          default:
            // this.$router.push({path:'/admin/home'});
            this.sideSubmenu = [];
            console.log("当下没有侧边栏子菜单");
            this.$message.error("当下没有侧边栏子菜单");
        }
      }
    },

    quitClick() {
      localStorage.clear();
      this.$router.push({ path: "/admin/login" });
    }
  },
  created() {
    this.setDataStatus();
    this.userName = webDb.getLItem("username");
  },
  watch: {
    $route() {
      this.setDataStatus();
    }
  }
};
