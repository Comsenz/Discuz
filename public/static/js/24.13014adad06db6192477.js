(window.webpackJsonp=window.webpackJsonp||[]).push([[24,79,80],{"/Zpk":function(e,t,s){"use strict";Object.defineProperty(t,"__esModule",{value:!0}),t.default={data:function(){return{id:1,checked:!0,result:[],checkBoxres:[],imageShow:!1,index:1,themeListResult:[],firstpostImageListResult:[],priview:[],showScreen:[],length:0,menuStatus:!1}},props:{themeList:{type:Array},replyTag:{replyTag:!1},isTopShow:{isTopShow:!1},isMoreShow:{isMoreShow:!1},ischeckShow:{ischeckShow:!1}},created:function(){this.loadPriviewImgList(),this.forList()},beforeDestroy:function(){},watch:{themeList:function(e,t){this.themeList=e,this.themeListResult=e,this.loadPriviewImgList(),this.$forceUpdate()},deep:!0},methods:{userArr:function(e){var t=[];return e.forEach((function(e){t.push(e._data.username)})),t.join(",")},forList:function(){for(var e=this.themeList.length,t=0;t<e;t++)this.showScreen.push(!1)},bindScreen:function(e){var t=this;console.log(e);this.showScreen.forEach((function(e){console.log(t.showScreen)})),this.showScreen.splice(e,1,!this.showScreen[e])},themeOpera:function(e,t,s){var i=new Object;2==t?(console.log(s),this.themeOpeRequest(e,i,s),i.isEssence=s):3==t?(i.isSticky=s,this.themeOpeRequest(e,i,s)):4==t?(i.isDeleted=!0,this.themeOpeRequest(e,i)):this.$router.push({path:"/edit-topic/"+this.themeId})},themeOpeRequest:function(e,t,s){var i=this;this.appFetch({url:"threads",method:"patch",splice:"/"+e,data:{data:{type:"threads",attributes:t}}}).then((function(e){console.log(e),console.log("888"),i.$emit("changeStatus",!0)}))},replyOpera:function(e,t,s,i){var a=this,o=new Object;o.isLiked=i;var n="posts/"+e;this.appFetch({url:n,method:"patch",data:{data:{type:"posts",attributes:o}}}).then((function(e){a.$message("修改成功"),a.$emit("changeStatus",!0)}))},loadPriviewImgList:function(){var e=this.themeListResult.length;if(""==this.themeListResult||null==this.themeListResult)return!1;for(var t=0;t<e;t++){var s=[];if(this.themeListResult[t].firstPost.images)for(var i=0;i<this.themeListResult[t].firstPost.images.length;i++)s.push(this.themeListResult[t].firstPost.images[i]._data.thumbUrl);this.themeListResult[t].firstPost.imageList=s}},imageSwiper:function(e){this.loadPriviewImgList(),this.imageShow=!0,console.log(this.priview)},onChange:function(e){this.index=e+1},checkAll:function(){console.log(this.$refs),this.$refs.checkboxGroup.toggleAll(!0)},signOutDele:function(){this.$refs.checkboxGroup.toggleAll()},deleteAllClick:function(){this.$emit("deleteAll",this.result)},jumpThemeDet:function(e){this.$router.push({path:"details/"+e})},jumpPerDet:function(e){this.$router.push({path:"/home-page/"+e})}},beforeRouteLeave:function(e,t,s){}}},"2xe9":function(e,t,s){"use strict";var i=function(){var e=this,t=e.$createElement,s=e._self._c||t;return s("section",[s("div",[s("van-checkbox-group",{ref:"checkboxGroup",model:{value:e.result,callback:function(t){e.result=t},expression:"result"}},[e._l(e.themeList,(function(t,i){return s("div",{key:i},[s("div",{staticClass:"cirPostCon"},[s("div",{},[s("div",{staticClass:"postTop"},[s("div",{staticClass:"postPer"},[t.user._data.avatarUrl?s("img",{staticClass:"postHead",attrs:{src:t.user._data.avatarUrl}}):s("img",{staticClass:"postHead",attrs:{src:e.appConfig.staticBaseUrl+"/images/noavatar.gif"}}),e._v(" "),s("div",{staticClass:"perDet"},[t.user?s("div",{staticClass:"perName"},[e._v(e._s(t.user._data.username))]):s("div",{staticClass:"perName"},[e._v("该用户已被删除")]),e._v(" "),s("div",{staticClass:"postTime"},[e._v(e._s(e.$moment(t._data.createdAt).format("YYYY-MM-DD HH:mm")))])])]),e._v(" "),s("div",{staticClass:"postOpera"},[t._data.isSticky?s("span",{directives:[{name:"show",rawName:"v-show",value:e.isTopShow,expression:"isTopShow"}],staticClass:"icon iconfont icon-top"}):e._e(),e._v(" "),t._data.canEssence||t._data.canSticky||t._data.canDelete||t._data.canEdit?s("div",{staticClass:"screen",on:{click:function(t){return e.bindScreen(i)}}},[s("div",{staticClass:"moreCli"},[s("span",{staticClass:"icon iconfont icon-more"})]),e._v(" "),s("div",{directives:[{name:"show",rawName:"v-show",value:e.showScreen[i],expression:"showScreen[index]"}],staticClass:"themeList"},[t.firstPost._data.canLike&&t.firstPost._data.isLiked?s("a",{attrs:{href:"javascript:;"},on:{click:function(s){return e.replyOpera(t.firstPost._data.id,2,t.firstPost._data.isLiked,!1)}}},[e._v("取消点赞")]):e._e(),e._v(" "),t.firstPost._data.canLike&&!t.firstPost._data.isLiked?s("a",{attrs:{href:"javascript:;"},on:{click:function(s){return e.replyOpera(t.firstPost._data.id,2,t.firstPost._data.isLiked,!0)}}},[e._v("点赞")]):e._e(),e._v(" "),t._data.canEssence&&t._data.isEssence?s("a",{attrs:{href:"javascript:;"},on:{click:function(s){return e.themeOpera(t._data.id,2,!1)}}},[e._v("取消加精")]):e._e(),e._v(" "),t._data.canEssence&&!t._data.isEssence?s("a",{attrs:{href:"javascript:;"},on:{click:function(s){return e.themeOpera(t._data.id,2,!0)}}},[e._v("加精")]):e._e(),e._v(" "),t._data.canSticky&&t._data.isSticky?s("a",{attrs:{href:"javascript:;"},on:{click:function(s){return e.themeOpera(t._data.id,3,!1)}}},[e._v("取消置顶")]):e._e(),e._v(" "),t._data.canSticky&&!t._data.isSticky?s("a",{attrs:{href:"javascript:;"},on:{click:function(s){return e.themeOpera(t._data.id,3,!0)}}},[e._v("置顶")]):e._e(),e._v(" "),t._data.canDelete?s("a",{attrs:{href:"javascript:;"},on:{click:function(s){return e.themeOpera(t._data.id,4)}}},[e._v("删除")]):e._e()])]):e._e()])]),e._v(" "),t.firstPost?s("div",{staticClass:"postContent"},[s("a",{domProps:{innerHTML:e._s(t.firstPost._data.contentHtml)},on:{click:function(s){return e.jumpThemeDet(t._data.id)}}})]):e._e(),e._v(" "),t.firstPost.imageList&&t.firstPost.imageList.length>0?s("div",{staticClass:"themeImgBox"},[s("div",{staticClass:"themeImgList moreImg"},e._l(t.firstPost.imageList,(function(e,t){return s("van-image",{staticClass:"themeImgChild",attrs:{fit:"cover",width:"113px",height:"113px","lazy-load":"",src:e}})})),1)]):e._e()]),e._v(" "),s("div",{staticClass:"operaBox"},[t.firstPost.likedUsers.length>0||t.rewardedUsers.length>0?s("div",{staticClass:"isrelationGap"}):e._e(),e._v(" "),t.firstPost.likedUsers.length>0?s("div",{staticClass:"likeBox"},[s("span",{staticClass:"icon iconfont icon-praise-after"}),e._v(" "),e._l(t.firstPost.likedUsers,(function(i){return s("a",{on:{click:function(t){return e.jumpPerDet(i._data.id)}}},[e._v(e._s(e.userArr(t.firstPost.likedUsers)))])})),e._v(" "),t.firstPost._data.likeCount>10?s("i",[e._v(" 等"),s("span",[e._v(e._s(t.firstPost._data.likeCount))]),e._v("个人觉得很赞")]):e._e()],2):e._e(),e._v(" "),t.rewardedUsers.length>0?s("div",{staticClass:"reward"},[s("span",{staticClass:"icon iconfont icon-money"}),e._v(" "),e._l(t.rewardedUsers,(function(i){return s("a",{attrs:{href:"javascript:;"},on:{click:function(t){return e.jumpPerDet(i._data.id)}}},[e._v(e._s(e.userArr(t.rewardedUsers)))])}))],2):e._e(),e._v(" "),t.lastThreePosts.length>0&&t.firstPost.likedUsers.length>0||t.lastThreePosts.length>0&&t.rewardedUsers.length>0?s("div",{staticClass:"isrelationLine"}):e._e(),e._v(" "),t.lastThreePosts.length>0?s("div",{staticClass:"replyBox"},[e._l(t.lastThreePosts,(function(t){return s("div",{staticClass:"replyCon"},[t.user?s("a",{attrs:{href:"javascript:;"}},[e._v(e._s(t.user._data.username))]):s("a",{attrs:{href:"javascript:;"}},[e._v("该用户已被删除")]),e._v(" "),t._data.replyUserId?s("span",{staticClass:"font9"},[e._v("回复")]):e._e(),e._v(" "),t._data.replyUserId&&t.replyUser?s("a",{attrs:{href:"javascript:;"}},[e._v(e._s(t.replyUser._data.username))]):t._data.replyUserId&&!t.replyUser?s("a",{attrs:{href:"javascript:;"}},[e._v("该用户已被删除")]):e._e(),e._v(" "),s("span",{domProps:{innerHTML:e._s(t._data.contentHtml)}})])})),e._v(" "),t._data.postCount>4?s("a",{staticClass:"allReply",on:{click:function(s){return e.jumpThemeDet(t._data.id)}}},[e._v("全部"+e._s(t._data.postCount-1)+"条回复"),s("span",{staticClass:"icon iconfont icon-right-arrow"})]):e._e()],2):e._e()]),e._v(" "),e.ischeckShow?s("van-checkbox",{ref:"checkboxes",refInFor:!0,staticClass:"memberCheck",attrs:{name:t._data.id}}):e._e()],1),e._v(" "),s("div",{staticClass:"gap"})])})),e._v(" "),e.ischeckShow?s("div",{staticClass:"manageFootFixed choFixed"},[s("a",{attrs:{href:"javascript:;"},on:{click:e.checkAll}},[e._v("全选")]),e._v(" "),s("a",{attrs:{href:"javascript:;"},on:{click:e.signOutDele}},[e._v("取消全选")]),e._v(" "),s("button",{staticClass:"checkSubmit",on:{click:e.deleteAllClick}},[e._v("删除选中")])]):e._e()],2)],1),e._v(" "),s("van-image-preview",{attrs:{images:e.priview},on:{change:e.onChange},scopedSlots:e._u([{key:"index",fn:function(){return[e._v("第"+e._s(e.index)+"页")]},proxy:!0}]),model:{value:e.imageShow,callback:function(t){e.imageShow=t},expression:"imageShow"}})],1)},a=[];s.d(t,"a",(function(){return i})),s.d(t,"b",(function(){return a}))},CFQY:function(e,t,s){"use strict";s.r(t);var i=s("2xe9"),a=s("DhNJ");for(var o in a)"default"!==o&&function(e){s.d(t,e,(function(){return a[e]}))}(o);var n=s("KHd+"),r=Object(n.a)(a.default,i.a,i.b,!1,null,null,null);t.default=r.exports},DhNJ:function(e,t,s){"use strict";s.r(t);var i=s("xry+"),a=s.n(i);for(var o in i)"default"!==o&&function(e){s.d(t,e,(function(){return i[e]}))}(o);t.default=a.a},Jgvg:function(e,t,s){"use strict";s.r(t);var i=s("pvnC"),a=s.n(i);for(var o in i)"default"!==o&&function(e){s.d(t,e,(function(){return i[e]}))}(o);t.default=a.a},P674:function(e,t,s){"use strict";s.r(t);var i=s("s7by"),a=s.n(i);for(var o in i)"default"!==o&&function(e){s.d(t,e,(function(){return i[e]}))}(o);t.default=a.a},QiNT:function(e,t,s){"use strict";Object.defineProperty(t,"__esModule",{value:!0});var i,a=n(s("YEIV")),o=(s("ULRk"),n(s("+KBz")),n(s("VVfg")),n(s("6NK7")));function n(e){return e&&e.__esModule?e:{default:e}}t.default={data:function(){var e;return e={headBackShow:!1,oneHeader:!1,twoHeader:!1,threeHeader:!1,fourHeader:!1,isfixNav:!1,isShow:!1,isHeadShow:!1,showHeader:!1,showMask:!1,title:"",navActi:0,perDet:{themeNum:"1222",memberNum:"1222",circleLeader:"站长名称"},avatarUrl:"",mobile:"",userId:""},(0,a.default)(e,"isfixNav",!1),(0,a.default)(e,"popupShow",!1),(0,a.default)(e,"current",0),(0,a.default)(e,"userDet",[]),(0,a.default)(e,"categories",[]),(0,a.default)(e,"siteInfo",!1),(0,a.default)(e,"username",""),(0,a.default)(e,"isPayVal",""),(0,a.default)(e,"isWeixin",!1),(0,a.default)(e,"isPhone",!1),(0,a.default)(e,"firstCategoriesId",""),(0,a.default)(e,"logo",!1),e},props:{personInfo:{type:!1},userInfoAvatarUrl:{type:String},userInfoName:{type:String},headFixed:{headFixed:!1},invitePerDet:{invitePerDet:!1},searchIconShow:{searchIconShow:!1},menuIconShow:{menuIconShow:!1},navShow:{navShow:!1},invitationShow:{invitationShow:!1},perDetShow:{perDet:!1},logoShow:{logoShow:!1}},created:function(){this.isWeixin=o.default.isWeixin().isWeixin,this.isPhone=o.default.isWeixin().isPhone,this.loadCategories()},watch:{isfixNav:function(e,t){this.isfixNav=e}},methods:(i={limitWidth:function(){document.getElementById("testNavBar").style.width="640px";var e=window.innerWidth;document.getElementById("testNavBar").style.marginLeft=(e-640)/2+"px"},loadCategories:function(){var e=this;this.appFetch({url:"forum",method:"get",data:{include:["users"]}}).then((function(t){e.siteInfo=t.readdata,t.readdata._data.logo&&(e.logo=t.readdata._data.logo),e.isPayVal=t.readdata._data.siteMode})),this.appFetch({url:"categories",method:"get",data:{include:[]}}).then((function(t){console.log("2222"),console.log(t),e.categories=t.readdata,e.firstCategoriesId=t.readdata[0]._data.id,console.log(e.firstCategoriesId),e.$emit("update",e.firstCategoriesId),console.log("3456")}))},backUrl:function(){window.history.go(-1)},showPopup:function(){this.popupShow=!0},categoriesCho:function(e){this.$emit("categoriesChoice",e)},searchJump:function(){this.$router.push({path:"/search"})},handleTabFix:function(){if(this.headFixed)if((window.pageYOffset||document.documentElement.scrollTop||document.body.scrollTop)>document.querySelector("#testNavBar").offsetTop)this.showHeader=!0,this.isfixNav=!0,1!=this.isWeixin&&1!=this.isPhone&&this.limitWidth();else{this.showHeader=!1,this.isfixNav=!1;window.innerWidth;document.getElementById("testNavBar").style.marginLeft="0px"}}},(0,a.default)(i,"backUrl",(function(){window.history.go(-1)})),(0,a.default)(i,"LogOut",(function(){console.log("测试")})),(0,a.default)(i,"bindEvent",(function(e){1==e&&this.LogOut()})),i),mounted:function(){window.addEventListener("scroll",this.handleTabFix,!0)},beforeRouteLeave:function(e,t,s){window.removeEventListener("scroll",this.handleTabFix,!0),s()}}},SO9L:function(e,t,s){"use strict";Object.defineProperty(t,"__esModule",{value:!0});var i,a=s("VVfg"),o=(i=a)&&i.__esModule?i:{default:i};t.default={data:function(){return{showScreen:!1,loginBtnFix:!1,loginHide:!1,fourHeader:!0,isWx:"1",themeChoList:[{typeWo:"全部主题",type:"1",themeType:"allThemes"},{typeWo:"精华主题",type:"2",themeType:"isEssence"}],themeListCon:[],themeNavListCon:[],currentData:{},replyTagShow:!1,firstpostImageListCon:[],loading:!1,finished:!1,isLoading:!1,pageIndex:1,pageLimit:5,offset:100,canEdit:!0,firstCategoriesId:"",Initialization:!1,searchStatus:!0,menuStatus:!0,categoryId:!1}},created:function(){this.getInfo(),this.load()},methods:{receive:function(e){console.log(e),this.firstCategoriesId=e,this.loadThemeList()},getInfo:function(){var e=this;this.appFetch({url:"forum",method:"get",data:{include:["users"]}}).then((function(t){console.log(t),e.siteInfo=t.readdata,console.log(t.readdata._data.siteMode+"请求"),e.sitePrice=t.readdata._data.sitePrice,e.isPayVal=t.readdata._data.siteMode,null!=e.isPayVal&&""!=e.isPayVal&&(e.isPayVal=t.readdata._data.siteMode,e.detailIf(e.isPayVal,!1))}))},getUser:function(){var e=this,t=o.default.getLItem("tokenId");this.appFetch({url:"users",method:"get",splice:"/"+t,data:{include:"groups"}}).then((function(t){e.username=t.readdata._data.username,e.isPaid=t.readdata._data.paid,e.roleList=t.readdata.groups,""==t.readdata._data.joinedAt||null==t.readdata._data.joinedAt?e.joinedAt=t.readdata._data.createdAt:e.joinedAt=t.readdata._data.joinedAt,null!=e.isPaid&&""!=e.isPaid&&e.detailIf(e.isPayVal,!1)}))},detailIf:function(e){if("public"==e){console.log("公开");var t=o.default.getLItem("Authorization",t);t?(console.log("公开，已登录"),this.loadThemeList(),this.loginBtnFix=!1,this.loginHide=!0,this.canEdit=!0,this.searchStatus=!0,this.menuStatus=!0):(console.log("公开，未登录"),this.loginBtnFix=!0,this.loginHide=!1,this.canEdit=!1,this.searchStatus=!1,this.menuStatus=!1)}},load:function(){var e=this.appCommonH.isWeixin().isWeixin;return this.isWx=1==e?2:1,this.isWx},loadThemeList:function(e,t){var s=this,i=arguments.length>2&&void 0!==arguments[2]&&arguments[2];if(console.log("请求333"),console.log(e),"isEssence"==e)console.log("筛选请求"),this.categoryId||(this.categoryId=this.firstCategoriesId),console.log(this.categoryId),console.log("添加分类筛选"),this.appFetch({url:"threads",method:"get",data:{"filter[isEssence]":"yes","filter[categoryId]":this.categoryId,"filter[isApproved]":1,"filter[isDeleted]":"no",include:["user","firstPost","firstPost.images","lastThreePosts","lastThreePosts.user","lastThreePosts.replyUser","firstPost.likedUsers","rewardedUsers"],"page[number]":this.pageIndex,"page[limit]":this.pageLimit}}).then((function(e){i&&(s.themeListCon=[]),s.themeListCon=s.themeListCon.concat(e.readdata),s.loading=!1,s.finished=e.readdata.length<s.pageLimit})).catch((function(e){s.loading&&1!==s.pageIndex&&s.pageIndex--,s.loading=!1}));else{if("allThemes"!=e)return"categoryId"==e?(console.log("初始化请求页面"),this.categoryId=t,this.appFetch({url:"threads",method:"get",data:{"filter[categoryId]":t,"filter[isApproved]":1,"filter[isDeleted]":"no",include:["user","firstPost","firstPost.images","lastThreePosts","lastThreePosts.user","lastThreePosts.replyUser","firstPost.likedUsers","rewardedUsers"]}}).then((function(e){i&&(s.themeListCon=[]),console.log(e),console.log("890"),s.themeListCon=e.readdata,s.themeListCon=s.themeListCon.concat(e.readdata),console.log(s.themeListCon),console.log("666"),s.loading=!1,s.finished=e.readdata.length<s.pageLimit})).catch((function(e){s.loading&&1!==s.pageIndex&&s.pageIndex--,s.loading=!1}))):(console.log("执行初始化"),this.appFetch({url:"threads",method:"get",data:{"filter[categoryId]":this.firstCategoriesId,"filter[isApproved]":1,"filter[isDeleted]":"no",include:["user","firstPost","firstPost.images","lastThreePosts","lastThreePosts.user","lastThreePosts.replyUser","firstPost.likedUsers","rewardedUsers"],"page[number]":this.pageIndex,"page[limit]":this.pageLimit}}).then((function(e){i&&(s.themeListCon=[]),s.themeListCon=s.themeListCon.concat(e.readdata),console.log(s.themeListCon),console.log("77777"),s.loading=!1,s.finished=e.data.length<s.pageLimit})).catch((function(e){s.loading&&1!==s.pageIndex&&s.pageIndex--,s.loading=!1})));console.log("筛选请求"),this.categoryId||(this.categoryId=this.firstCategoriesId),console.log(this.categoryId),this.appFetch({url:"threads",method:"get",data:{"filter[categoryId]":this.categoryId,"filter[isApproved]":1,"filter[isDeleted]":"no",include:["user","firstPost","firstPost.images","lastThreePosts","lastThreePosts.user","lastThreePosts.replyUser","firstPost.likedUsers","rewardedUsers"],"page[number]":this.pageIndex,"page[limit]":this.pageLimit}}).then((function(e){i&&(s.themeListCon=[]),s.themeListCon=[],s.themeListCon=e.readdata,s.themeListCon=s.themeListCon.concat(e.readdata),s.loading=!1,s.finished=e.data.length<s.pageLimit})).catch((function(e){s.loading&&1!==s.pageIndex&&s.pageIndex--,s.loading=!1}))}},pushImgArray:function(){},footFix:function(){var e=window.pageYOffset||document.documentElement.scrollTop||document.body.scrollTop,t=document.querySelector("#testNavBar").offsetTop;1==this.loginBtnFix&&(this.loginHide=!0,this.loginHide=e>t)},choTheme:function(e){console.log(e),console.log("筛选"),this.loadThemeList(e)},categoriesChoice:function(e){this.loadThemeList("categoryId",e)},loginJump:function(e){var t=this,s=this.load();this.$router.push({path:"wechat"}),1==s?this.$router.push({path:"login-user"}):2==s&&this.appFetch({url:"weixin",method:"get",data:{}}).then((function(e){alert(1234),t.$router.push({path:"wechat"})}))},postTopic:function(){this.$router.push({path:"/post-topic"})},addClass:function(e,t){this.current=e;t.currentTarget},bindScreen:function(){this.showScreen=!this.showScreen},hideScreen:function(){this.showScreen=!1},onLoad:function(){this.loading=!0,this.pageIndex++,this.loadThemeList()},onRefresh:function(){var e=this;this.pageIndex=1,this.loadThemeList(!0).then((function(){e.$toast("刷新成功"),e.finished=!1,e.isLoading=!1})).catch((function(t){e.$toast("刷新失败"),e.isLoading=!1}))}},mounted:function(){window.addEventListener("scroll",this.footFix,!0)},beforeRouteLeave:function(e,t,s){window.removeEventListener("scroll",this.footFix,!0),s()}}},iT2n:function(e,t,s){"use strict";var i=function(){var e=this,t=e.$createElement,s=e._self._c||t;return s("section",[s("van-popup",{staticClass:"sidebarWrap",style:{height:"100%"},attrs:{position:"right"},model:{value:e.popupShow,callback:function(t){e.popupShow=t},expression:"popupShow"}},[s("sidebar",{attrs:{isPayVal:e.isPayVal}})],1),e._v(" "),e.$route.meta.oneHeader?s("div",{staticClass:"headerBox"},[s("div",{directives:[{name:"show",rawName:"v-show",value:e.invitePerDet,expression:"invitePerDet"}],staticClass:"invitePerDet"},[e.personInfo?s("div",{},[e.userInfoAvatarUrl?s("img",{staticClass:"inviteHead",attrs:{src:e.userInfoAvatarUrl,alt:""}}):s("img",{staticClass:"inviteHead",attrs:{src:e.appConfig.staticBaseUrl+"/images/noavatar.gif",alt:"ssss"}}),e._v(" "),e.invitePerDet&&e.userInfoName?s("div",{staticClass:"inviteName",model:{value:e.userInfoName,callback:function(t){e.userInfoName=t},expression:"userInfoName"}},[e._v(e._s(e.userInfoName))]):s("div",{staticClass:"inviteName"},[e._v("该用户已被删除")]),e._v(" "),s("p",{directives:[{name:"show",rawName:"v-show",value:e.invitationShow,expression:"invitationShow"}],staticClass:"inviteWo"},[e._v("邀请您加入")])]):e._e()]),e._v(" "),e.searchIconShow||e.menuIconShow?e._e():s("div",{staticClass:"headeGap"}),e._v(" "),e.searchIconShow||e.menuIconShow?s("div",{staticClass:"headOpe"},[s("span",{directives:[{name:"show",rawName:"v-show",value:e.searchIconShow,expression:"searchIconShow"}],staticClass:"icon iconfont icon-search",on:{click:e.searchJump}}),e._v(" "),s("span",{directives:[{name:"show",rawName:"v-show",value:e.menuIconShow,expression:"menuIconShow"}],staticClass:"icon iconfont icon-Shape",attrs:{"is-link":""},on:{click:e.showPopup}})]):e._e(),e._v(" "),s("div",{directives:[{name:"show",rawName:"v-show",value:e.logoShow,expression:"logoShow"}],staticClass:"logoBox"},[e.logo?s("img",{staticClass:"logo",attrs:{src:e.logo}}):s("img",{staticClass:"logo",attrs:{src:e.appConfig.staticBaseUrl+"/images/logo.png"}})]),e._v(" "),e.siteInfo?s("div",{directives:[{name:"show",rawName:"v-show",value:e.perDetShow,expression:"perDetShow"}],staticClass:"circleDet"},[s("span",[e._v("主题："+e._s(e.siteInfo._data.threads))]),e._v(" "),s("span",[e._v("成员："+e._s(e.siteInfo._data.members))]),e._v(" "),e.siteInfo._data.siteAuthor?s("span",[e._v("站长："+e._s(e.siteInfo._data.siteAuthor.username))]):s("span",[e._v("站长：无")])]):e._e(),e._v(" "),s("div",{directives:[{name:"show",rawName:"v-show",value:e.navShow,expression:"navShow"}],staticClass:"navBox",class:{fixedNavBar:e.isfixNav},attrs:{id:"testNavBar"}},[s("van-tabs",{model:{value:e.navActi,callback:function(t){e.navActi=t},expression:"navActi"}},e._l(e.categories,(function(t,i){return s("van-tab",{key:i},[s("span",{attrs:{slot:"title"},on:{click:function(s){return e.categoriesCho(t._data.id)}},slot:"title"},[e._v("\n              "+e._s(t._data.name)+"\n          ")])])})),1)],1)]):e._e()],1)},a=[];s.d(t,"a",(function(){return i})),s.d(t,"b",(function(){return a}))},lf9R:function(e,t,s){"use strict";var i=function(){var e=this,t=e.$createElement,s=e._self._c||t;return s("div",{staticClass:"circleCon"},[s("van-list",{attrs:{finished:e.finished,offset:e.offset,"finished-text":"没有更多了","immediate-check":!1},on:{load:e.onLoad},model:{value:e.loading,callback:function(t){e.loading=t},expression:"loading"}},[s("van-pull-refresh",{on:{refresh:e.onRefresh},model:{value:e.isLoading,callback:function(t){e.isLoading=t},expression:"isLoading"}},[s("Header",{attrs:{searchIconShow:e.searchStatus,perDetShow:!0,logoShow:!0,menuIconShow:e.menuStatus,navShow:!0,invitePerDet:!1,headFixed:!0},on:{categoriesChoice:e.categoriesChoice,update:e.receive}}),e._v(" "),s("div",{staticClass:"padB"}),e._v(" "),s("div",{staticClass:"gap"}),e._v(" "),s("div",{staticClass:"themeTitBox"},[s("span",{staticClass:"themeTit"},[e._v("全部主题")]),e._v(" "),s("div",{staticClass:"screen",on:{click:e.bindScreen}},[s("span",[e._v("筛选")]),e._v(" "),s("span",{staticClass:"icon iconfont icon-down-menu jtGrayB"}),e._v(" "),e.showScreen?s("div",{staticClass:"themeList"},e._l(e.themeChoList,(function(t,i){return s("a",{key:i,attrs:{href:"javascript:;"},on:{click:function(s){return e.choTheme(t.themeType)}}},[e._v(e._s(t.typeWo))])})),0):e._e()])]),e._v(" "),e.themeListCon?s("div",[s("ThemeDet",{attrs:{themeList:e.themeListCon,isTopShow:!0,isMoreShow:!0},on:{"update:themeList":function(t){e.themeListCon=t},"update:theme-list":function(t){e.themeListCon=t},changeStatus:e.loadThemeList}})],1):e._e(),e._v(" "),e.loginBtnFix?s("van-button",{staticClass:"loginBtnFix",class:{hide:e.loginHide},attrs:{type:"primary"},on:{click:function(t){return e.loginJump(1)}}},[e._v("登录 / 注册")]):e._e(),e._v(" "),e.canEdit?s("div",{staticClass:"fixedEdit",on:{click:e.postTopic}},[s("span",{staticClass:"icon iconfont icon-publish"})]):e._e()],1)],1)],1)},a=[];s.d(t,"a",(function(){return i})),s.d(t,"b",(function(){return a}))},omtG:function(e,t,s){"use strict";s.r(t);var i=s("iT2n"),a=s("Jgvg");for(var o in a)"default"!==o&&function(e){s.d(t,e,(function(){return a[e]}))}(o);var n=s("KHd+"),r=Object(n.a)(a.default,i.a,i.b,!1,null,null,null);t.default=r.exports},pvnC:function(e,t,s){"use strict";Object.defineProperty(t,"__esModule",{value:!0});var i=n(s("QbLZ")),a=n(s("QiNT")),o=n(s("IsPG"));function n(e){return e&&e.__esModule?e:{default:e}}s("E2jd"),t.default=(0,i.default)({name:"headerView",components:{Sidebar:o.default}},a.default)},s7by:function(e,t,s){"use strict";Object.defineProperty(t,"__esModule",{value:!0});var i=d(s("QbLZ")),a=d(s("SO9L")),o=d(s("QiNT")),n=d(s("omtG")),r=d(s("/Zpk")),c=d(s("CFQY"));function d(e){return e&&e.__esModule?e:{default:e}}s("E2jd"),t.default=(0,i.default)({name:"circleView",components:{Header:n.default,ThemeDet:c.default}},o.default,r.default,a.default)},vuqY:function(e,t,s){"use strict";s.r(t);var i=s("lf9R"),a=s("P674");for(var o in a)"default"!==o&&function(e){s.d(t,e,(function(){return a[e]}))}(o);var n=s("KHd+"),r=Object(n.a)(a.default,i.a,i.b,!1,null,null,null);t.default=r.exports},"xry+":function(e,t,s){"use strict";Object.defineProperty(t,"__esModule",{value:!0});var i=o(s("QbLZ")),a=o(s("/Zpk"));function o(e){return e&&e.__esModule?e:{default:e}}s("E2jd"),t.default=(0,i.default)({name:"themeDetView"},a.default)}}]);