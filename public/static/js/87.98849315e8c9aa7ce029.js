(window.webpackJsonp=window.webpackJsonp||[]).push([[87],{"5JD/":function(t,e,o){"use strict";o.r(e);var s=o("HE2v"),i=o.n(s);for(var n in s)"default"!==n&&function(t){o.d(e,t,(function(){return s[t]}))}(n);e.default=i.a},HE2v:function(t,e,o){"use strict";Object.defineProperty(e,"__esModule",{value:!0});var s=n(o("QbLZ"));o("XCsR"),o("i4TU");var i=n(o("SHGB"));function n(t){return t&&t.__esModule?t:{default:t}}e.default=(0,s.default)({name:"login-view"},i.default)},SHGB:function(t,e,o){"use strict";Object.defineProperty(e,"__esModule",{value:!0});var s=l(o("QbLZ")),i=l(o("JZuw")),n=l(o("UjaL")),a=l(o("VVfg")),r=o("L2JU");function l(t){return t&&t.__esModule?t:{default:t}}e.default={data:function(){return{userName:"",password:"",userId:"2",btnLoading:!1,wxLoginShow:!0,isOne:!1,siteMode:"",phoneStatus:"",wxHref:"",isPC:!1,isCodeState:0,wxStatus:""}},computed:(0,r.mapState)({status:function(t){return t.appSiteModule.status}}),mounted:function(){},methods:(0,s.default)({},(0,r.mapMutations)({setStatus:"appSiteModule/SET_STATUS",setOpenId:"appSiteModule/SET_OPENID"}),{loginClick:function(){var t=this;this.setStatus("啊啦啦啦"),this.appFetch({url:"login",method:"post",data:{data:{attributes:{username:this.userName,password:this.password}}}}).then((function(e){if(console.log(e),e.errors)e.errors[0].detail?t.$toast.fail(e.errors[0].code+"\n"+e.errors[0].detail[0]):t.$toast.fail(e.errors[0].code);else{t.$toast.success("登录成功");var o=e.data.attributes.access_token,s=e.data.id,i=e.data.attributes.refresh_token;a.default.setLItem("Authorization",o),a.default.setLItem("tokenId",s),a.default.setLItem("refreshToken",i),t.getUsers(s).then((function(e){if(e.errors){var o=t.appCommonH.errorHandling(e.errors,!0);t.$toast.fail(o[0].errorDetail)}else e.readdata._data.paid?t.$router.push({path:"/"}):"pay"===t.siteMode?t.$router.push({path:"pay-circle-login"}):"public"===t.siteMode?t.$router.push({path:"/"}):console.log("缺少参数，请刷新页面")}))}})).catch((function(t){console.log(t)}))},loginWxClick:function(){var t=this;this.getWxLogin().then((function(e){e.errors?t.$toast.fail(e.errors[0].code):window.location.href=e.readdata._data.location}))},loginPhoneClick:function(){this.$router.push({path:"/login-phone"})},getForum:function(){var t=this;this.appFetch({url:"forum",method:"get",data:{}}).then((function(e){console.log(e),t.phoneStatus=e.readdata._data.qcloud.qcloud_sms,t.siteMode=e.readdata._data.setsite.site_mode,a.default.setLItem("siteInfo",e.readdata)})).catch((function(t){console.log(t)}))},getUsers:function(t){var e=this;return this.appFetch({url:"users",method:"get",splice:"/"+t,headers:{Authorization:"Bearer "+a.default.getLItem("Authorization")},data:{include:["groups"]}}).then((function(t){if(console.log(t),!t.errors)return t;e.$toast.fail(t.errors[0].code)})).catch((function(t){console.log(t)}))},getWxLogin:function(){return this.appFetch({url:"wxLogin",method:"get",data:{}}).then((function(t){return console.log(t),t})).catch((function(t){console.log(t)}))}}),created:function(){localStorage.clear();var t=this.appCommonH.isWeixin().isWeixin,e=this.appCommonH.isWeixin().isPhone;console.log(this.$router.history),console.log(this.$router.history.current.query.code),console.log(this.$router.history.current.query.state),!0===t?console.log("微信登录"):!0===e?(console.log("手机浏览器登录"),this.wxLoginShow=!1,this.isOne=!0):(console.log("pc登录"),this.isPC=!0),this.getForum()},components:{LoginHeader:i.default,LoginFooter:n.default}}},bVx2:function(t,e,o){"use strict";var s=function(){var t=this,e=t.$createElement,o=t._self._c||e;return o("div",{staticClass:"login-user-box"},[o("LoginHeader"),t._v(" "),o("main",{staticClass:"login-user-box-main"},[t._m(0),t._v(" "),o("form",{staticClass:"user-login-form login-module-form"},[o("van-cell-group",[o("van-field",{attrs:{clearable:"",label:"用户名",placeholder:"请输入您的用户名"},model:{value:t.userName,callback:function(e){t.userName=e},expression:"userName"}}),t._v(" "),o("van-field",{attrs:{type:"password",clearable:"",label:"密码",placeholder:"请填写密码"},model:{value:t.password,callback:function(e){t.password=e},expression:"password"}})],1)],1),t._v(" "),o("div",{staticClass:"login-user-btn"},[o("van-button",{attrs:{type:"primary",loading:t.btnLoading,"loading-text":"登录中..."},on:{click:t.loginClick}},[t._v("登录")])],1),t._v(" "),o("div",{staticClass:"login-user-method"},[o("div",{staticClass:"login-user-method-box"},[o("van-divider",{directives:[{name:"show",rawName:"v-show",value:t.phoneStatus||t.wxLoginShow,expression:"phoneStatus ||  wxLoginShow"}]},[t._v("其他登录方式")])],1),t._v(" "),o("div",{staticClass:"login-user-method-icon"},[o("div",{staticClass:"login-user-method-icon-box",class:{justifyCenter:t.isOne}},[t.phoneStatus?o("i",{staticClass:"login-user-method-icon-ring iconfont",on:{click:t.loginPhoneClick}},[o("span",{staticClass:"icon iconfont icon-shouji",staticStyle:{color:"rgba(136, 136, 136, 1)"}})]):t._e(),t._v(" "),o("i",{directives:[{name:"show",rawName:"v-show",value:t.wxLoginShow,expression:"wxLoginShow"}],staticClass:"login-user-method-icon-ring iconfont",on:{click:t.loginWxClick}},[o("span",{staticClass:"icon iconfont icon-weixin",staticStyle:{color:"rgba(136, 136, 136, 1)"}})])])])])]),t._v(" "),o("LoginFooter")],1)},i=[function(){var t=this.$createElement,e=this._self._c||t;return e("div",{staticClass:"login-user-title-box login-module-title-box"},[e("p",{staticClass:"login-user-title-p login-module-title"},[this._v("用户名登录")])])}];o.d(e,"a",(function(){return s})),o.d(e,"b",(function(){return i}))},lEHL:function(t,e,o){"use strict";o.r(e);var s=o("bVx2"),i=o("5JD/");for(var n in i)"default"!==n&&function(t){o.d(e,t,(function(){return i[t]}))}(n);var a=o("KHd+"),r=Object(a.a)(i.default,s.a,s.b,!1,null,null,null);e.default=r.exports}}]);