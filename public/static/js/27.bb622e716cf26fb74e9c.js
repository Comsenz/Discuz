(window.webpackJsonp=window.webpackJsonp||[]).push([[27,81,82],{"/Zpk":function(e,t,a){"use strict";Object.defineProperty(t,"__esModule",{value:!0}),t.default={data:function(){return{id:1,checked:!0,result:[],checkBoxres:[],imageShow:!1,index:1,themeListResult:[],firstpostImageListResult:[],priview:[],showScreen:[],length:0,indexlist:-1,menuStatus:!1}},props:{themeList:{type:Array},replyTag:{replyTag:!1},isTopShow:{isTopShow:!1},isMoreShow:{isMoreShow:!1},ischeckShow:{ischeckShow:!1}},created:function(){this.loadPriviewImgList(),this.forList()},beforeDestroy:function(){},watch:{themeList:function(e,t){this.themeList=e,this.themeListResult=e,this.loadPriviewImgList(),this.$forceUpdate()},deep:!0},methods:{userArr:function(e){var t=[];return e.forEach((function(e){t.push('<a  href="/home-page/'+e._data.id+'">'+e._data.username+"</a>")})),t.join(",")},forList:function(){},bindScreen:function(e){e==this.indexlist?this.indexlist=-1:this.indexlist=e},disappear:function(){console.log("dianji")},themeOpera:function(e,t,a){var s=new Object;2==t?(console.log(a),this.themeOpeRequest(e,s,a),s.isEssence=a):3==t?(s.isSticky=a,this.themeOpeRequest(e,s,a)):4==t?(s.isDeleted=!0,this.themeOpeRequest(e,s)):this.$router.push({path:"/edit-topic/"+this.themeId})},themeOpeRequest:function(e,t,a){var s=this;this.appFetch({url:"threads",method:"patch",splice:"/"+e,data:{data:{type:"threads",attributes:t}}}).then((function(e){if(e.errors)throw s.$toast.fail(e.errors[0].code),new Error(e.error);s.$emit("changeStatus",!0)}))},replyOpera:function(e,t,a,s){var i=this,n=new Object;n.isLiked=s;var o="posts/"+e;this.appFetch({url:o,method:"patch",data:{data:{type:"posts",attributes:n}}}).then((function(e){if(e.errors)throw i.$toast.fail(e.errors[0].code),new Error(e.error);i.$toast.success("修改成功"),i.$emit("changeStatus",!0)}))},loadPriviewImgList:function(){var e=this.themeListResult.length;if(""==this.themeListResult||null==this.themeListResult)return!1;for(var t=0;t<e;t++){var a=[];if(this.themeListResult[t].firstPost.images)for(var s=0;s<this.themeListResult[t].firstPost.images.length;s++)a.push(this.themeListResult[t].firstPost.images[s]._data.thumbUrl);this.themeListResult[t].firstPost.imageList=a}},imageSwiper:function(e){this.loadPriviewImgList(),this.imageShow=!0,console.log(this.priview)},onChange:function(e){this.index=e+1},checkAll:function(){console.log(this.$refs),this.$refs.checkboxGroup.toggleAll(!0)},signOutDele:function(){this.$refs.checkboxGroup.toggleAll()},deleteAllClick:function(){this.$emit("deleteAll",this.result)},jumpThemeDet:function(e,t){t?this.$router.push({path:"details/"+e}):this.$toast.fail("没有权限，请联系站点管理员")},jumpPerDet:function(e){this.$router.push({path:"/home-page/"+e})}},beforeRouteLeave:function(e,t,a){a()}}},"4Njt":function(e,t,a){"use strict";Object.defineProperty(t,"__esModule",{value:!0});var s=l(a("QbLZ"));a("Cpqr");var i=l(a("omtG")),n=(l(a("QiNT")),l(a("JZuw"))),o=l(a("CFQY")),r=l(a("aSTm")),c=l(a("/Zpk"));function l(e){return e&&e.__esModule?e:{default:e}}a("E2jd"),t.default=(0,s.default)({name:"my-collection-view",components:{comHeader:n.default,Header:i.default,ThemeDet:o.default}},r.default,{mSiteThemeDet:c.default})},"6JNq":function(e,t,a){"use strict";var s=function(){var e=this,t=e.$createElement,a=e._self._c||t;return a("section",[a("van-popup",{staticClass:"sidebarWrap",style:{height:"100%"},attrs:{position:"right"},model:{value:e.popupShow,callback:function(t){e.popupShow=t},expression:"popupShow"}},[a("sidebar",{attrs:{isPayVal:e.isPayVal}})],1),e._v(" "),e.$route.meta.oneHeader?a("div",{staticClass:"headerBox"},[a("div",{directives:[{name:"show",rawName:"v-show",value:e.invitePerDet,expression:"invitePerDet"}],staticClass:"invitePerDet aaa"},[e.userInfoAvatarUrl?a("img",{staticClass:"inviteHead",attrs:{src:e.userInfoAvatarUrl,alt:""}}):a("img",{staticClass:"inviteHead",attrs:{src:e.appConfig.staticBaseUrl+"/images/noavatar.gif",alt:"ssss"}}),e._v(" "),e.invitePerDet&&e.userInfoName?a("div",{staticClass:"inviteName",model:{value:e.userInfoName,callback:function(t){e.userInfoName=t},expression:"userInfoName"}},[e._v(e._s(e.userInfoName))]):a("div",{staticClass:"inviteName"},[e._v("该用户已被删除")]),e._v(" "),a("p",{directives:[{name:"show",rawName:"v-show",value:e.invitationShow,expression:"invitationShow"}],staticClass:"inviteWo"},[e._v("邀请您加入")])]),e._v(" "),e.searchIconShow||e.menuIconShow?e._e():a("div",{staticClass:"headeGap"}),e._v(" "),e.searchIconShow||e.menuIconShow?a("div",{staticClass:"headOpe"},[a("span",{directives:[{name:"show",rawName:"v-show",value:e.searchIconShow,expression:"searchIconShow"}],staticClass:"icon iconfont icon-search",on:{click:e.searchJump}}),e._v(" "),a("span",{directives:[{name:"show",rawName:"v-show",value:e.menuIconShow,expression:"menuIconShow"}],staticClass:"icon iconfont icon-Shape",attrs:{"is-link":""},on:{click:e.showPopup}})]):e._e(),e._v(" "),a("div",{directives:[{name:"show",rawName:"v-show",value:e.logoShow,expression:"logoShow"}],staticClass:"logoBox"},[e.logo?a("img",{staticClass:"logo",attrs:{src:e.logo}}):a("img",{staticClass:"logo",attrs:{src:e.appConfig.staticBaseUrl+"/images/logo.png"}})]),e._v(" "),e.siteInfo?a("div",{directives:[{name:"show",rawName:"v-show",value:e.perDetShow,expression:"perDetShow"}],staticClass:"circleDet"},[a("span",[e._v("主题："+e._s(e.siteInfo._data.threads))]),e._v(" "),a("span",[e._v("成员："+e._s(e.siteInfo._data.members))]),e._v(" "),e.siteInfo._data.siteAuthor?a("span",[e._v("站长："+e._s(e.siteInfo._data.siteAuthor.username))]):a("span",[e._v("站长：无")])]):e._e(),e._v(" "),a("div",{directives:[{name:"show",rawName:"v-show",value:e.navShow,expression:"navShow"}],staticClass:"navBox",class:{fixedNavBar:e.isfixNav},attrs:{id:"testNavBar"}},[a("van-tabs",{model:{value:e.navActi,callback:function(t){e.navActi=t},expression:"navActi"}},e._l(e.categories,(function(t,s){return a("van-tab",{key:s},[a("span",{attrs:{slot:"title"},on:{click:function(a){return e.categoriesCho(t._data.id)}},slot:"title"},[e._v("\n              "+e._s(t._data.name)+"\n          ")])])})),1)],1)]):e._e()],1)},i=[];a.d(t,"a",(function(){return s})),a.d(t,"b",(function(){return i}))},"8VtU":function(e,t,a){"use strict";var s=function(){var e=this,t=e.$createElement,a=e._self._c||t;return a("div",{staticClass:"reply-my-box my-info-money-header"},[a("comHeader",{attrs:{title:"我的收藏"}}),e._v(" "),a("van-list",{attrs:{finished:e.finished,offset:e.offset,"finished-text":"没有更多了","immediate-check":!1},on:{load:e.onLoad},model:{value:e.loading,callback:function(t){e.loading=t},expression:"loading"}},[a("van-pull-refresh",{on:{refresh:e.onRefresh},model:{value:e.isLoading,callback:function(t){e.isLoading=t},expression:"isLoading"}},[a("div",{staticClass:"content"},[a("ThemeDet",{attrs:{themeList:e.collectionList,isMoreShow:!1}})],1)])],1)],1)},i=[];a.d(t,"a",(function(){return s})),a.d(t,"b",(function(){return i}))},CFQY:function(e,t,a){"use strict";a.r(t);var s=a("dzHY"),i=a("DhNJ");for(var n in i)"default"!==n&&function(e){a.d(t,e,(function(){return i[e]}))}(n);var o=a("KHd+"),r=Object(o.a)(i.default,s.a,s.b,!1,null,null,null);t.default=r.exports},DhNJ:function(e,t,a){"use strict";a.r(t);var s=a("xry+"),i=a.n(s);for(var n in s)"default"!==n&&function(e){a.d(t,e,(function(){return s[e]}))}(n);t.default=i.a},Jgvg:function(e,t,a){"use strict";a.r(t);var s=a("pvnC"),i=a.n(s);for(var n in s)"default"!==n&&function(e){a.d(t,e,(function(){return s[e]}))}(n);t.default=i.a},QiNT:function(e,t,a){"use strict";Object.defineProperty(t,"__esModule",{value:!0});var s,i=o(a("YEIV")),n=(a("ULRk"),o(a("+KBz")),o(a("VVfg")),o(a("6NK7")));function o(e){return e&&e.__esModule?e:{default:e}}t.default={data:function(){var e;return e={headBackShow:!1,oneHeader:!1,twoHeader:!1,threeHeader:!1,fourHeader:!1,isfixNav:!1,isShow:!1,isHeadShow:!1,showHeader:!1,showMask:!1,title:"",navActi:0,perDet:{themeNum:"1222",memberNum:"1222",circleLeader:"站长名称"},avatarUrl:"",mobile:"",userId:""},(0,i.default)(e,"isfixNav",!1),(0,i.default)(e,"popupShow",!1),(0,i.default)(e,"current",0),(0,i.default)(e,"userDet",[]),(0,i.default)(e,"categories",[]),(0,i.default)(e,"siteInfo",!1),(0,i.default)(e,"username",""),(0,i.default)(e,"isPayVal",""),(0,i.default)(e,"isWeixin",!1),(0,i.default)(e,"isPhone",!1),(0,i.default)(e,"firstCategoriesId",""),(0,i.default)(e,"logo",!1),e},props:{userInfoAvatarUrl:{type:String},userInfoName:{type:String},headFixed:{headFixed:!1},invitePerDet:{invitePerDet:!1},searchIconShow:{searchIconShow:!1},menuIconShow:{menuIconShow:!1},navShow:{navShow:!1},invitationShow:{invitationShow:!1},perDetShow:{perDet:!1},logoShow:{logoShow:!1}},created:function(){this.isWeixin=n.default.isWeixin().isWeixin,this.isPhone=n.default.isWeixin().isPhone,this.loadCategories()},watch:{isfixNav:function(e,t){this.isfixNav=e}},methods:(s={limitWidth:function(){document.getElementById("testNavBar").style.width="640px";var e=window.innerWidth;document.getElementById("testNavBar").style.marginLeft=(e-640)/2+"px"},loadCategories:function(){var e=this;this.appFetch({url:"forum",method:"get",data:{include:["users"]}}).then((function(t){e.siteInfo=t.readdata,t.readdata._data.logo&&(e.logo=t.readdata._data.logo),e.isPayVal=t.readdata._data.siteMode})),this.appFetch({url:"categories",method:"get",data:{include:[]}}).then((function(t){console.log("2222"),console.log(t),e.categories=t.readdata,e.firstCategoriesId=t.readdata[0]._data.id,console.log(e.firstCategoriesId),e.$emit("update",e.firstCategoriesId),console.log("3456")}))},backUrl:function(){window.history.go(-1)},showPopup:function(){this.popupShow=!0},categoriesCho:function(e){this.$emit("categoriesChoice",e)},searchJump:function(){this.$router.push({path:"/search"})},handleTabFix:function(){if(this.headFixed)if((window.pageYOffset||document.documentElement.scrollTop||document.body.scrollTop)>document.querySelector("#testNavBar").offsetTop)this.showHeader=!0,this.isfixNav=!0,1!=this.isWeixin&&1!=this.isPhone&&this.limitWidth();else{this.showHeader=!1,this.isfixNav=!1;window.innerWidth;document.getElementById("testNavBar").style.marginLeft="0px"}}},(0,i.default)(s,"backUrl",(function(){window.history.go(-1)})),(0,i.default)(s,"LogOut",(function(){console.log("测试")})),(0,i.default)(s,"bindEvent",(function(e){1==e&&this.LogOut()})),s),mounted:function(){window.addEventListener("scroll",this.handleTabFix,!0)},beforeDestroy:function(){window.removeEventListener("scroll",this.handleTabFix,!0)},destroyed:function(){window.removeEventListener("scroll",this.handleTabFix,!0)},beforeRouteLeave:function(e,t,a){window.removeEventListener("scroll",this.handleTabFix,!0),a()}}},YRWR:function(e,t,a){"use strict";a.r(t);var s=a("4Njt"),i=a.n(s);for(var n in s)"default"!==n&&function(e){a.d(t,e,(function(){return s[e]}))}(n);t.default=i.a},aSTm:function(e,t,a){"use strict";Object.defineProperty(t,"__esModule",{value:!0});s(a("JZuw")),s(a("STKU")),s(a("/uo3")),s(a("3YLv"));function s(e){return e&&e.__esModule?e:{default:e}}t.default={data:function(){return{collectionList:[],list:[],loading:!1,finished:!1,isLoading:!1,pageIndex:1,pageLimit:20,offset:100}},created:function(){this.imgUrl="../../../../../../../static/images/mytx.png",this.myCollection()},methods:{myCollection:function(){var e=this,t=arguments.length>0&&void 0!==arguments[0]&&arguments[0];return this.appFetch({url:"collection",method:"get",data:{include:["user","firstPost","lastThreePosts","lastThreePosts.user","firstPost.likedUsers","rewardedUsers"],"page[number]":this.pageIndex,"page[limit]":this.pageLimit}}).then((function(a){if(a.errors)throw e.$toast.fail(a.errors[0].code),new Error(a.error);t&&(e.collectionList=[]),e.collectionList=e.collectionList.concat(a.readdata),e.loading=!1,e.finished=a.data.length<e.pageLimit})).catch((function(t){e.loading&&1!==e.pageIndex&&e.pageIndex--,e.loading=!1}))},onLoad:function(){this.loading=!0,this.pageIndex++,this.myCollection()},onRefresh:function(){var e=this;this.pageIndex=1,this.myCollection(!0).then((function(){e.$toast("刷新成功"),e.finished=!1,e.isLoading=!1})).catch((function(t){e.$toast("刷新失败"),e.isLoading=!1}))}}}},dzHY:function(e,t,a){"use strict";var s=function(){var e=this,t=e.$createElement,a=e._self._c||t;return a("section",[a("div",[a("van-checkbox-group",{ref:"checkboxGroup",model:{value:e.result,callback:function(t){e.result=t},expression:"result"}},[e._l(e.themeList,(function(t,s){return a("div",{key:s,on:{click:function(t){return e.disappear()}}},[a("div",{staticClass:"cirPostCon"},[a("div",{},[a("div",{staticClass:"postTop"},[a("div",{staticClass:"postPer"},[t.user._data.avatarUrl?a("img",{staticClass:"postHead",attrs:{src:t.user._data.avatarUrl},on:{click:function(a){return e.jumpPerDet(t.user._data.id)}}}):a("img",{staticClass:"postHead",attrs:{src:e.appConfig.staticBaseUrl+"/images/noavatar.gif"}}),e._v(" "),a("div",{staticClass:"perDet"},[t.user?a("div",{staticClass:"perName",on:{click:function(a){return e.jumpPerDet(t.user._data.id)}}},[e._v(e._s(t.user._data.username))]):a("div",{staticClass:"perName"},[e._v("该用户已被删除")]),e._v(" "),a("div",{staticClass:"postTime"},[e._v(e._s(e.$moment(t._data.createdAt).format("YYYY-MM-DD HH:mm")))])])]),e._v(" "),a("div",{staticClass:"postOpera"},[t._data.isSticky?a("span",{directives:[{name:"show",rawName:"v-show",value:e.isTopShow,expression:"isTopShow"}],staticClass:"icon iconfont icon-top"}):e._e(),e._v(" "),e.isMoreShow&&(t._data.canEssence||t._data.canSticky||t._data.canDelete||t._data.canEdit)?a("div",{ref:"screenDiv",refInFor:!0,staticClass:"screen",on:{click:function(t){return e.bindScreen(s)}}},[a("div",{staticClass:"moreCli"},[a("span",{staticClass:"icon iconfont icon-more"})]),e._v(" "),a("div",{directives:[{name:"show",rawName:"v-show",value:e.indexlist==s,expression:"indexlist==index"}],staticClass:"themeList"},[t.firstPost._data.canLike&&t.firstPost._data.isLiked?a("a",{attrs:{href:"javascript:;"},on:{click:function(a){return e.replyOpera(t.firstPost._data.id,2,t.firstPost._data.isLiked,!1)}}},[e._v("取消点赞")]):e._e(),e._v(" "),t.firstPost._data.canLike&&!t.firstPost._data.isLiked?a("a",{attrs:{href:"javascript:;"},on:{click:function(a){return e.replyOpera(t.firstPost._data.id,2,t.firstPost._data.isLiked,!0)}}},[e._v("点赞")]):e._e(),e._v(" "),t._data.canEssence&&t._data.isEssence?a("a",{attrs:{href:"javascript:;"},on:{click:function(a){return e.themeOpera(t._data.id,2,!1)}}},[e._v("取消加精")]):e._e(),e._v(" "),t._data.canEssence&&!t._data.isEssence?a("a",{attrs:{href:"javascript:;"},on:{click:function(a){return e.themeOpera(t._data.id,2,!0)}}},[e._v("加精")]):e._e(),e._v(" "),t._data.canSticky&&t._data.isSticky?a("a",{attrs:{href:"javascript:;"},on:{click:function(a){return e.themeOpera(t._data.id,3,!1)}}},[e._v("取消置顶")]):e._e(),e._v(" "),t._data.canSticky&&!t._data.isSticky?a("a",{attrs:{href:"javascript:;"},on:{click:function(a){return e.themeOpera(t._data.id,3,!0)}}},[e._v("置顶")]):e._e(),e._v(" "),t._data.canDelete?a("a",{attrs:{href:"javascript:;"},on:{click:function(a){return e.themeOpera(t._data.id,4)}}},[e._v("删除")]):e._e()])]):e._e()])]),e._v(" "),t.firstPost?a("div",{staticClass:"postContent"},[a("a",{domProps:{innerHTML:e._s(t.firstPost._data.contentHtml)},on:{click:function(a){return e.jumpThemeDet(t._data.id,t._data.canViewPosts)}}})]):e._e(),e._v(" "),t.firstPost.imageList&&t.firstPost.imageList.length>0?a("div",{staticClass:"themeImgBox"},[a("div",{staticClass:"themeImgList moreImg"},e._l(t.firstPost.imageList,(function(s,i){return i<9?a("van-image",{key:i,staticClass:"themeImgChild",attrs:{fit:"cover","lazy-load":"",src:s},on:{click:function(a){return e.jumpThemeDet(t._data.id,t._data.canViewPosts)}}}):e._e()})),1)]):e._e()]),e._v(" "),a("div",{staticClass:"operaBox"},[t.firstPost.likedUsers.length>0||t.rewardedUsers.length>0?a("div",{staticClass:"isrelationGap"}):e._e(),e._v(" "),t.firstPost.likedUsers.length>0?a("div",{staticClass:"likeBox"},[a("span",{staticClass:"icon iconfont icon-praise-after"}),e._v(" "),a("span",{domProps:{innerHTML:e._s(e.userArr(t.firstPost.likedUsers))}}),e._v(" "),t.firstPost._data.likeCount>10?a("i",[e._v(" 等"),a("span",[e._v(e._s(t.firstPost._data.likeCount))]),e._v("个人觉得很赞")]):e._e()]):e._e(),e._v(" "),t.rewardedUsers.length>0?a("div",{staticClass:"reward"},[a("span",{staticClass:"icon iconfont icon-money"}),e._v(" "),a("span",{domProps:{innerHTML:e._s(e.userArr(t.rewardedUsers))}})]):e._e(),e._v(" "),t.lastThreePosts.length>0&&t.firstPost.likedUsers.length>0||t.lastThreePosts.length>0&&t.rewardedUsers.length>0?a("div",{staticClass:"isrelationLine"}):e._e(),e._v(" "),t.lastThreePosts.length>0?a("div",{staticClass:"replyBox"},[e._l(t.lastThreePosts,(function(t){return a("div",{staticClass:"replyCon"},[t.user?a("a",{attrs:{href:"javascript:;"}},[e._v(e._s(t.user._data.username))]):a("a",{attrs:{href:"javascript:;"}},[e._v("该用户已被删除")]),e._v(" "),t._data.replyUserId?a("span",{staticClass:"font9"},[e._v("回复")]):e._e(),e._v(" "),t._data.replyUserId&&t.replyUser?a("a",{attrs:{href:"javascript:;"}},[e._v(e._s(t.replyUser._data.username))]):t._data.replyUserId&&!t.replyUser?a("a",{attrs:{href:"javascript:;"}},[e._v("该用户已被删除")]):e._e(),e._v(" "),a("span",{domProps:{innerHTML:e._s(t._data.contentHtml)}})])})),e._v(" "),t._data.postCount>4?a("a",{staticClass:"allReply",on:{click:function(a){return e.jumpThemeDet(t._data.id,t._data.canViewPosts)}}},[e._v("全部"+e._s(t._data.postCount-1)+"条回复"),a("span",{staticClass:"icon iconfont icon-right-arrow"})]):e._e()],2):e._e()]),e._v(" "),e.ischeckShow?a("van-checkbox",{ref:"checkboxes",refInFor:!0,staticClass:"memberCheck",attrs:{name:t._data.id}}):e._e()],1),e._v(" "),a("div",{staticClass:"gap"})])})),e._v(" "),e.ischeckShow?a("div",{staticClass:"manageFootFixed choFixed"},[a("a",{attrs:{href:"javascript:;"},on:{click:e.checkAll}},[e._v("全选")]),e._v(" "),a("a",{attrs:{href:"javascript:;"},on:{click:e.signOutDele}},[e._v("取消全选")]),e._v(" "),a("button",{staticClass:"checkSubmit",on:{click:e.deleteAllClick}},[e._v("删除选中")])]):e._e()],2)],1),e._v(" "),a("van-image-preview",{attrs:{images:e.priview},on:{change:e.onChange},scopedSlots:e._u([{key:"index",fn:function(){return[e._v("第"+e._s(e.index)+"页")]},proxy:!0}]),model:{value:e.imageShow,callback:function(t){e.imageShow=t},expression:"imageShow"}})],1)},i=[];a.d(t,"a",(function(){return s})),a.d(t,"b",(function(){return i}))},gX8P:function(e,t,a){"use strict";a.r(t);var s=a("8VtU"),i=a("YRWR");for(var n in i)"default"!==n&&function(e){a.d(t,e,(function(){return i[e]}))}(n);var o=a("KHd+"),r=Object(o.a)(i.default,s.a,s.b,!1,null,null,null);t.default=r.exports},omtG:function(e,t,a){"use strict";a.r(t);var s=a("6JNq"),i=a("Jgvg");for(var n in i)"default"!==n&&function(e){a.d(t,e,(function(){return i[e]}))}(n);var o=a("KHd+"),r=Object(o.a)(i.default,s.a,s.b,!1,null,null,null);t.default=r.exports},pvnC:function(e,t,a){"use strict";Object.defineProperty(t,"__esModule",{value:!0});var s=o(a("QbLZ")),i=o(a("QiNT")),n=o(a("IsPG"));function o(e){return e&&e.__esModule?e:{default:e}}a("E2jd"),t.default=(0,s.default)({name:"headerView",components:{Sidebar:n.default}},i.default)},"xry+":function(e,t,a){"use strict";Object.defineProperty(t,"__esModule",{value:!0});var s=n(a("QbLZ")),i=n(a("/Zpk"));function n(e){return e&&e.__esModule?e:{default:e}}a("E2jd"),t.default=(0,s.default)({name:"themeDetView"},i.default)}}]);