(window.webpackJsonp=window.webpackJsonp||[]).push([[74],{Y29O:function(t,n,e){"use strict";Object.defineProperty(n,"__esModule",{value:!0});var i=o(e("4gYi")),a=o(e("pNQN"));function o(t){return t&&t.__esModule?t:{default:t}}n.default={data:function(){return{loginStatus:"default",tableData:[{name:"H5微信授权登录",type:"h5",description:"用户在电脑网页使用微信扫码登录或微信内的H5、小程序使用微信授权登录",status:!0,icon:"iconH"},{name:"小程序微信授权登录",type:"applets",description:"用户在电脑网页使用微信扫码登录或微信内的H5、小程序使用微信授权登录",status:!1,icon:"iconxiaochengxu"},{name:"PC端微信扫码登录",type:"pc",description:"用户在PC的网页使用微信扫码登录",status:!0,icon:"iconweixin"}]}},methods:{configClick:function(t){switch(console.log(t),t){case"h5":this.$router.push({path:"/admin/worth-mentioning-config/h5wx"});break;case"applets":this.$router.push({path:"/admin/worth-mentioning-config/applets"});break;case"pc":this.$router.push({path:"/admin/worth-mentioning-config/pcwx"});break;default:this.$router.push({path:"/admin/worth-mentioning-set"})}}},components:{Card:i.default,CardRow:a.default}}},dKQ8:function(t,n,e){"use strict";e.r(n);var i=e("iNRT"),a=e.n(i);for(var o in i)"default"!==o&&function(t){e.d(n,t,(function(){return i[t]}))}(o);n.default=a.a},"e/0E":function(t,n,e){"use strict";e.r(n);var i=e("mIIu"),a=e("dKQ8");for(var o in a)"default"!==o&&function(t){e.d(n,t,(function(){return a[t]}))}(o);var s=e("ZpG+"),u=Object(s.a)(a.default,i.a,i.b,!1,null,null,null);n.default=u.exports},iNRT:function(t,n,e){"use strict";Object.defineProperty(n,"__esModule",{value:!0});var i=o(e("bS4n")),a=o(e("Y29O"));function o(t){return t&&t.__esModule?t:{default:t}}e("RXJm"),n.default=(0,i.default)({name:"worth-mentioning-set-view"},a.default)},mIIu:function(t,n,e){"use strict";var i=function(){var t=this,n=t.$createElement,e=t._self._c||n;return e("div",{staticClass:"worth-mention-box"},[e("div",{staticClass:"worth-mention__default"},[e("el-table",{staticStyle:{width:"100%"},attrs:{data:t.tableData}},[e("el-table-column",{attrs:{prop:"date",label:"第三方登录类型"},scopedSlots:t._u([{key:"default",fn:function(n){return[e("i",{staticClass:"iconfont table-icon",class:n.row.icon}),t._v(" "),e("div",{staticClass:"table-con-box"},[e("p",[t._v(t._s(n.row.name))]),t._v(" "),e("p",[t._v(t._s(n.row.description))])])]}}])}),t._v(" "),e("el-table-column",{attrs:{prop:"name",label:"状态",width:"100",align:"center"},scopedSlots:t._u([{key:"default",fn:function(t){return[t.row.status?e("span",{staticClass:"iconfont iconicon_select"}):e("span",{staticClass:"iconfont iconicon_"})]}}])}),t._v(" "),e("el-table-column",{attrs:{prop:"address",label:"操作",width:"180"},scopedSlots:t._u([{key:"default",fn:function(n){return[n.row.status?e("div",[e("el-button",{attrs:{size:"mini"},on:{click:function(e){return t.configClick(n.row.type)}}},[t._v("配置")]),t._v(" "),e("el-button",{attrs:{size:"mini"}},[t._v("关闭")])],1):e("el-button",{attrs:{size:"mini"}},[t._v("开启")])]}}])})],1)],1)])},a=[];e.d(n,"a",(function(){return i})),e.d(n,"b",(function(){return a}))}}]);