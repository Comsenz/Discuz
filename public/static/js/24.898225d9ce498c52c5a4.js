(window.webpackJsonp=window.webpackJsonp||[]).push([[24,102],{EAgr:function(e,t,i){},Jgvg:function(e,t,i){"use strict";i.r(t);var o=i("pvnC"),s=i.n(o);for(var a in o)"default"!==a&&function(e){i.d(t,e,(function(){return o[e]}))}(a);t.default=s.a},P674:function(e,t,i){"use strict";i.r(t);var o=i("s7by"),s=i.n(o);for(var a in o)"default"!==a&&function(e){i.d(t,e,(function(){return o[e]}))}(a);t.default=s.a},QiNT:function(e,t,i){"use strict";Object.defineProperty(t,"__esModule",{value:!0});var o,s=r(i("YEIV")),a=(i("ULRk"),r(i("VVfg"))),n=r(i("6NK7"));function r(e){return e&&e.__esModule?e:{default:e}}t.default={data:function(){var e;return e={headBackShow:!1,oneHeader:!1,twoHeader:!1,threeHeader:!1,fourHeader:!1,isfixNav:!1,isShow:!1,isHeadShow:!1,showHeader:!1,showMask:!1,title:"",navActi:0,perDet:{themeNum:"1222",memberNum:"1222",circleLeader:"站长名称"},avatarUrl:"",mobile:""},(0,s.default)(e,"isfixNav",!1),(0,s.default)(e,"popupShow",!1),(0,s.default)(e,"current",0),(0,s.default)(e,"userDet",[]),(0,s.default)(e,"categories",[]),(0,s.default)(e,"siteInfo",!1),(0,s.default)(e,"username",""),(0,s.default)(e,"isPayVal",""),(0,s.default)(e,"isWeixin",!1),(0,s.default)(e,"isPhone",!1),(0,s.default)(e,"firstCategoriesId",""),(0,s.default)(e,"logo",!1),(0,s.default)(e,"viewportWidth",""),(0,s.default)(e,"userId",""),(0,s.default)(e,"followDet",""),(0,s.default)(e,"followFlag",""),(0,s.default)(e,"intiFollowVal","0"),(0,s.default)(e,"noticeSum",0),e},props:{userInfoAvatarUrl:{type:String},userInfoName:{type:String},headFixed:{headFixed:!1},invitePerDet:{invitePerDet:!1},searchIconShow:{searchIconShow:!1},menuIconShow:{menuIconShow:!1},navShow:{navShow:!1},invitationShow:{invitationShow:!1},perDetShow:{perDet:!1},logoShow:{logoShow:!1},followShow:{logoShow:!1}},computed:{personUserId:function(){return this.$route.params.userId}},created:function(){this.userId=a.default.getLItem("tokenId"),console.log(this.userId,"登录用户id"),console.log(this.personUserId,"用户主页获取到的参数id"),this.viewportWidth=window.innerWidth,this.isWeixin=n.default.isWeixin().isWeixin,this.isPhone=n.default.isWeixin().isPhone,this.loadCategories(),this.followShow&&this.loadUserFollowInfo(),this.loadUserInfo()},watch:{isfixNav:function(e,t){this.isfixNav=e}},methods:(o={limitWidth:function(){document.getElementById("testNavBar").style.width="640px";var e=window.innerWidth;document.getElementById("testNavBar").style.marginLeft=(e-640)/2+"px"},loadCategories:function(){var e=this;this.appFetch({url:"forum",method:"get",data:{include:["users"]}}).then((function(t){console.log(t.readdata._data.other),console.log("-------------------"),e.siteInfo=t.readdata,t.readdata._data.set_site.site_logo&&(e.logo=t.readdata._data.set_site.site_logo),e.isPayVal=t.readdata._data.set_site.site_mode})),this.appFetch({url:"categories",method:"get",data:{include:[]}}).then((function(t){console.log("2222"),console.log(t),e.categories=t.readdata,e.firstCategoriesId=t.readdata[0]._data.id,console.log(e.firstCategoriesId),e.$emit("update",e.firstCategoriesId),console.log("3456")}))},loadUserFollowInfo:function(){var e=this;this.appFetch({url:"users",method:"get",splice:"/"+this.personUserId,data:{}}).then((function(t){console.log(t.readdata._data.follow,"00000000000&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&——————————————————————————————————————————"),e.followDet=t.readdata,console.log(e.followDet,"结果数据·······"),"1"==t.readdata._data.follow?(console.log("已关注"),e.followFlag="已关注"):"0"==t.readdata._data.follow&&(console.log("关注TA"),e.followFlag="关注TA")}))},loadUserInfo:function(){var e=this;console.log(this.personUserId,"访问Id"),this.appFetch({url:"users",method:"get",splice:"/"+this.userId,data:{}}).then((function(t){console.log(t,"00000000000&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&——————————————————————————————————————————"),t.data.attributes.typeUnreadNotifications.liked||(t.data.attributes.typeUnreadNotifications.liked=0),t.data.attributes.typeUnreadNotifications.replied||(t.data.attributes.typeUnreadNotifications.replied=0),t.data.attributes.typeUnreadNotifications.rewarded||(t.data.attributes.typeUnreadNotifications.rewarded=0),t.data.attributes.typeUnreadNotifications.system||(t.data.attributes.typeUnreadNotifications.system=0),e.noticeSum=t.data.attributes.typeUnreadNotifications.liked+t.data.attributes.typeUnreadNotifications.replied+t.data.attributes.typeUnreadNotifications.rewarded+t.data.attributes.typeUnreadNotifications.system,console.log(t.data.attributes.typeUnreadNotifications.liked,t.data.attributes.typeUnreadNotifications.replied,t.data.attributes.typeUnreadNotifications.rewarded,t.data.attributes.typeUnreadNotifications.system,"和")}))},followCli:function(e){console.log("参数",e);var t=new Object,i="";"0"==e?(console.log("未关注"),t.to_user_id=this.personUserId,i="post",this.intiFollowVal="1",console.log(this.intiFollowVal,"修改")):(console.log("已关注"),t.from_user_id=this.userId,t.to_user_id=this.personUserId,i="delete",this.intiFollowVal="0"),console.log(t,"33333333-----"),this.followRequest(i,t)},followRequest:function(e,t){var i=this;this.appFetch({url:"follow",method:e,data:{data:{type:"user_follow",attributes:t}}}).then((function(t){if(console.log(t,"987654"),t.errors)throw i.$toast.fail(t.errors[0].code),new Error(t.error);i.followFlag="delete"==e?"关注TA":"已关注"}))},backUrl:function(){window.history.go(-1)},showPopup:function(){this.popupShow=!0},categoriesCho:function(e){this.$emit("categoriesChoice",e)},searchJump:function(){this.$router.push({path:"/search"})},handleTabFix:function(){if(this.headFixed)if((window.pageYOffset||document.documentElement.scrollTop||document.body.scrollTop)>document.querySelector("#testNavBar").offsetTop)this.showHeader=!0,this.isfixNav=!0,1!=this.isWeixin&&1!=this.isPhone&&this.limitWidth();else{this.showHeader=!1,this.isfixNav=!1;window.innerWidth;document.getElementById("testNavBar").style.marginLeft="0px"}}},(0,s.default)(o,"backUrl",(function(){window.history.go(-1)})),(0,s.default)(o,"LogOut",(function(){console.log("测试")})),(0,s.default)(o,"bindEvent",(function(e){1==e&&this.LogOut()})),o),mounted:function(){window.addEventListener("scroll",this.handleTabFix,!0)},beforeDestroy:function(){window.removeEventListener("scroll",this.handleTabFix,!0)},destroyed:function(){window.removeEventListener("scroll",this.handleTabFix,!0)},beforeRouteLeave:function(e,t,i){window.removeEventListener("scroll",this.handleTabFix,!0),i()}}},RIP2:function(e,t,i){"use strict";var o=function(){var e=this,t=e.$createElement,i=e._self._c||t;return i("section",[i("van-popup",{staticClass:"sidebarWrap",style:{height:"100%",right:e.isPhone||e.isWeixin?"0":(e.viewportWidth-640)/2+"px"},attrs:{position:"right"},model:{value:e.popupShow,callback:function(t){e.popupShow=t},expression:"popupShow"}},[i("sidebar",{attrs:{isPayVal:e.isPayVal}})],1),e._v(" "),e.$route.meta.oneHeader?i("div",{staticClass:"headerBox"},[i("div",{directives:[{name:"show",rawName:"v-show",value:e.invitePerDet,expression:"invitePerDet"}],staticClass:"invitePerDet aaa"},[e.userInfoAvatarUrl?i("img",{staticClass:"inviteHead",attrs:{src:e.userInfoAvatarUrl,alt:""}}):i("img",{staticClass:"inviteHead",attrs:{src:e.appConfig.staticBaseUrl+"/images/noavatar.gif",alt:"ssss"}}),e._v(" "),e.invitePerDet&&e.userInfoName?i("div",{staticClass:"inviteName",model:{value:e.userInfoName,callback:function(t){e.userInfoName=t},expression:"userInfoName"}},[e._v(e._s(e.userInfoName))]):i("div",{staticClass:"inviteName"},[e._v("该用户已被删除")]),e._v(" "),i("p",{directives:[{name:"show",rawName:"v-show",value:e.invitationShow,expression:"invitationShow"}],staticClass:"inviteWo"},[e._v("邀请您加入")]),e._v(" "),e.followShow?i("div",{staticClass:"followBox"},[i("span",[e._v("关注："+e._s(e.followDet._data.followCount))]),e._v(" "),i("span",[e._v("被关注："+e._s(e.followDet._data.fansCount))]),e._v(" "),e.userId!=e.personUserId?i("a",{staticClass:"followOne",attrs:{href:"javascript:;",id:"followCli"},on:{click:function(t){return e.followCli(e.intiFollowVal)}}},[e._v(e._s(e.followFlag))]):e._e()]):e._e()]),e._v(" "),e.searchIconShow||e.menuIconShow?e._e():i("div",{staticClass:"headeGap"}),e._v(" "),e.searchIconShow||e.menuIconShow?i("div",{staticClass:"headOpe"},[i("span",{directives:[{name:"show",rawName:"v-show",value:e.searchIconShow,expression:"searchIconShow"}],staticClass:"icon iconfont icon-search",on:{click:e.searchJump}}),e._v(" "),i("span",{directives:[{name:"show",rawName:"v-show",value:e.menuIconShow,expression:"menuIconShow"}],staticClass:"icon iconfont icon-Shape relative",attrs:{"is-link":""},on:{click:e.showPopup}},[e.noticeSum>0?i("i",{staticClass:"noticeNew"}):e._e()])]):e._e(),e._v(" "),i("div",{directives:[{name:"show",rawName:"v-show",value:e.logoShow,expression:"logoShow"}],staticClass:"logoBox"},[e.logo?i("img",{staticClass:"logo",attrs:{src:e.logo}}):i("img",{staticClass:"logo",attrs:{src:e.appConfig.staticBaseUrl+"/images/logo.png"}})]),e._v(" "),e.siteInfo?i("div",{directives:[{name:"show",rawName:"v-show",value:e.perDetShow,expression:"perDetShow"}],staticClass:"circleDet"},[i("span",[e._v("主题："+e._s(e.siteInfo._data.other.count_threads))]),e._v(" "),i("span",[e._v("成员："+e._s(e.siteInfo._data.other.count_users))]),e._v(" "),e.siteInfo._data.set_site.site_author?i("span",[e._v("站长："+e._s(e.siteInfo._data.set_site.site_author.username))]):i("span",[e._v("站长：无")])]):e._e(),e._v(" "),i("div",{directives:[{name:"show",rawName:"v-show",value:e.navShow,expression:"navShow"}],staticClass:"navBox",class:{fixedNavBar:e.isfixNav},attrs:{id:"testNavBar"}},[i("van-tabs",{model:{value:e.navActi,callback:function(t){e.navActi=t},expression:"navActi"}},[i("van-tab",[i("span",{attrs:{slot:"title"},on:{click:function(t){return e.categoriesCho(0)}},slot:"title"},[e._v("\n              全部\n          ")])]),e._v(" "),e._l(e.categories,(function(t,o){return i("van-tab",{key:o},[i("span",{attrs:{slot:"title"},on:{click:function(i){return e.categoriesCho(t._data.id)}},slot:"title"},[e._v("\n              "+e._s(t._data.name)+"\n          ")])])}))],2)],1)]):e._e()],1)},s=[];i.d(t,"a",(function(){return o})),i.d(t,"b",(function(){return s}))},SO9L:function(e,t,i){"use strict";Object.defineProperty(t,"__esModule",{value:!0});var o=a(i("VVfg")),s=a(i("6NK7"));function a(e){return e&&e.__esModule?e:{default:e}}t.default={data:function(){return{showScreen:!1,loginBtnFix:!1,loginHide:!1,fourHeader:!0,isWx:"1",themeChoList:[{typeWo:"全部主题",type:"1",themeType:"allThemes"},{typeWo:"精华主题",type:"2",themeType:"isEssence"},{typeWo:"关注用户的",type:"3",themeType:"fromUserId"}],themeListCon:[],themeNavListCon:[],currentData:{},replyTagShow:!1,firstpostImageListCon:[],loading:!1,finished:!1,isLoading:!1,pageIndex:0,pageLimit:20,offset:100,canEdit:!0,firstCategoriesId:"",Initialization:!1,searchStatus:!1,menuStatus:!1,categoryId:!1,filterInfo:{filterCondition:"allThemes",typeWo:"全部主题"},canCreateThread:"",canViewThreads:"",nullTip:!1,nullWord:"",allowRegister:"",loginWord:"登录 / 注册",isWeixin:!1,isPhone:!1,viewportWidth:""}},created:function(){this.getInfo(),this.load(),this.isWeixin=s.default.isWeixin().isWeixin,this.isPhone=s.default.isWeixin().isPhone,this.viewportWidth=window.innerWidth,this.onLoad(),this.detailIf()},methods:{receive:function(e){this.firstCategoriesId=e},getInfo:function(){var e=this;this.appFetch({url:"forum",method:"get",data:{include:["users"]}}).then((function(t){if(t.errors)throw e.$toast.fail(t.errors[0].code),new Error(t.error);console.log("44443"),console.log(t),e.siteInfo=t.readdata,e.canCreateThread=t.readdata._data.other.can_create_thread,e.canViewThreads=t.readdata._data.other.can_view_threads,e.allowRegister=t.readdata._data.set_reg.register_close,e.allowRegister||(e.loginWord="登录"),console.log(t.readdata._data.set_site.site_mode+"请求"),e.sitePrice=t.readdata._data.set_site.site_price,e.isPayVal=t.readdata._data.set_site.site_mode,null!=e.isPayVal&&""!=e.isPayVal&&(e.isPayVal=t.readdata._data.set_site.site_mode,console.log("可以访问"))}))},detailIf:function(){o.default.getLItem("Authorization")?(console.log("已登录"),this.loginBtnFix=!1,this.loginHide=!0,this.canEdit=!0,this.searchStatus=!0,this.menuStatus=!0,this.canEdit):(console.log("未登录"),this.loginBtnFix=!0,this.loginHide=!1,this.canEdit=!1)},load:function(){var e=this.appCommonH.isWeixin().isWeixin;return this.isWx=1==e?2:1,this.isWx},loadThemeList:function(e,t){var i=this;console.log(e,"123~~~~");var s=o.default.getLItem("tokenId");this.categoryId=t||0;var a={"filter[isEssence]":"yes","filter[fromUserId]":s,"filter[categoryId]":this.categoryId,"filter[isApproved]":1,"filter[isDeleted]":"no",include:["user","firstPost","firstPost.images","lastThreePosts","lastThreePosts.user","lastThreePosts.replyUser","firstPost.likedUsers","rewardedUsers"],"page[number]":this.pageIndex,"page[limit]":this.pageLimit};return 0==t&&delete a["filter[categoryId]"],"isEssence"!==e&&delete a["filter[isEssence]"],"fromUserId"!==e&&delete a["filter[fromUserId]"],console.log(a,"data数据"),this.appFetch({url:"threads",method:"get",data:a}).then((function(e){if(console.log(e),console.log("3443431111"),e.errors){if("permission_denied"!=e.rawData[0].code)throw i.$toast.fail(e.errors[0].code),new Error(e.error);i.nullTip=!0,i.nullWord=e.errors[0].code}else console.log("正确请求"),i.canViewThreads?(i.themeListCon.length<0&&(i.nullTip=!0),i.themeListCon=i.themeListCon.concat(e.readdata),console.log(i.themeListCon),console.log("66544"),i.loading=!1,i.finished=e.readdata.length<i.pageLimit):(i.nullTip=!0,i.nullWord=e.errors[0].code)})).catch((function(e){i.loading&&1!==i.pageIndex&&i.pageIndex--,i.loading=!1}))},pushImgArray:function(){},footFix:function(){var e=window.pageYOffset||document.documentElement.scrollTop||document.body.scrollTop,t=document.querySelector("#testNavBar").offsetTop;1==this.loginBtnFix&&(this.loginHide=!0,this.loginHide=e>t)},choTheme:function(e){console.log(e),console.log("筛选"),this.filterInfo.typeWo="isEssence"===e?"精华主题":"fromUserId"===e?"关注用户的":"全部主题",this.filterInfo.filterCondition=e,console.log(this.filterInfo.filterCondition,"类型"),this.pageIndex=1,this.themeListCon=[],this.loadThemeList(this.filterInfo.filterCondition,this.categoryId)},categoriesChoice:function(e){this.pageIndex=1,this.themeListCon=[],this.loadThemeList(this.filterInfo.filterCondition,e)},loginJump:function(e){var t=this,i=this.load();this.$router.push({path:"wechat"}),1==i?this.$router.push({path:"login-user"}):2==i&&this.appFetch({url:"weixin",method:"get",data:{}}).then((function(e){if(e.errors)throw t.$toast.fail(e.errors[0].code),new Error(e.error);t.$router.push({path:"wechat"})}))},postTopic:function(){this.canCreateThread?this.$router.push({path:"/post-topic"}):this.$toast.fail("没有权限，请联系站点管理员")},addClass:function(e,t){this.current=e;t.currentTarget},bindScreen:function(){this.showScreen=!this.showScreen},listenEvt:function(e){this.$refs.screenBox.contains(e.target)||(this.showScreen=!1)},hideScreen:function(){this.showScreen=!1},onLoad:function(){this.loading=!0,this.pageIndex++,this.loadThemeList(this.filterCondition,this.categoryId)},onRefresh:function(){var e=this;this.pageIndex=1,this.themeListCon=[],this.nullTip=!1,this.loadThemeList(this.filterCondition,this.categoryId).then((function(){e.$toast("刷新成功"),e.finished=!1,e.isLoading=!1})).catch((function(t){e.$toast("刷新失败"),e.isLoading=!1}))}},mounted:function(){window.addEventListener("scroll",this.footFix,!0),document.addEventListener("click",this.listenEvt,!1)},destroyed:function(){window.removeEventListener("scroll",this.footFix,!0),document.removeEventListener("click",this.listenEvt,!1)},beforeRouteLeave:function(e,t,i){window.removeEventListener("scroll",this.footFix,!0),document.removeEventListener("click",this.listenEvt,!1),i()}}},bYhK:function(e,t,i){"use strict";var o=function(){var e=this,t=e.$createElement,i=e._self._c||t;return i("div",{staticClass:"circleCon"},[i("van-list",{attrs:{finished:e.finished,offset:e.offset,"finished-text":1===e.pageIndex&&0===e.themeListCon.length?"暂无数据":"没有更多了","immediate-check":!1},on:{load:e.onLoad},model:{value:e.loading,callback:function(t){e.loading=t},expression:"loading"}},[i("van-pull-refresh",{on:{refresh:e.onRefresh},model:{value:e.isLoading,callback:function(t){e.isLoading=t},expression:"isLoading"}},[i("Header",{attrs:{searchIconShow:e.searchStatus,perDetShow:!0,logoShow:!0,menuIconShow:e.menuStatus,navShow:!0,invitePerDet:!1,headFixed:!0},on:{categoriesChoice:e.categoriesChoice,update:e.receive}}),e._v(" "),i("div",{staticClass:"padB"}),e._v(" "),i("div",{staticClass:"gap"}),e._v(" "),i("div",{staticClass:"themeTitBox"},[i("span",{staticClass:"themeTit"},[e._v(e._s(e.filterInfo.typeWo))]),e._v(" "),i("div",{ref:"screenBox",staticClass:"screen",on:{click:e.bindScreen}},[i("span",[e._v("筛选")]),e._v(" "),i("span",{staticClass:"icon iconfont icon-down-menu jtGrayB"}),e._v(" "),e.showScreen?i("div",{staticClass:"themeList"},e._l(e.themeChoList,(function(t,o){return i("a",{key:o,attrs:{href:"javascript:;"},on:{click:function(i){return e.choTheme(t.themeType)}}},[e._v(e._s(t.typeWo))])})),0):e._e()])]),e._v(" "),e.themeListCon?i("div",[i("ThemeDet",{attrs:{themeList:e.themeListCon,isTopShow:!0,isMoreShow:!0},on:{"update:themeList":function(t){e.themeListCon=t},"update:theme-list":function(t){e.themeListCon=t},changeStatus:e.loadThemeList}})],1):e._e()],1)],1),e._v(" "),e.nullTip?i("div",{staticClass:"nullTip"},[i("van-icon",{staticClass:"nullIcon",attrs:{name:"warning-o",size:"1.8rem"}}),e._v(" "),i("p",{staticClass:"nullWord"},[e._v(e._s(e.nullWord))])],1):e._e(),e._v(" "),e.loginBtnFix?i("van-button",{staticClass:"loginBtnFix",class:{hide:e.loginHide},attrs:{type:"primary"},on:{click:function(t){return e.loginJump(1)}}},[e._v(e._s(e.loginWord))]):e._e(),e._v(" "),e.canEdit?i("div",{staticClass:"fixedEdit",style:{right:e.isPhone||e.isWeixin?"15px":(e.viewportWidth-640)/2+15+"px"},attrs:{id:"fixedEdit"},on:{click:e.postTopic}},[i("span",{staticClass:"icon iconfont icon-publish"})]):e._e()],1)},s=[];i.d(t,"a",(function(){return o})),i.d(t,"b",(function(){return s}))},omtG:function(e,t,i){"use strict";i.r(t);var o=i("RIP2"),s=i("Jgvg");for(var a in s)"default"!==a&&function(e){i.d(t,e,(function(){return s[e]}))}(a);var n=i("KHd+"),r=Object(n.a)(s.default,o.a,o.b,!1,null,null,null);t.default=r.exports},pvnC:function(e,t,i){"use strict";Object.defineProperty(t,"__esModule",{value:!0});var o=n(i("QbLZ")),s=n(i("QiNT")),a=n(i("IsPG"));function n(e){return e&&e.__esModule?e:{default:e}}i("iUmJ"),t.default=(0,o.default)({name:"headerView",components:{Sidebar:a.default}},s.default)},s7by:function(e,t,i){"use strict";Object.defineProperty(t,"__esModule",{value:!0});var o=d(i("QbLZ")),s=d(i("SO9L")),a=d(i("QiNT")),n=d(i("omtG")),r=d(i("/Zpk")),l=d(i("CFQY"));function d(e){return e&&e.__esModule?e:{default:e}}i("iUmJ"),i("N960"),t.default=(0,o.default)({name:"circleView",components:{Header:n.default,ThemeDet:l.default}},a.default,r.default,s.default)},uxo0:function(e,t,i){"use strict";var o=i("EAgr");i.n(o).a},vuqY:function(e,t,i){"use strict";i.r(t);var o=i("bYhK"),s=i("P674");for(var a in s)"default"!==a&&function(e){i.d(t,e,(function(){return s[e]}))}(a);i("uxo0");var n=i("KHd+"),r=Object(n.a)(s.default,o.a,o.b,!1,null,"7f86d570",null);t.default=r.exports}}]);