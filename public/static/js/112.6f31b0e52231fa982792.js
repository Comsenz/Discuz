(window.webpackJsonp=window.webpackJsonp||[]).push([[112],{"/Zpk":function(t,e,s){"use strict";Object.defineProperty(e,"__esModule",{value:!0});var i=r(s("6NK7")),a=r(s("VVfg"));function r(t){return t&&t.__esModule?t:{default:t}}e.default={data:function(){return{id:1,checked:!0,result:[],checkBoxres:[],imageShow:!1,index:1,themeListResult:[],firstpostImageListResult:[],priview:[],showScreen:[],length:0,indexlist:-1,menuStatus:!1,isWeixin:!1,isPhone:!1,viewportWidth:"",currentUserName:"",userId:""}},props:{themeList:{type:Array},replyTag:{replyTag:!1},isTopShow:{isTopShow:!1},isMoreShow:{isMoreShow:!1},ischeckShow:{ischeckShow:!1}},created:function(){var t=this;this.userId=a.default.getLItem("tokenId"),this.currentUserName=a.default.getLItem("foregroundUser"),this.viewportWidth=window.innerWidth,this.isWeixin=i.default.isWeixin().isWeixin,this.isPhone=i.default.isWeixin().isPhone,this.loadPriviewImgList(),this.forList(),document.addEventListener("click",(function(e){t.$refs.screenDiv;document.contains(e.target)&&(t.indexlist=-1)}))},watch:{themeList:function(t,e){this.themeList=t,this.themeListResult=t,this.loadPriviewImgList(),this.$forceUpdate()},deep:!0},methods:{userArr:function(t){var e=[];return t.forEach((function(t){e.push('<a  href="/home-page/'+t._data.id+'">'+t._data.username+"</a>")})),e.join(",")},forList:function(){},bindScreen:function(t,e){t==this.indexlist?this.indexlist=-1:this.indexlist=t},themeOpera:function(t,e,s,i){var a=new Object;3==e?(a.isEssence=!s,this.themeOpeRequest(t,a,"3",i)):4==e?(a.isSticky=!s,this.themeOpeRequest(t,a,"4",i)):5==e?(a.isDeleted=!0,this.themeOpeRequest(t,a,"5",i)):6==e?s?this.$router.push({path:"/edit-long-text/"+t}):this.$router.push({path:"/edit-topic/"+t}):7==e&&this.$router.push({path:"/reply-to-topic/"+t+"/0"})},themeOpeRequest:function(t,e,s,i){var a=this;this.appFetch({url:"threads",method:"patch",splice:"/"+t,data:{data:{type:"threads",attributes:e}}}).then((function(t){if(t.errors)throw a.$toast.fail(t.errors[0].code),new Error(t.error);"3"==s?(a.essenceStatus=t.readdata._data.isEssence,a.themeList[i]._data.isEssence=a.essenceStatus):"4"==s?(a.stickyStatus=t.readdata._data.isSticky,a.themeList[i]._data.isSticky=a.stickyStatus):"5"==s&&(a.deletedStatus=t.readdata._data.isDeleted,a.themeList.splice(i,1),a.$toast.success("删除成功"))}))},replyOpera:function(t,e,s,i){var a=this,r=new Object;r.isLiked=!s,this.appFetch({url:"posts",method:"patch",splice:"/"+t,data:{data:{type:"posts",attributes:r}}}).then((function(t){if(t.errors)throw a.$toast.fail(t.errors[0].code),new Error(t.error);s?(a.likedStatus=t.readdata._data.isLiked,a.themeList[i].firstPost._data.isLiked=a.likedStatus,a.themeList[i].firstPost.likedUsers.map((function(t,e,s){t._data.id===a.userId&&s.splice(e,1)}))):(a.likedStatus=t.readdata._data.isLiked,a.themeList[i].firstPost._data.isLiked=a.likedStatus,a.themeList[i].firstPost.likedUsers.unshift({_data:{username:a.currentUserName,id:a.userId}}))}))},loadPriviewImgList:function(){if(""==this.themeListResult||null==this.themeListResult)return!1;for(var t=this.themeListResult.length,e=0;e<t;e++){var s=[];if(this.themeListResult[e].firstPost.images)for(var i=0;i<this.themeListResult[e].firstPost.images.length;i++)s.push(this.themeListResult[e].firstPost.images[i]._data.thumbUrl);this.themeListResult[e].firstPost.imageList=s}},imageSwiper:function(t){this.loadPriviewImgList(),this.imageShow=!0},onChange:function(t){this.index=t+1},checkAll:function(){this.$refs.checkboxGroup.toggleAll(!0)},signOutDele:function(){this.$refs.checkboxGroup.toggleAll()},deleteAllClick:function(){this.$emit("deleteAll",this.result)},jumpThemeDet:function(t,e){e?this.$router.push({path:"/details/"+t}):this.$toast.fail("没有权限，请联系站点管理员")},jumpPerDet:function(t){this.$router.push({path:"/home-page/"+t})}},mounted:function(){document.addEventListener("click",this.disappear,!1)},destroyed:function(){document.addEventListener("click",this.disappear,!1)},beforeRouteLeave:function(t,e,s){s()}}},CFQY:function(t,e,s){"use strict";s.r(e);var i=s("pGNL"),a=s("DhNJ");for(var r in a)"default"!==r&&function(t){s.d(e,t,(function(){return a[t]}))}(r);var n=s("KHd+"),o=Object(n.a)(a.default,i.a,i.b,!1,null,null,null);e.default=o.exports},DhNJ:function(t,e,s){"use strict";s.r(e);var i=s("xry+"),a=s.n(i);for(var r in i)"default"!==r&&function(t){s.d(e,t,(function(){return i[t]}))}(r);e.default=a.a},pGNL:function(t,e,s){"use strict";var i=function(){var t=this,e=t.$createElement,s=t._self._c||e;return s("section",[s("div",[s("van-checkbox-group",{ref:"checkboxGroup",model:{value:t.result,callback:function(e){t.result=e},expression:"result"}},[t._l(t.themeList,(function(e,i){return s("div",{key:i},[s("div",{staticClass:"cirPostCon"},[s("div",{},[s("div",{staticClass:"postTop"},[s("div",{staticClass:"postPer"},[e.user&&e.user._data.avatarUrl?s("img",{staticClass:"postHead",attrs:{src:e.user._data.avatarUrl},on:{click:function(s){return t.jumpPerDet(e.user._data.id)}}}):s("img",{staticClass:"postHead",attrs:{src:t.appConfig.staticBaseUrl+"/images/noavatar.gif"},on:{click:function(s){return t.jumpPerDet(e.user._data.id)}}}),t._v(" "),s("div",{staticClass:"perDet"},[e.user?s("div",{staticClass:"perName",on:{click:function(s){return t.jumpPerDet(e.user._data.id)}}},[t._v(t._s(e.user._data.username))]):s("div",{staticClass:"perName"},[t._v("该用户已被删除")]),t._v(" "),s("div",{staticClass:"postTime"},[t._v(t._s(t.$moment(e._data.createdAt).format("YYYY-MM-DD HH:mm")))])])]),t._v(" "),s("div",{staticClass:"postOpera"},[e._data.isSticky?s("span",{directives:[{name:"show",rawName:"v-show",value:t.isTopShow,expression:"isTopShow"}],staticClass:"icon iconfont icon-top"}):t._e(),t._v(" "),t.isMoreShow&&(e._data.canEssence||e._data.canSticky||e._data.canDelete||e._data.canEdit||e.firstPost._data.canLike)?s("div",{ref:"screenDiv",refInFor:!0,staticClass:"screen",on:{click:function(e){return e.stopPropagation(),t.bindScreen(i,e)}}},[s("div",{staticClass:"moreCli"},[s("span",{staticClass:"icon iconfont icon-more"})]),t._v(" "),s("div",{directives:[{name:"show",rawName:"v-show",value:t.indexlist==i,expression:"indexlist==index"}],staticClass:"themeList"},[e.firstPost._data.canLike&&e.firstPost._data.isLiked?s("a",{attrs:{href:"javascript:;"},on:{click:function(s){return t.replyOpera(e.firstPost._data.id,2,e.firstPost._data.isLiked,i)}}},[t._v("取消点赞")]):t._e(),t._v(" "),e.firstPost._data.canLike&&!e.firstPost._data.isLiked?s("a",{attrs:{href:"javascript:;"},on:{click:function(s){return t.replyOpera(e.firstPost._data.id,2,e.firstPost._data.isLiked,i)}}},[t._v("点赞")]):t._e(),t._v(" "),e._data.canEssence&&e._data.isEssence?s("a",{attrs:{href:"javascript:;"},on:{click:function(s){return t.themeOpera(e._data.id,3,e._data.isEssence,i)}}},[t._v("取消加精")]):t._e(),t._v(" "),e._data.canEssence&&!e._data.isEssence?s("a",{attrs:{href:"javascript:;"},on:{click:function(s){return t.themeOpera(e._data.id,3,e._data.isEssence,i)}}},[t._v("加精")]):t._e(),t._v(" "),e._data.canSticky&&e._data.isSticky?s("a",{attrs:{href:"javascript:;"},on:{click:function(s){return t.themeOpera(e._data.id,4,e._data.isSticky,i)}}},[t._v("取消置顶")]):t._e(),t._v(" "),e._data.canSticky&&!e._data.isSticky?s("a",{attrs:{href:"javascript:;"},on:{click:function(s){return t.themeOpera(e._data.id,4,e._data.isSticky,i)}}},[t._v("置顶")]):t._e(),t._v(" "),e.firstPost._data.canEdit?s("a",{attrs:{href:"javascript:;"},on:{click:function(s){return t.themeOpera(e._data.id,6,e._data.isLongArticle)}}},[t._v("编辑")]):t._e(),t._v(" "),e._data.canReply?s("a",{attrs:{href:"javascript:;"},on:{click:function(s){return t.themeOpera(e._data.id,7)}}},[t._v("回复")]):t._e(),t._v(" "),e._data.canDelete?s("a",{attrs:{href:"javascript:;"},on:{click:function(s){return t.themeOpera(e._data.id,5,"",i)}}},[t._v("删除")]):t._e()])]):t._e()])]),t._v(" "),e.firstPost&&e._data.isLongArticle?s("div",{staticClass:"postContent listPostCon",on:{click:function(s){return t.jumpThemeDet(e._data.id,e._data.canViewPosts)}}},[s("span",{staticClass:"postConTitle"},[t._v(t._s(e._data.title))]),t._v(" "),e._data.isLongArticle&&e._data.price<=0?s("span",{staticClass:"icon iconfont icon-longtext"}):e._data.price>0?s("span",{staticClass:"icon iconfont icon-money1"}):t._e()]):e.firstPost&&!e._data.isLongArticle?s("div",{staticClass:"postContent",domProps:{innerHTML:t._s(e.firstPost._data.contentHtml)},on:{click:function(s){return t.jumpThemeDet(e._data.id,e._data.canViewPosts)}}}):t._e(),t._v(" "),e.firstPost.imageList&&e.firstPost.imageList.length>0?s("div",{staticClass:"themeImgBox",on:{click:function(s){return t.jumpThemeDet(e._data.id,e._data.canViewPosts)}}},[s("div",{staticClass:"themeImgList moreImg"},t._l(e.firstPost.imageList,(function(e,i){return i<9?s("van-image",{key:i,staticClass:"themeImgChild",attrs:{fit:"cover","lazy-load":"",src:e}}):t._e()})),1)]):t._e()]),t._v(" "),s("div",{staticClass:"operaBox"},[e.firstPost.likedUsers.length>0||e.rewardedUsers.length>0?s("div",{staticClass:"isrelationGap"}):t._e(),t._v(" "),e.firstPost.likedUsers.length>0?s("div",{staticClass:"likeBox"},[s("span",{staticClass:"icon iconfont icon-praise-after"}),t._v(" "),s("span",{domProps:{innerHTML:t._s(t.userArr(e.firstPost.likedUsers))}}),t._v(" "),e.firstPost._data.likeCount>10?s("i",[t._v(" 等"),s("span",[t._v(t._s(e.firstPost._data.likeCount))]),t._v("个人觉得很赞")]):t._e()]):t._e(),t._v(" "),e.rewardedUsers.length>0?s("div",{staticClass:"reward"},[s("span",{staticClass:"icon iconfont icon-money"}),t._v(" "),s("span",{domProps:{innerHTML:t._s(t.userArr(e.rewardedUsers))}})]):t._e(),t._v(" "),e.lastThreePosts.length>0&&e.firstPost.likedUsers.length>0||e.lastThreePosts.length>0&&e.rewardedUsers.length>0?s("div",{staticClass:"isrelationLine"}):t._e(),t._v(" "),e.lastThreePosts.length>0?s("div",{staticClass:"replyBox"},[t._l(e.lastThreePosts,(function(e,i){return s("div",{key:i,staticClass:"replyCon"},[e.user?s("a",{attrs:{href:"javascript:;"},on:{click:function(s){return t.jumpPerDet(e.user._data.id)}}},[t._v(t._s(e.user._data.username))]):s("a",{attrs:{href:"javascript:;"}},[t._v("该用户已被删除")]),t._v(" "),e._data.replyUserId?s("span",{staticClass:"font9"},[t._v("回复")]):t._e(),t._v(" "),e._data.replyUserId&&e.replyUser?s("a",{attrs:{href:"javascript:;"},on:{click:function(s){return t.jumpPerDet(e.user._data.id)}}},[t._v(t._s(e.replyUser._data.username))]):e._data.replyUserId&&!e.replyUser?s("a",{attrs:{href:"javascript:;"}},[t._v("该用户已被删除")]):t._e(),t._v(" "),s("span",{domProps:{innerHTML:t._s(e._data.contentHtml)}})])})),t._v(" "),e._data.postCount>4?s("a",{staticClass:"allReply",on:{click:function(s){return t.jumpThemeDet(e._data.id,e._data.canViewPosts)}}},[t._v("全部"+t._s(e._data.postCount-1)+"条回复"),s("span",{staticClass:"icon iconfont icon-right-arrow"})]):t._e()],2):t._e()]),t._v(" "),t.ischeckShow?s("van-checkbox",{ref:"checkboxes",refInFor:!0,staticClass:"memberCheck",attrs:{name:e._data.id}}):t._e()],1),t._v(" "),s("div",{staticClass:"gap"})])})),t._v(" "),t.ischeckShow?s("div",{staticClass:"manageFootFixed choFixed",style:{width:t.isPhone||t.isWeixin?"100%":"640px",left:t.isPhone||t.isWeixin?"0":(t.viewportWidth-640)/2+"px"}},[s("a",{attrs:{href:"javascript:;"},on:{click:t.checkAll}},[t._v("全选")]),t._v(" "),s("a",{attrs:{href:"javascript:;"},on:{click:t.signOutDele}},[t._v("取消全选")]),t._v(" "),s("button",{staticClass:"checkSubmit",on:{click:t.deleteAllClick}},[t._v("删除选中")])]):t._e()],2)],1),t._v(" "),s("van-image-preview",{attrs:{images:t.priview},on:{change:t.onChange},scopedSlots:t._u([{key:"index",fn:function(){return[t._v("第"+t._s(t.index)+"页")]},proxy:!0}]),model:{value:t.imageShow,callback:function(e){t.imageShow=e},expression:"imageShow"}})],1)},a=[];s.d(e,"a",(function(){return i})),s.d(e,"b",(function(){return a}))},"xry+":function(t,e,s){"use strict";Object.defineProperty(e,"__esModule",{value:!0});var i=r(s("QbLZ")),a=r(s("/Zpk"));function r(t){return t&&t.__esModule?t:{default:t}}s("iUmJ"),s("N960"),e.default=(0,i.default)({name:"themeDetView"},a.default)}}]);