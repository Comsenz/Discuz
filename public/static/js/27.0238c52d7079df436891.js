(window.webpackJsonp=window.webpackJsonp||[]).push([[27,111,112],{"/Zpk":function(t,e,a){"use strict";Object.defineProperty(e,"__esModule",{value:!0});var i=o(a("6NK7")),s=o(a("VVfg"));function o(t){return t&&t.__esModule?t:{default:t}}e.default={data:function(){return{id:1,checked:!0,result:[],checkBoxres:[],imageShow:!1,index:1,themeListResult:[],firstpostImageListResult:[],priview:[],showScreen:[],length:0,indexlist:-1,menuStatus:!1,isWeixin:!1,isPhone:!1,viewportWidth:"",currentUserName:"",userId:""}},props:{themeList:{type:Array},replyTag:{replyTag:!1},isTopShow:{isTopShow:!1},isMoreShow:{isMoreShow:!1},ischeckShow:{ischeckShow:!1}},created:function(){var t=this;this.userId=s.default.getLItem("tokenId"),this.currentUserName=s.default.getLItem("foregroundUser"),this.viewportWidth=window.innerWidth,this.isWeixin=i.default.isWeixin().isWeixin,this.isPhone=i.default.isWeixin().isPhone,this.loadPriviewImgList(),this.forList(),document.addEventListener("click",(function(e){t.$refs.screenDiv;document.contains(e.target)&&(t.indexlist=-1)}))},watch:{themeList:function(t,e){this.themeList=t,this.themeListResult=t,this.loadPriviewImgList(),this.$forceUpdate()},deep:!0},methods:{userArr:function(t){var e=[];return t.forEach((function(t){e.push('<a  href="/home-page/'+t._data.id+'">'+t._data.username+"</a>")})),e.join(",")},forList:function(){},bindScreen:function(t,e){t==this.indexlist?this.indexlist=-1:this.indexlist=t},themeOpera:function(t,e,a,i){var s=new Object;3==e?(s.isEssence=!a,this.themeOpeRequest(t,s,"3",i)):4==e?(s.isSticky=!a,this.themeOpeRequest(t,s,"4",i)):5==e?(s.isDeleted=!0,this.themeOpeRequest(t,s,"5",i)):6==e?a?this.$router.push({path:"/edit-long-text/"+t}):this.$router.push({path:"/edit-topic/"+t}):7==e&&this.$router.push({path:"/reply-to-topic/"+t+"/0"})},themeOpeRequest:function(t,e,a,i){var s=this;this.appFetch({url:"threads",method:"patch",splice:"/"+t,data:{data:{type:"threads",attributes:e}}}).then((function(t){if(t.errors)throw s.$toast.fail(t.errors[0].code),new Error(t.error);"3"==a?(s.essenceStatus=t.readdata._data.isEssence,s.themeList[i]._data.isEssence=s.essenceStatus):"4"==a?(s.stickyStatus=t.readdata._data.isSticky,s.themeList[i]._data.isSticky=s.stickyStatus):"5"==a&&(s.deletedStatus=t.readdata._data.isDeleted,s.themeList.splice(i,1),s.$toast.success("删除成功"))}))},replyOpera:function(t,e,a,i){var s=this,o=new Object;o.isLiked=!a,this.appFetch({url:"posts",method:"patch",splice:"/"+t,data:{data:{type:"posts",attributes:o}}}).then((function(t){if(t.errors)throw s.$toast.fail(t.errors[0].code),new Error(t.error);a?(s.likedStatus=t.readdata._data.isLiked,s.themeList[i].firstPost._data.isLiked=s.likedStatus,s.themeList[i].firstPost.likedUsers.map((function(t,e,a){t._data.id===s.userId&&a.splice(e,1)}))):(s.likedStatus=t.readdata._data.isLiked,s.themeList[i].firstPost._data.isLiked=s.likedStatus,s.themeList[i].firstPost.likedUsers.unshift({_data:{username:s.currentUserName,id:s.userId}}))}))},loadPriviewImgList:function(){if(""==this.themeListResult||null==this.themeListResult)return!1;for(var t=this.themeListResult.length,e=0;e<t;e++){var a=[];if(this.themeListResult[e].firstPost.images)for(var i=0;i<this.themeListResult[e].firstPost.images.length;i++)a.push(this.themeListResult[e].firstPost.images[i]._data.thumbUrl);this.themeListResult[e].firstPost.imageList=a}},imageSwiper:function(t){this.loadPriviewImgList(),this.imageShow=!0},onChange:function(t){this.index=t+1},checkAll:function(){this.$refs.checkboxGroup.toggleAll(!0)},signOutDele:function(){this.$refs.checkboxGroup.toggleAll()},deleteAllClick:function(){this.$emit("deleteAll",this.result)},jumpThemeDet:function(t,e){e?this.$router.push({path:"/details/"+t}):this.$toast.fail("没有权限，请联系站点管理员")},jumpPerDet:function(t){this.$router.push({path:"/home-page/"+t})}},mounted:function(){document.addEventListener("click",this.disappear,!1)},destroyed:function(){document.addEventListener("click",this.disappear,!1)},beforeRouteLeave:function(t,e,a){a()}}},AC1L:function(t,e,a){"use strict";Object.defineProperty(e,"__esModule",{value:!0});var i=d(a("QbLZ")),s=d(a("AoGw")),o=d(a("QiNT")),n=d(a("omtG")),r=d(a("/Zpk")),l=d(a("CFQY"));function d(t){return t&&t.__esModule?t:{default:t}}a("iUmJ"),a("N960"),e.default=(0,i.default)({name:"circleView",components:{Header:n.default,ThemeDet:l.default}},o.default,r.default,s.default)},AoGw:function(t,e,a){"use strict";Object.defineProperty(e,"__esModule",{value:!0});var i=n(a("14Xm")),s=n(a("D3Ub")),o=a("ULRk");function n(t){return t&&t.__esModule?t:{default:t}}e.default={data:function(){return{result:[],showScreen:!1,themeListCon:[],checked:null,pageIndex:1,pageLimit:20,loading:!1,finished:!1,offset:100,isLoading:!1}},created:function(){this.deleteList()},mounted:function(){o.Bus.$emit("setHeader",["标题标题3443453454ee","fasle","false"])},methods:{deleteAllClick:function(t){var e=this;return(0,s.default)(i.default.mark((function a(){var s,o;return i.default.wrap((function(a){for(;;)switch(a.prev=a.next){case 0:for(s=[],o=0;o<t.length;o++)s.push({type:"threads",id:t[o],attributes:{isDeleted:!0}});return a.next=4,e.appFetch({url:"threadsBatch",method:"patch",data:{data:s}}).then((function(t){t.errors?e.$toast.fail(t.errors[0].code):(e.$toast.success("删除成功"),e.pageIndex=1,e.deleteList(!0))}));case 4:case"end":return a.stop()}}),a,e)})))()},deleteList:function(){var t=this,e=arguments.length>0&&void 0!==arguments[0]&&arguments[0];return this.appFetch({url:"threads",method:"get",data:{include:["user,firstPost,lastThreePosts,lastThreePosts.user,firstPost.likedUsers,rewardedUsers"],"filter[isDeleted]":"no","filter[categoryId]":"","page[number]":this.pageIndex,"page[limit]":this.pageLimit}}).then((function(a){if(a.errors)throw t.$toast.fail(a.errors[0].code),new Error(a.error);e&&(t.themeListCon=[]),t.themeListCon=t.themeListCon.concat(a.readdata),t.loading=!1,t.finished=a.readdata.length<t.pageLimit})).catch((function(e){t.loading&&1!==t.pageIndex&&t.pageIndex--,t.loading=!1}))},checkAll:function(t){this.$refs.checkboxGroup.toggleAll(!0)},toggleAll:function(){this.$refs.checkboxGroup.toggleAll()},signOutDele:function(){},addClass:function(t,e){this.current=t;e.currentTarget},bindScreen:function(){this.showScreen=!this.showScreen},hideScreen:function(){this.showScreen=!1},onLoad:function(){this.loading=!0,this.pageIndex++,this.deleteList()},onRefresh:function(){var t=this;this.pageIndex=1,this.deleteList(!0).then((function(e){t.$toast("刷新成功"),t.finished=!1,t.isLoading=!1})).catch((function(e){t.$toast("刷新失败"),t.isLoading=!1}))},headerBack:function(){this.$router.go(-1)}},beforeRouteLeave:function(t,e,a){a()}}},CFQY:function(t,e,a){"use strict";a.r(e);var i=a("gb5o"),s=a("DhNJ");for(var o in s)"default"!==o&&function(t){a.d(e,t,(function(){return s[t]}))}(o);var n=a("KHd+"),r=Object(n.a)(s.default,i.a,i.b,!1,null,null,null);e.default=r.exports},Cc9l:function(t,e,a){"use strict";a.r(e);var i=a("dM1h"),s=a("LrES");for(var o in s)"default"!==o&&function(t){a.d(e,t,(function(){return s[t]}))}(o);var n=a("KHd+"),r=Object(n.a)(s.default,i.a,i.b,!1,null,null,null);e.default=r.exports},DhNJ:function(t,e,a){"use strict";a.r(e);var i=a("xry+"),s=a.n(i);for(var o in i)"default"!==o&&function(t){a.d(e,t,(function(){return i[t]}))}(o);e.default=s.a},Jgvg:function(t,e,a){"use strict";a.r(e);var i=a("pvnC"),s=a.n(i);for(var o in i)"default"!==o&&function(t){a.d(e,t,(function(){return i[t]}))}(o);e.default=s.a},LrES:function(t,e,a){"use strict";a.r(e);var i=a("AC1L"),s=a.n(i);for(var o in i)"default"!==o&&function(t){a.d(e,t,(function(){return i[t]}))}(o);e.default=s.a},QiNT:function(t,e,a){"use strict";Object.defineProperty(e,"__esModule",{value:!0});var i,s=r(a("YEIV")),o=(a("ULRk"),r(a("VVfg"))),n=r(a("6NK7"));function r(t){return t&&t.__esModule?t:{default:t}}e.default={data:function(){var t;return t={headBackShow:!1,oneHeader:!1,twoHeader:!1,threeHeader:!1,fourHeader:!1,isfixNav:!1,isShow:!1,isHeadShow:!1,showHeader:!1,showMask:!1,title:"",navActi:0,perDet:{themeNum:"1222",memberNum:"1222",circleLeader:"站长名称"},avatarUrl:"",mobile:""},(0,s.default)(t,"isfixNav",!1),(0,s.default)(t,"popupShow",!1),(0,s.default)(t,"current",0),(0,s.default)(t,"userDet",[]),(0,s.default)(t,"categories",[]),(0,s.default)(t,"siteInfo",!1),(0,s.default)(t,"username",""),(0,s.default)(t,"isPayVal",""),(0,s.default)(t,"isWeixin",!1),(0,s.default)(t,"isPhone",!1),(0,s.default)(t,"firstCategoriesId",""),(0,s.default)(t,"logo",!1),(0,s.default)(t,"viewportWidth",""),(0,s.default)(t,"userId",""),(0,s.default)(t,"followDet",""),(0,s.default)(t,"followFlag",""),(0,s.default)(t,"intiFollowVal","0"),(0,s.default)(t,"noticeSum",0),(0,s.default)(t,"intiFollowChangeVal","0"),(0,s.default)(t,"oldFollow",!1),(0,s.default)(t,"equalId",!1),(0,s.default)(t,"clickStatus",!0),t},props:{userInfoAvatarUrl:{type:String},userInfoName:{type:String},headFixed:{headFixed:!1},invitePerDet:{invitePerDet:!1},searchIconShow:{searchIconShow:!1},menuIconShow:{menuIconShow:!1},navShow:{navShow:!1},invitationShow:{invitationShow:!1},perDetShow:{perDet:!1},logoShow:{logoShow:!1},followShow:{followShow:!1}},computed:{personUserId:function(){return this.$route.params.userId}},created:function(){this.userId=o.default.getLItem("tokenId"),this.userId==this.personUserId?this.equalId=!0:this.equalId=!1,this.viewportWidth=window.innerWidth,this.isWeixin=n.default.isWeixin().isWeixin,this.isPhone=n.default.isWeixin().isPhone,this.loadCategories(),this.followShow&&this.loadUserFollowInfo(),this.userId&&this.loadUserInfo()},watch:{isfixNav:function(t,e){this.isfixNav=t}},methods:(i={limitWidth:function(){document.getElementById("testNavBar").style.width="640px";var t=window.innerWidth;document.getElementById("testNavBar").style.marginLeft=(t-640)/2+"px"},loadCategories:function(){var t=this;this.appFetch({url:"forum",method:"get",data:{include:["users"]}}).then((function(e){t.siteInfo=e.readdata,e.readdata._data.set_site.site_logo&&(t.logo=e.readdata._data.set_site.site_logo),t.isPayVal=e.readdata._data.set_site.site_mode})),this.navShow&&this.appFetch({url:"categories",method:"get",data:{include:[]}}).then((function(e){t.categories=e.readdata,t.firstCategoriesId=e.readdata[0]._data.id,t.$emit("update",t.firstCategoriesId)}))},loadUserFollowInfo:function(){var t=this;this.appFetch({url:"users",method:"get",splice:"/"+this.personUserId,data:{}}).then((function(e){t.followDet=e.readdata,"1"==e.readdata._data.follow?t.followFlag="已关注":"0"==e.readdata._data.follow?t.followFlag="关注TA":t.followFlag="相互关注",t.intiFollowVal=e.readdata._data.follow}))},loadUserInfo:function(){var t=this;if(!this.userId)return!1;this.appFetch({url:"users",method:"get",splice:"/"+this.userId,data:{}}).then((function(e){e.data.attributes.typeUnreadNotifications.liked||(e.data.attributes.typeUnreadNotifications.liked=0),e.data.attributes.typeUnreadNotifications.replied||(e.data.attributes.typeUnreadNotifications.replied=0),e.data.attributes.typeUnreadNotifications.rewarded||(e.data.attributes.typeUnreadNotifications.rewarded=0),e.data.attributes.typeUnreadNotifications.system||(e.data.attributes.typeUnreadNotifications.system=0),t.noticeSum=e.data.attributes.typeUnreadNotifications.liked+e.data.attributes.typeUnreadNotifications.replied+e.data.attributes.typeUnreadNotifications.rewarded+e.data.attributes.typeUnreadNotifications.system}))},followCli:function(t){if(o.default.getLItem("Authorization")){if(!this.clickStatus)return!1;this.clickStatus=!1;var e=new Object,a="";"1"==t||"2"==t?(e.to_user_id=this.personUserId,a="delete",this.oldFollow=t):(e.to_user_id=this.personUserId,a="post"),this.followRequest(a,e,t)}else o.default.setSItem("beforeVisiting",this.$route.path),this.$router.push({path:"/login-user"})},followRequest:function(t,e,a){var i=this;this.appFetch({url:"follow",method:t,data:{data:{type:"user_follow",attributes:e}}}).then((function(e){if(e.errors)throw i.$toast.fail(e.errors[0].code),new Error(e.error);"delete"==t?(i.intiFollowVal="0",i.followDet._data.fansCount=i.followDet._data.fansCount-1):"1"==i.oldFollow||"0"==i.oldFollow?(i.followDet._data.fansCount=i.followDet._data.fansCount+1,i.intiFollowVal="1"):(i.followDet._data.fansCount=i.followDet._data.fansCount+1,i.intiFollowVal="2"),i.clickStatus=!0}))},backUrl:function(){window.history.go(-1)},showPopup:function(){this.popupShow=!0},categoriesCho:function(t){this.$emit("categoriesChoice",t)},searchJump:function(){this.$router.push({path:"/search"})},handleTabFix:function(){if(this.headFixed)if((window.pageYOffset||document.documentElement.scrollTop||document.body.scrollTop)>document.querySelector("#testNavBar").offsetTop)this.showHeader=!0,this.isfixNav=!0,1!=this.isWeixin&&1!=this.isPhone&&this.limitWidth();else{this.showHeader=!1,this.isfixNav=!1;window.innerWidth;document.getElementById("testNavBar").style.marginLeft="0px"}}},(0,s.default)(i,"backUrl",(function(){window.history.go(-1)})),(0,s.default)(i,"LogOut",(function(){})),(0,s.default)(i,"bindEvent",(function(t){1==t&&this.LogOut()})),i),mounted:function(){window.addEventListener("scroll",this.handleTabFix,!0)},beforeDestroy:function(){window.removeEventListener("scroll",this.handleTabFix)},destroyed:function(){window.removeEventListener("scroll",this.handleTabFix)},beforeRouteLeave:function(t,e,a){window.removeEventListener("scroll",this.handleTabFix),a()}}},dM1h:function(t,e,a){"use strict";var i=function(){var t=this,e=t.$createElement,a=t._self._c||e;return a("div",{staticClass:"circleCon"},[a("van-list",{attrs:{finished:t.finished,offset:t.offset,"finished-text":"没有更多了","immediate-check":!1},on:{load:t.onLoad},model:{value:t.loading,callback:function(e){t.loading=e},expression:"loading"}},[a("van-pull-refresh",{on:{refresh:t.onRefresh},model:{value:t.isLoading,callback:function(e){t.isLoading=e},expression:"isLoading"}},[a("Header",{attrs:{searchIconShow:!0,perDetShow:!0,logoShow:!0,menuIconShow:!0,navShow:"true",headFixed:"true"},on:{click:t.headerBack}}),t._v(" "),a("div",{staticClass:"gap"}),t._v(" "),a("div",{staticClass:"themeTitBox"},[a("span",{staticClass:"themeTit"},[t._v("全部主题")]),t._v(" "),a("div",{staticClass:"screen",on:{click:t.bindScreen}},[a("span",[t._v("筛选")]),t._v(" "),a("span",{staticClass:"icon iconfont icon-down-menu jtGrayB"})])]),t._v(" "),a("div",{staticClass:"memberCheckList"},[a("ThemeDet",{attrs:{themeList:t.themeListCon,isTopShow:!0,isMoreShow:!0,ischeckShow:!0},on:{deleteAll:t.deleteAllClick}}),t._v(" "),a("div",{staticClass:"gap"})],1)],1)],1)],1)},s=[];a.d(e,"a",(function(){return i})),a.d(e,"b",(function(){return s}))},gWtA:function(t,e,a){"use strict";var i=function(){var t=this,e=t.$createElement,a=t._self._c||e;return a("section",[a("van-popup",{staticClass:"sidebarWrap",style:{height:"100%",right:t.isPhone||t.isWeixin?"0":(t.viewportWidth-640)/2+"px"},attrs:{position:"right"},model:{value:t.popupShow,callback:function(e){t.popupShow=e},expression:"popupShow"}},[a("sidebar",{attrs:{isPayVal:t.isPayVal}})],1),t._v(" "),t.$route.meta.oneHeader?a("div",{staticClass:"headerBox"},[a("div",{directives:[{name:"show",rawName:"v-show",value:t.invitePerDet,expression:"invitePerDet"}],staticClass:"invitePerDet aaa"},[t.userInfoAvatarUrl?a("img",{staticClass:"inviteHead",attrs:{src:t.userInfoAvatarUrl,alt:""}}):a("img",{staticClass:"inviteHead",attrs:{src:t.appConfig.staticBaseUrl+"/images/noavatar.gif",alt:""}}),t._v(" "),t.invitePerDet&&t.userInfoName?a("div",{staticClass:"inviteName",model:{value:t.userInfoName,callback:function(e){t.userInfoName=e},expression:"userInfoName"}},[t._v(t._s(t.userInfoName))]):t._e(),t._v(" "),a("p",{directives:[{name:"show",rawName:"v-show",value:t.invitationShow,expression:"invitationShow"}],staticClass:"inviteWo"},[t._v("邀请您加入")]),t._v(" "),t.followShow&&t.followDet?a("div",{staticClass:"followBox"},[a("span",[t._v("关注："+t._s(t.followDet._data.followCount))]),t._v(" "),a("span",[t._v("被关注："+t._s(t.followDet._data.fansCount))]),t._v(" "),t.equalId?t._e():a("div",{staticClass:"followStatus",attrs:{href:"javascript:;"}},["0"==t.intiFollowVal?a("a",{attrs:{href:"javascript:;"},on:{click:function(e){return t.followCli(t.intiFollowVal)}}},[t._v("关注TA")]):"2"==t.intiFollowVal?a("a",{attrs:{href:"javascript:;"},on:{click:function(e){return t.followCli(t.intiFollowVal)}}},[t._v("相互关注")]):"1"==t.intiFollowVal?a("a",{attrs:{href:"javascript:;"},on:{click:function(e){return t.followCli(t.intiFollowVal)}}},[t._v("已关注")]):t._e()])]):t._e()]),t._v(" "),t.searchIconShow||t.menuIconShow?t._e():a("div",{staticClass:"headeGap"}),t._v(" "),t.searchIconShow||t.menuIconShow?a("div",{staticClass:"headOpe"},[a("span",{directives:[{name:"show",rawName:"v-show",value:t.searchIconShow,expression:"searchIconShow"}],staticClass:"icon iconfont icon-search",on:{click:t.searchJump}}),t._v(" "),a("span",{directives:[{name:"show",rawName:"v-show",value:t.menuIconShow,expression:"menuIconShow"}],staticClass:"icon iconfont icon-Shape relative",attrs:{"is-link":""},on:{click:t.showPopup}},[t.noticeSum>0?a("i",{staticClass:"noticeNew"}):t._e()])]):t._e(),t._v(" "),a("div",{directives:[{name:"show",rawName:"v-show",value:t.logoShow,expression:"logoShow"}],staticClass:"logoBox"},[t.logo?a("img",{staticClass:"logo",attrs:{src:t.logo}}):a("img",{staticClass:"logo",attrs:{src:t.appConfig.staticBaseUrl+"/images/logo.png"}})]),t._v(" "),t.siteInfo?a("div",{directives:[{name:"show",rawName:"v-show",value:t.perDetShow,expression:"perDetShow"}],staticClass:"circleDet"},[a("span",[t._v("主题："+t._s(t.siteInfo._data.other.count_threads))]),t._v(" "),a("span",[t._v("成员："+t._s(t.siteInfo._data.other.count_users))]),t._v(" "),t.siteInfo._data.set_site.site_author?a("span",[t._v("站长："+t._s(t.siteInfo._data.set_site.site_author.username))]):a("span",[t._v("站长：无")])]):t._e(),t._v(" "),a("div",{directives:[{name:"show",rawName:"v-show",value:t.navShow,expression:"navShow"}],staticClass:"navBox",class:{fixedNavBar:t.isfixNav},attrs:{id:"testNavBar"}},[a("van-tabs",{model:{value:t.navActi,callback:function(e){t.navActi=e},expression:"navActi"}},[a("van-tab",[a("span",{attrs:{slot:"title"},on:{click:function(e){return t.categoriesCho(0)}},slot:"title"},[t._v("\n              全部\n          ")])]),t._v(" "),t._l(t.categories,(function(e,i){return a("van-tab",{key:i},[a("span",{attrs:{slot:"title"},on:{click:function(a){return t.categoriesCho(e._data.id)}},slot:"title"},[t._v("\n              "+t._s(e._data.name)+"\n          ")])])}))],2)],1)]):t._e()],1)},s=[];a.d(e,"a",(function(){return i})),a.d(e,"b",(function(){return s}))},gb5o:function(t,e,a){"use strict";var i=function(){var t=this,e=t.$createElement,a=t._self._c||e;return a("section",[a("div",[a("van-checkbox-group",{ref:"checkboxGroup",model:{value:t.result,callback:function(e){t.result=e},expression:"result"}},[t._l(t.themeList,(function(e,i){return a("div",{key:i},[a("div",{staticClass:"cirPostCon"},[a("div",{},[a("div",{staticClass:"postTop"},[a("div",{staticClass:"postPer"},[e.user&&e.user._data.avatarUrl?a("img",{staticClass:"postHead",attrs:{src:e.user._data.avatarUrl},on:{click:function(a){return t.jumpPerDet(e.user._data.id)}}}):a("img",{staticClass:"postHead",attrs:{src:t.appConfig.staticBaseUrl+"/images/noavatar.gif"},on:{click:function(a){return t.jumpPerDet(e.user._data.id)}}}),t._v(" "),a("div",{staticClass:"perDet"},[e.user?a("div",{staticClass:"perName",on:{click:function(a){return t.jumpPerDet(e.user._data.id)}}},[t._v(t._s(e.user._data.username))]):a("div",{staticClass:"perName"},[t._v("该用户已被删除")]),t._v(" "),a("div",{staticClass:"postTime"},[t._v(t._s(t.$moment(e._data.createdAt).format("YYYY-MM-DD HH:mm")))])])]),t._v(" "),a("div",{staticClass:"postOpera"},[e._data.isSticky?a("span",{directives:[{name:"show",rawName:"v-show",value:t.isTopShow,expression:"isTopShow"}],staticClass:"icon iconfont icon-top"}):t._e(),t._v(" "),t.isMoreShow&&(e._data.canEssence||e._data.canSticky||e._data.canDelete||e._data.canEdit||e.firstPost._data.canLike)?a("div",{ref:"screenDiv",refInFor:!0,staticClass:"screen",on:{click:function(e){return e.stopPropagation(),t.bindScreen(i,e)}}},[a("div",{staticClass:"moreCli"},[a("span",{staticClass:"icon iconfont icon-more"})]),t._v(" "),a("div",{directives:[{name:"show",rawName:"v-show",value:t.indexlist==i,expression:"indexlist==index"}],staticClass:"themeList"},[e.firstPost._data.canLike&&e.firstPost._data.isLiked?a("a",{attrs:{href:"javascript:;"},on:{click:function(a){return t.replyOpera(e.firstPost._data.id,2,e.firstPost._data.isLiked,i)}}},[t._v("取消点赞")]):t._e(),t._v(" "),e.firstPost._data.canLike&&!e.firstPost._data.isLiked?a("a",{attrs:{href:"javascript:;"},on:{click:function(a){return t.replyOpera(e.firstPost._data.id,2,e.firstPost._data.isLiked,i)}}},[t._v("点赞")]):t._e(),t._v(" "),e._data.canEssence&&e._data.isEssence?a("a",{attrs:{href:"javascript:;"},on:{click:function(a){return t.themeOpera(e._data.id,3,e._data.isEssence,i)}}},[t._v("取消加精")]):t._e(),t._v(" "),e._data.canEssence&&!e._data.isEssence?a("a",{attrs:{href:"javascript:;"},on:{click:function(a){return t.themeOpera(e._data.id,3,e._data.isEssence,i)}}},[t._v("加精")]):t._e(),t._v(" "),e._data.canSticky&&e._data.isSticky?a("a",{attrs:{href:"javascript:;"},on:{click:function(a){return t.themeOpera(e._data.id,4,e._data.isSticky,i)}}},[t._v("取消置顶")]):t._e(),t._v(" "),e._data.canSticky&&!e._data.isSticky?a("a",{attrs:{href:"javascript:;"},on:{click:function(a){return t.themeOpera(e._data.id,4,e._data.isSticky,i)}}},[t._v("置顶")]):t._e(),t._v(" "),e.firstPost._data.canEdit?a("a",{attrs:{href:"javascript:;"},on:{click:function(a){return t.themeOpera(e._data.id,6,e._data.isLongArticle)}}},[t._v("编辑")]):t._e(),t._v(" "),e._data.canReply?a("a",{attrs:{href:"javascript:;"},on:{click:function(a){return t.themeOpera(e._data.id,7)}}},[t._v("回复")]):t._e(),t._v(" "),e._data.canDelete?a("a",{attrs:{href:"javascript:;"},on:{click:function(a){return t.themeOpera(e._data.id,5,"",i)}}},[t._v("删除")]):t._e()])]):t._e()])]),t._v(" "),e.firstPost&&e._data.isLongArticle?a("div",{staticClass:"postContent listPostCon",on:{click:function(a){return t.jumpThemeDet(e._data.id,e._data.canViewPosts)}}},[a("span",{staticClass:"postConTitle"},[t._v(t._s(e._data.title))]),t._v(" "),e._data.isLongArticle?a("span",{staticClass:"icon iconfont icon-longtext"}):t._e()]):e.firstPost&&!e._data.isLongArticle?a("div",{staticClass:"postContent",domProps:{innerHTML:t._s(e.firstPost._data.contentHtml)},on:{click:function(a){return t.jumpThemeDet(e._data.id,e._data.canViewPosts)}}}):t._e(),t._v(" "),e.firstPost.imageList&&e.firstPost.imageList.length>0?a("div",{staticClass:"themeImgBox",on:{click:function(a){return t.jumpThemeDet(e._data.id,e._data.canViewPosts)}}},[a("div",{staticClass:"themeImgList moreImg"},t._l(e.firstPost.imageList,(function(e,i){return i<9?a("van-image",{key:i,staticClass:"themeImgChild",attrs:{fit:"cover","lazy-load":"",src:e}}):t._e()})),1)]):t._e()]),t._v(" "),a("div",{staticClass:"operaBox"},[e.firstPost.likedUsers.length>0||e.rewardedUsers.length>0?a("div",{staticClass:"isrelationGap"}):t._e(),t._v(" "),e.firstPost.likedUsers.length>0?a("div",{staticClass:"likeBox"},[a("span",{staticClass:"icon iconfont icon-praise-after"}),t._v(" "),a("span",{domProps:{innerHTML:t._s(t.userArr(e.firstPost.likedUsers))}}),t._v(" "),e.firstPost._data.likeCount>10?a("i",[t._v(" 等"),a("span",[t._v(t._s(e.firstPost._data.likeCount))]),t._v("个人觉得很赞")]):t._e()]):t._e(),t._v(" "),e.rewardedUsers.length>0?a("div",{staticClass:"reward"},[a("span",{staticClass:"icon iconfont icon-money"}),t._v(" "),a("span",{domProps:{innerHTML:t._s(t.userArr(e.rewardedUsers))}})]):t._e(),t._v(" "),e.lastThreePosts.length>0&&e.firstPost.likedUsers.length>0||e.lastThreePosts.length>0&&e.rewardedUsers.length>0?a("div",{staticClass:"isrelationLine"}):t._e(),t._v(" "),e.lastThreePosts.length>0?a("div",{staticClass:"replyBox"},[t._l(e.lastThreePosts,(function(e,i){return a("div",{key:i,staticClass:"replyCon"},[e.user?a("a",{attrs:{href:"javascript:;"},on:{click:function(a){return t.jumpPerDet(e.user._data.id)}}},[t._v(t._s(e.user._data.username))]):a("a",{attrs:{href:"javascript:;"}},[t._v("该用户已被删除")]),t._v(" "),e._data.replyUserId?a("span",{staticClass:"font9"},[t._v("回复")]):t._e(),t._v(" "),e._data.replyUserId&&e.replyUser?a("a",{attrs:{href:"javascript:;"},on:{click:function(a){return t.jumpPerDet(e.user._data.id)}}},[t._v(t._s(e.replyUser._data.username))]):e._data.replyUserId&&!e.replyUser?a("a",{attrs:{href:"javascript:;"}},[t._v("该用户已被删除")]):t._e(),t._v(" "),a("span",{domProps:{innerHTML:t._s(e._data.contentHtml)}})])})),t._v(" "),e._data.postCount>4?a("a",{staticClass:"allReply",on:{click:function(a){return t.jumpThemeDet(e._data.id,e._data.canViewPosts)}}},[t._v("全部"+t._s(e._data.postCount-1)+"条回复"),a("span",{staticClass:"icon iconfont icon-right-arrow"})]):t._e()],2):t._e()]),t._v(" "),t.ischeckShow?a("van-checkbox",{ref:"checkboxes",refInFor:!0,staticClass:"memberCheck",attrs:{name:e._data.id}}):t._e()],1),t._v(" "),a("div",{staticClass:"gap"})])})),t._v(" "),t.ischeckShow?a("div",{staticClass:"manageFootFixed choFixed",style:{width:t.isPhone||t.isWeixin?"100%":"640px",left:t.isPhone||t.isWeixin?"0":(t.viewportWidth-640)/2+"px"}},[a("a",{attrs:{href:"javascript:;"},on:{click:t.checkAll}},[t._v("全选")]),t._v(" "),a("a",{attrs:{href:"javascript:;"},on:{click:t.signOutDele}},[t._v("取消全选")]),t._v(" "),a("button",{staticClass:"checkSubmit",on:{click:t.deleteAllClick}},[t._v("删除选中")])]):t._e()],2)],1),t._v(" "),a("van-image-preview",{attrs:{images:t.priview},on:{change:t.onChange},scopedSlots:t._u([{key:"index",fn:function(){return[t._v("第"+t._s(t.index)+"页")]},proxy:!0}]),model:{value:t.imageShow,callback:function(e){t.imageShow=e},expression:"imageShow"}})],1)},s=[];a.d(e,"a",(function(){return i})),a.d(e,"b",(function(){return s}))},omtG:function(t,e,a){"use strict";a.r(e);var i=a("gWtA"),s=a("Jgvg");for(var o in s)"default"!==o&&function(t){a.d(e,t,(function(){return s[t]}))}(o);var n=a("KHd+"),r=Object(n.a)(s.default,i.a,i.b,!1,null,null,null);e.default=r.exports},pvnC:function(t,e,a){"use strict";Object.defineProperty(e,"__esModule",{value:!0});var i=n(a("QbLZ")),s=n(a("QiNT")),o=n(a("IsPG"));function n(t){return t&&t.__esModule?t:{default:t}}a("iUmJ"),e.default=(0,i.default)({name:"headerView",components:{Sidebar:o.default}},s.default)},"xry+":function(t,e,a){"use strict";Object.defineProperty(e,"__esModule",{value:!0});var i=o(a("QbLZ")),s=o(a("/Zpk"));function o(t){return t&&t.__esModule?t:{default:t}}a("iUmJ"),a("N960"),e.default=(0,i.default)({name:"themeDetView"},s.default)}}]);