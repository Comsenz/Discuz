(window.webpackJsonp=window.webpackJsonp||[]).push([[64],{"14lE":function(e,t,r){"use strict";Object.defineProperty(t,"__esModule",{value:!0});var n=a(r("QbLZ")),u=a(r("kwHM"));function a(e){return e&&e.__esModule?e:{default:e}}r("iUmJ"),r("llYx"),t.default=(0,n.default)({name:"my-notice"},u.default)},SE8h:function(e,t,r){"use strict";r.r(t);var n=r("swHT"),u=r("rN9E");for(var a in u)"default"!==a&&function(e){r.d(t,e,(function(){return u[e]}))}(a);var i=r("KHd+"),s=Object(i.a)(u.default,n.a,n.b,!1,null,null,null);t.default=s.exports},kwHM:function(e,t,r){"use strict";Object.defineProperty(t,"__esModule",{value:!0});var n=a(r("JZuw")),u=a(r("VVfg"));function a(e){return e&&e.__esModule?e:{default:e}}t.default={data:function(){return{num:{replied:{title:"回复我的",typeId:1,number:0,routerName:"reply"},rewarded:{title:"打赏我的",typeId:3,number:0,routerName:"reward"},liked:{title:"点赞我的",typeId:2,number:0,routerName:"like"},system:{title:"系统通知",typeId:4,number:0,routerName:"system"}}}},mounted:function(){this.notice()},methods:{myJump:function(e){switch(e){case"reply":this.$router.push("/reply");break;case"reward":this.$router.push("/reward");break;case"like":this.$router.push("/like");break;default:this.$router.push("/system")}},notice:function(){var e=this,t=u.default.getLItem("tokenId");this.appFetch({url:"users",method:"get",splice:"/"+t,standard:!1,data:{}}).then((function(t){if(t.errors)e.$toast.fail(t.errors[0].code);else{var r=t.data.attributes.typeUnreadNotifications;for(var n in r)e.num[n]&&(e.num[n].number=r[n])}}))}},components:{MyNoticeHeader:n.default}}},llYx:function(e,t,r){},rN9E:function(e,t,r){"use strict";r.r(t);var n=r("14lE"),u=r.n(n);for(var a in n)"default"!==a&&function(e){r.d(t,e,(function(){return n[e]}))}(a);t.default=u.a},swHT:function(e,t,r){"use strict";var n=function(){var e=this,t=e.$createElement,r=e._self._c||t;return r("div",{staticClass:"my-notice-box my-info-money-header"},[r("MyNoticeHeader",{attrs:{title:"我的通知"}}),e._v(" "),r("main",{staticClass:"my-notice-main content"},e._l(e.num,(function(t,n){return r("van-cell",{key:n,attrs:{cless:"my-notice-cell","is-link":""},on:{click:function(r){return e.myJump(t.routerName)}}},[r("template",{staticClass:"my-notice-cell-template",slot:"title"},[r("span",{staticClass:"custom-title"},[e._v(e._s(t.title))]),e._v(" "),r("i",{directives:[{name:"show",rawName:"v-show",value:0!==t.number,expression:"item.number === 0 ? false:true"}],staticClass:"custom-title-icon",attrs:{type:"danger"}},[e._v(e._s(t.number))])])],2)})),1),e._v(" "),r("footer",{staticClass:"my-info-money-footer"})],1)},u=[];r.d(t,"a",(function(){return n})),r.d(t,"b",(function(){return u}))}}]);