(window.webpackJsonp=window.webpackJsonp||[]).push([[77],{"5GN5":function(t,e,a){"use strict";Object.defineProperty(e,"__esModule",{value:!0});var r=n(a("QbLZ"));a("zt69");var i=n(a("s9FF"));function n(t){return t&&t.__esModule?t:{default:t}}e.default=(0,r.default)({name:"worth-mentioning-config-view"},i.default)},SnC3:function(t,e,a){"use strict";a.r(e);var r=a("5GN5"),i=a.n(r);for(var n in r)"default"!==n&&function(t){a.d(e,t,(function(){return r[t]}))}(n);e.default=i.a},dY5s:function(t,e,a){"use strict";a.r(e);var r=a("yZwC"),i=a("SnC3");for(var n in i)"default"!==n&&function(t){a.d(e,t,(function(){return i[t]}))}(n);var p=a("KHd+"),o=Object(p.a)(i.default,r.a,r.b,!1,null,null,null);e.default=o.exports},s9FF:function(t,e,a){"use strict";Object.defineProperty(e,"__esModule",{value:!0});var r=n(a("4gYi")),i=n(a("pNQN"));function n(t){return t&&t.__esModule?t:{default:t}}e.default={data:function(){return{loginStatus:"default",appId:"",appSecret:"",type:"",typeCopywriting:{wx_offiaccount:{title:"公众号接口配置",appIdDescription:"填写申请公众号后，你获得的APPID ",appSecretDescription:"填写申请公众号后，你获得的App secret",url:"https://mp.weixin.qq.com/"},wx_miniprogram:{title:"小程序微信授权登录设置",appIdDescription:"填写申请小程序后，你获得的APPID ",appSecretDescription:"填写申请小程序后，你获得的App secret",url:""},wx_oplatform:{title:"PC端微信扫码登录",appIdDescription:"填写申请PC端微信扫码后，你获得的APPID ",appSecretDescription:"填写申请PC端微信扫码后，你获得的App secret",url:""}}}},created:function(){var t=this.$route.query.type;this.type=t,this.loadStatus()},methods:{loadStatus:function(){var t=this;console.log(this.type),this.appFetch({url:"tags",method:"get",splice:"/"+this.type,data:{}}).then((function(e){e.errors?t.$message.error(e.errors[0].code):(t.appId=e.readdata[0]._data.app_id,t.appSecret=e.readdata[0]._data.app_secret)})).catch((function(t){}))},submitConfiguration:function(){var t=this;this.appFetch({url:"settings",method:"post",data:{data:[{attributes:{key:"app_id",value:this.appId,tag:this.type}},{attributes:{key:"app_secret",value:this.appSecret,tag:this.type}}]}}).then((function(e){e.errors?t.$toast.fail(e.errors[0].code):t.$message({message:"提交成功",type:"success"})}))}},components:{Card:r.default,CardRow:i.default}}},yZwC:function(t,e,a){"use strict";var r=function(){var t=this,e=t.$createElement,a=t._self._c||e;return a("div",{staticClass:"worth-mentioning-config-h5-box"},[a("Card",{attrs:{header:t.typeCopywriting[t.type].title}}),t._v(" "),a("Card",{attrs:{header:"APPID："}},[a("CardRow",{attrs:{description:t.typeCopywriting[t.type].appIdDescription},scopedSlots:t._u([{key:"tail",fn:function(){return[a("a",{staticStyle:{"margin-left":"15px"},attrs:{href:t.typeCopywriting[t.type].url,target:"_blank"}},[t._v("未申请？点此申请")])]},proxy:!0}])},[a("el-input",{model:{value:t.appId,callback:function(e){t.appId=e},expression:"appId"}})],1)],1),t._v(" "),a("Card",{attrs:{header:"App secret："}},[a("CardRow",{attrs:{description:t.typeCopywriting[t.type].appSecretDescription}},[a("el-input",{model:{value:t.appSecret,callback:function(e){t.appSecret=e},expression:"appSecret"}})],1)],1),t._v(" "),a("Card",{staticClass:"footer-btn"},[a("el-button",{attrs:{type:"primary",size:"medium"},on:{click:t.submitConfiguration}},[t._v("提交")])],1)],1)},i=[];a.d(e,"a",(function(){return r})),a.d(e,"b",(function(){return i}))}}]);