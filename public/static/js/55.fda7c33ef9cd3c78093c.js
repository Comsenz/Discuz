(window.webpackJsonp=window.webpackJsonp||[]).push([[55],{EyOG:function(e,r,t){"use strict";Object.defineProperty(r,"__esModule",{value:!0});var a=i(t("14Xm")),s=i(t("D3Ub")),n=i(t("VVfg"));function i(e){return e&&e.__esModule?e:{default:e}}r.default={data:function(){return{searchVal:"",userParams:{"filter[type]":"2","page[limit]":20,"page[number]":1,include:"fromUser"},firstComeIn:!0,searchUserList:[],searchThemeList:[],userLoadMoreStatus:!1,userLoadMorePageChange:!1,userLoading:!1,themeLoading:!1,timerSearch:null,searchMaxSum:3}},created:function(){this.handleSearchUser();var e="";this.$route.query&&this.$route.query.searchWord&&(e=this.$route.query.searchWord),this.onSearch(e)},methods:{onSearch:function(e){var r=this;clearTimeout(this.timerSearch),this.searchVal=e,this.timerSearch=setTimeout((function(){r.firstComeIn=!1,r.userParams["filter[username]"]=r.searchVal,r.userParams["page[number]"]=1,r.handleSearchUser(!0)}),200)},onCancel:function(){},handleSearchUser:function(){var e=this,r=arguments.length>0&&void 0!==arguments[0]&&arguments[0];return(0,s.default)(a.default.mark((function t(){var s;return a.default.wrap((function(t){for(;;)switch(t.prev=t.next){case 0:if(r&&(e.searchUserList=[]),!e.userLoading){t.next=3;break}return t.abrupt("return");case 3:return e.userLoading=!0,t.prev=4,s=e.userParams["page[number]"],t.next=8,e.appFetch({url:"follow",methods:"get",data:e.userParams}).then((function(r){e.userLoadMoreStatus=r.readdata.length>e.searchMaxSum,e.searchUserList=r.readdata})).catch((function(r){e.userLoadMorePageChange&&e.userParams["page[number]"]>1&&(e.userParams["page[number]"]=s-1)}));case 8:return t.prev=8,e.userLoadMorePageChange=!1,e.userLoading=!1,t.finish(8);case 12:case"end":return t.stop()}}),t,e,[[4,,8,12]])})))()},followSwitch:function(e,r,t){n.default.setSItem("beforeVisiting",this.$route.path);var a=new Object,s="";"0"==e?(a.to_user_id=r,s="post"):"1"==e&&(a.to_user_id=r,s="delete"),this.followRequest(s,a,e,t)},followRequest:function(e,r,t,a){var s=this;this.appFetch({url:"follow",method:e,data:{data:{type:"user_follow",attributes:r}}}).then((function(r){if(r.errors)throw s.$toast.fail(r.errors[0].code),new Error(r.error);s.searchUserList[a]._data.is_mutual="delete"==e?"0":"1"}))},jumpPerDet:function(e){this.$router.push({path:"/home-page/"+e})}},mounted:function(){},beforeRouteLeave:function(e,r,t){t()}}},RFdJ:function(e,r,t){"use strict";var a=t("Zdw9");t.n(a).a},Vjof:function(e,r,t){"use strict";t.r(r);var a=t("o4sJ"),s=t("vjib");for(var n in s)"default"!==n&&function(e){t.d(r,e,(function(){return s[e]}))}(n);t("RFdJ");var i=t("KHd+"),o=Object(i.a)(s.default,a.a,a.b,!1,null,"4537a153",null);r.default=o.exports},Zdw9:function(e,r,t){},o4sJ:function(e,r,t){"use strict";var a=function(){var e=this,r=e.$createElement,t=e._self._c||r;return t("div",{staticClass:"searchBox"},[t("comHeader",{attrs:{title:"关注我的人"}}),e._v(" "),t("div",{staticClass:"content"},[t("form",{attrs:{action:"/"}},[t("van-search",{staticClass:"searchCon",attrs:{placeholder:"搜索关注我的人",background:"#f8f8f8"},on:{input:e.onSearch,cancel:e.onCancel},model:{value:e.searchVal,callback:function(r){e.searchVal=r},expression:"searchVal"}})],1),e._v(" "),t("div",{directives:[{name:"show",rawName:"v-show",value:e.searchUserList.length>0,expression:"searchUserList.length > 0"}],staticClass:"searchRes"},e._l(e.searchUserList,(function(r,a){return t("div",{key:a,staticClass:"resUser"},[r.fromUser._data.avatarUrl?t("img",{staticClass:"resUserHead",attrs:{src:r.fromUser._data.avatarUrl},on:{click:function(t){return e.jumpPerDet(r.fromUser._data.id)}}}):t("img",{staticClass:"resUserHead",attrs:{src:e.appConfig.staticBaseUrl+"/images/noavatar.gif"},on:{click:function(t){return e.jumpPerDet(r.fromUser._data.id)}}}),e._v(" "),t("div",{staticClass:"resUserDet"},[t("span",{staticClass:"resUserName",domProps:{innerHTML:e._s(r.fromUser._data.username.replace(e.searchVal,"<i>"+e.searchVal+"</i>"))}}),e._v(" "),"0"==r._data.is_mutual?t("a",{staticClass:"followHe",attrs:{href:"javascript:;"},on:{click:function(t){return e.followSwitch(r._data.is_mutual,r.fromUser._data.id,a)}}},[e._v("关注TA")]):t("a",{staticClass:"alreadFollow",attrs:{href:"javascript:;"},on:{click:function(t){return e.followSwitch(r._data.is_mutual,r.fromUser._data.id,a)}}},[e._v("相互关注")])])])})),0),e._v(" "),t("div",{directives:[{name:"show",rawName:"v-show",value:0===e.searchUserList.length&&!e.firstComeIn,expression:"searchUserList.length === 0 && !firstComeIn"}],staticClass:"nullTip whiteBg"},[e._v("\n      暂无关注\n    ")])])],1)},s=[];t.d(r,"a",(function(){return a})),t.d(r,"b",(function(){return s}))},"ohl/":function(e,r,t){"use strict";Object.defineProperty(r,"__esModule",{value:!0});var a=i(t("QbLZ")),s=i(t("JZuw")),n=i(t("EyOG"));function i(e){return e&&e.__esModule?e:{default:e}}t("iUmJ"),t("N960"),t("p+Ry"),r.default=(0,a.default)({name:"careMeView",components:{comHeader:s.default}},n.default)},"p+Ry":function(e,r,t){},vjib:function(e,r,t){"use strict";t.r(r);var a=t("ohl/"),s=t.n(a);for(var n in a)"default"!==n&&function(e){t.d(r,e,(function(){return a[e]}))}(n);r.default=s.a}}]);