(window.webpackJsonp=window.webpackJsonp||[]).push([[31],{"3AWV":function(e,t,a){"use strict";Object.defineProperty(t,"__esModule",{value:!0});var n=o(a("QbLZ"));a("iUmJ");var s=o(a("zkMY"));function o(e){return e&&e.__esModule?e:{default:e}}t.default=(0,n.default)({name:"login-sign-up-footer"},s.default)},"8nXa":function(e,t,a){"use strict";a.r(t);var n=a("zFIK"),s=a.n(n);for(var o in n)"default"!==o&&function(e){a.d(t,e,(function(){return n[e]}))}(o);t.default=s.a},K59J:function(e,t,a){"use strict";var n=function(){var e=this,t=e.$createElement,a=e._self._c||t;return a("div",{staticClass:"wx-sign-up-bd-box"},[a("LoginHeader"),e._v(" "),a("main",{staticClass:"wx-sign-up-bd-main"},[e._m(0),e._v(" "),a("form",{staticClass:"wx-sign-up-bd-form",attrs:{action:""}},[a("van-cell-group",[a("van-field",{attrs:{clearable:"",label:"用户名",placeholder:"请输入您的用户名"},model:{value:e.userName,callback:function(t){e.userName=t},expression:"userName"}}),e._v(" "),a("van-field",{attrs:{type:"password",label:"密码",placeholder:"请填写密码"},model:{value:e.password,callback:function(t){e.password=t},expression:"password"}})],1)],1),e._v(" "),a("div",{staticClass:"wx-sign-up-bd-btn"},[a("van-button",{attrs:{type:"primary"},on:{click:e.signUpBdClick}},[e._v("注册并绑定")])],1)]),e._v(" "),a("LoginFooter")],1)},s=[function(){var e=this.$createElement,t=this._self._c||e;return t("div",{staticClass:"wx-sign-up-bd-title-box"},[t("h2",{staticClass:"wx-sign-up-bd-title-box-h2"},[this._v("微信绑定账号")]),this._v(" "),t("div",{staticClass:"wx-sign-up-main-desc"},[this._v("您的微信号未绑定账号，请注册并绑定您的账号")])])}];a.d(t,"a",(function(){return n})),a.d(t,"b",(function(){return s}))},NdMT:function(e,t,a){},Ra63:function(e,t,a){"use strict";var n=function(){var e=this,t=e.$createElement,a=e._self._c||t;return a("footer",{staticClass:"login-user-footer"},["login-user"===e.pageName||"login-phone"===e.pageName?[e.qcloudSms?a("span",{on:{click:e.retrieveClick}},[e._v("忘记密码？找回")]):e._e(),e._v(" "),e.registerClose&&e.qcloudSms?a("i"):e._e(),e._v(" "),e.registerClose?a("span",{on:{click:e.signUpClick}},[e._v("注册")]):e._e()]:"wx-login-bd"===e.pageName?[a("span",{on:{click:e.wxSignUpBdClick}},[e._v("没有账号？注册，绑定微信新账号")])]:"wx-sign-up-bd"===e.pageName?[a("span",{on:{click:e.wxLoginBdClick}},[e._v("已有账号？登录，微信绑定账号")])]:"sign-up"===e.pageName?[a("span",{on:{click:e.loginClick}},[e._v("已有账号立即登录")])]:"bind-phone"===e.pageName?[a("span",{on:{click:e.homeClick}},[e._v(e._s("pay"===e.siteMode?"跳过，进入支付费用":"跳过，进入首页"))])]:(e.pageName,[a("span")])],2)},s=[];a.d(t,"a",(function(){return n})),a.d(t,"b",(function(){return s}))},UjaL:function(e,t,a){"use strict";a.r(t);var n=a("Ra63"),s=a("pz4+");for(var o in s)"default"!==o&&function(e){a.d(t,e,(function(){return s[e]}))}(o);var i=a("KHd+"),r=Object(i.a)(s.default,n.a,n.b,!1,null,null,null);t.default=r.exports},nWjw:function(e,t,a){"use strict";Object.defineProperty(t,"__esModule",{value:!0});var n=r(a("JZuw")),s=r(a("UjaL")),o=r(a("VVfg")),i=r(a("6NK7"));function r(e){return e&&e.__esModule?e:{default:e}}t.default={data:function(){return{userName:"",password:"",phoneStatus:"",siteMode:"",openid:"",platform:""}},components:{LoginHeader:n.default,LoginFooter:s.default},methods:{signUpBdClick:function(){var e=this;this.appFetch({url:"register",method:"post",data:{data:{type:"users",attributes:{username:this.userName,password:this.password,openid:this.openid,platform:this.platform}}}}).then((function(t){if(console.log(t),t.errors)t.errors[0].detail?e.$toast.fail(t.errors[0].code+"\n"+t.errors[0].detail[0]):e.$toast.fail(t.errors[0].code);else{e.$toast.success("注册成功");var a=t.data.attributes.access_token,n=t.data.id,s=t.data.attributes.refresh_token;o.default.setLItem("Authorization",a),o.default.setLItem("tokenId",n),o.default.setLItem("refreshToken",s),e.getForum().then((function(){e.phoneStatus?e.$router.push({path:"bind-phone"}):"pay"===e.siteMode?e.$router.push({path:"pay-the-fee"}):"public"===e.siteMode?e.$router.push({path:"/"}):console.log("缺少参数，请刷新页面")}))}})).catch((function(e){console.log(e)}))},getForum:function(){var e=this;return this.appFetch({url:"forum",method:"get",data:{}}).then((function(t){console.log(t),t.errors?e.$toast.fail(t.errors[0].code):(e.phoneStatus=t.readdata._data.qcloud.qcloud_sms,e.siteMode=t.readdata._data.set_site.site_mode)})).catch((function(e){console.log(e)}))}},created:function(){this.getForum(),this.openid=o.default.getLItem("openid");var e=i.default.isWeixin().isWeixin;this.platform=e?"mp":"dev"}}},oe1W:function(e,t,a){"use strict";a.r(t);var n=a("K59J"),s=a("8nXa");for(var o in s)"default"!==o&&function(e){a.d(t,e,(function(){return s[e]}))}(o);var i=a("KHd+"),r=Object(i.a)(s.default,n.a,n.b,!1,null,null,null);t.default=r.exports},"pz4+":function(e,t,a){"use strict";a.r(t);var n=a("3AWV"),s=a.n(n);for(var o in n)"default"!==o&&function(e){a.d(t,e,(function(){return n[e]}))}(o);t.default=s.a},zFIK:function(e,t,a){"use strict";Object.defineProperty(t,"__esModule",{value:!0});var n=o(a("QbLZ"));a("NdMT");var s=o(a("nWjw"));function o(e){return e&&e.__esModule?e:{default:e}}t.default=(0,n.default)({name:"wx-sign-up-bd"},s.default)},zkMY:function(e,t,a){"use strict";Object.defineProperty(t,"__esModule",{value:!0});var n,s=a("VVfg"),o=(n=s)&&n.__esModule?n:{default:n};t.default={data:function(){return{pageName:"login",siteMode:"",registerClose:!0,qcloudSms:!0}},methods:{retrieveClick:function(){this.$router.push("retrieve-pwd")},signUpClick:function(){this.$router.push("sign-up")},wxSignUpBdClick:function(){this.$router.push("/wx-sign-up-bd")},wxLoginBdClick:function(){this.$router.push("/wx-login-bd")},loginClick:function(){this.$router.push("/login-user")},homeClick:function(){switch(this.siteMode){case"pay":this.$router.push({path:"pay-the-fee"});break;case"public":this.$router.push({path:"/"});break;default:console.log("参数错误，请重新刷新页面")}},getForum:function(){var e=this;this.appFetch({url:"forum",method:"get",data:{}}).then((function(t){console.log(t),e.siteMode=t.readdata._data.set_site.site_mode,e.registerClose=t.readdata._data.set_reg.register_close,e.qcloudSms=t.readdata._data.qcloud.qcloud_sms,o.default.setLItem("siteInfo",t.readdata)})).catch((function(e){console.log(e)}))}},created:function(){this.pageName=this.$router.history.current.name,this.getForum()}}}}]);