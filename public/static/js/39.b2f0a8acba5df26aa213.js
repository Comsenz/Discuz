(window.webpackJsonp=window.webpackJsonp||[]).push([[39],{"3AWV":function(t,e,a){"use strict";Object.defineProperty(e,"__esModule",{value:!0});var s=r(a("QbLZ"));a("iUmJ");var i=r(a("zkMY"));function r(t){return t&&t.__esModule?t:{default:t}}e.default=(0,s.default)({name:"login-sign-up-footer"},i.default)},"8MtQ":function(t,e,a){"use strict";var s=function(){var t=this,e=t.$createElement,a=t._self._c||e;return a("div",{staticClass:"wx-sign-up-bd-box"},[a("LoginHeader"),t._v(" "),a("main",{staticClass:"wx-sign-up-bd-main"},[t._m(0),t._v(" "),a("form",{staticClass:"wx-sign-up-bd-form",attrs:{action:""}},[a("van-cell-group",[a("van-field",{attrs:{clearable:"",label:"用户名",placeholder:"请输入您的用户名"},model:{value:t.userName,callback:function(e){t.userName=e},expression:"userName"}}),t._v(" "),a("van-field",{attrs:{type:"password",label:"密码",placeholder:"请填写密码"},model:{value:t.password,callback:function(e){t.password=e},expression:"password"}}),t._v(" "),t.signReasonStatus?a("van-field",{attrs:{clearable:"",label:"注册原因",placeholder:"请填写注册原因"},model:{value:t.signReason,callback:function(e){t.signReason=e},expression:"signReason"}}):t._e()],1)],1),t._v(" "),a("div",{staticClass:"wx-sign-up-bd-btn"},[a("van-button",{attrs:{type:"primary"},on:{click:t.signUpBdClick}},[t._v("注册并绑定")])],1)]),t._v(" "),a("LoginFooter")],1)},i=[function(){var t=this.$createElement,e=this._self._c||t;return e("div",{staticClass:"wx-sign-up-bd-title-box"},[e("h2",{staticClass:"wx-sign-up-bd-title-box-h2"},[this._v("微信绑定账号")]),this._v(" "),e("div",{staticClass:"wx-sign-up-main-desc"},[this._v("你的微信号未绑定账号，注册即可完成绑定")])])}];a.d(e,"a",(function(){return s})),a.d(e,"b",(function(){return i}))},"8nXa":function(t,e,a){"use strict";a.r(e);var s=a("zFIK"),i=a.n(s);for(var r in s)"default"!==r&&function(t){a.d(e,t,(function(){return s[t]}))}(r);e.default=i.a},NdMT:function(t,e,a){},Ra63:function(t,e,a){"use strict";var s=function(){var t=this,e=t.$createElement,a=t._self._c||e;return a("footer",{staticClass:"login-user-footer"},["login-user"===t.pageName||"login-phone"===t.pageName?[t.qcloudSms?a("span",{on:{click:t.retrieveClick}},[t._v("忘记密码？找回")]):t._e(),t._v(" "),t.registerClose&&t.qcloudSms?a("i"):t._e(),t._v(" "),t.registerClose?a("span",{on:{click:t.signUpClick}},[t._v("注册")]):t._e()]:"wx-login-bd"===t.pageName?[a("span",{on:{click:t.wxSignUpBdClick}},[t._v("没有账号？注册，绑定微信新账号")])]:"wx-sign-up-bd"===t.pageName?[a("span",{on:{click:t.wxLoginBdClick}},[t._v("已有账号？登录，微信绑定账号")])]:"sign-up"===t.pageName?[a("span",{on:{click:t.loginClick}},[t._v("已有账号立即登录")])]:"bind-phone"===t.pageName?[a("span",{on:{click:t.homeClick}},[t._v(t._s("pay"===t.siteMode?"跳过，进入支付费用":"跳过，进入首页"))])]:(t.pageName,[a("span")])],2)},i=[];a.d(e,"a",(function(){return s})),a.d(e,"b",(function(){return i}))},UjaL:function(t,e,a){"use strict";a.r(e);var s=a("Ra63"),i=a("pz4+");for(var r in i)"default"!==r&&function(t){a.d(e,t,(function(){return i[t]}))}(r);var n=a("KHd+"),o=Object(n.a)(i.default,s.a,s.b,!1,null,null,null);e.default=o.exports},nWjw:function(t,e,a){"use strict";Object.defineProperty(e,"__esModule",{value:!0});var s=o(a("JZuw")),i=o(a("UjaL")),r=o(a("VVfg")),n=o(a("6NK7"));function o(t){return t&&t.__esModule?t:{default:t}}e.default={data:function(){return{userName:"",password:"",phoneStatus:"",siteMode:"",openid:"",platform:"",signReason:"",signReasonStatus:!1}},components:{LoginHeader:s.default,LoginFooter:i.default},methods:{signUpBdClick:function(){this.signReasonStatus&&this.signReason.length<1?this.$toast.fail("请填写注册原因！"):this.setSignData()},getForum:function(){var t=this;return this.appFetch({url:"forum",method:"get",data:{}}).then((function(e){e.errors?t.$toast.fail(e.errors[0].code):(t.phoneStatus=e.readdata._data.qcloud.qcloud_sms,t.siteMode=e.readdata._data.set_site.site_mode,t.signReasonStatus=e.readdata._data.set_reg.register_validate)})).catch((function(t){}))},setSignData:function(){var t=this;this.appFetch({url:"register",method:"post",data:{data:{type:"users",attributes:{username:this.userName,password:this.password,openid:this.openid,platform:this.platform,register_reason:this.signReason}}}}).then((function(e){if(e.errors)e.errors[0].detail?t.$toast.fail(e.errors[0].code+"\n"+e.errors[0].detail[0]):"register_validate"===e.rawData[0].code?t.$router.push({path:"information-page",query:{setInfo:"registrationReview"}}):t.$toast.fail(e.errors[0].code);else{t.$toast.success("注册成功");var a=e.data.attributes.access_token,s=e.data.id,i=e.data.attributes.refresh_token;r.default.setLItem("Authorization",a),r.default.setLItem("tokenId",s),r.default.setLItem("refreshToken",i),t.getForum().then((function(){t.phoneStatus?t.$router.push({path:"bind-phone"}):"pay"===t.siteMode?t.$router.push({path:"pay-the-fee"}):"public"===t.siteMode&&t.$router.push({path:"/"})}))}})).catch((function(t){}))},getWatchHref:function(t,e,a){var s=this;this.appFetch({url:"wechat",method:"get",data:{code:t,state:e,sessionId:a}}).then((function(t){if(t.errors){var e=t.errors[0].status,a=t.errors[0].user.openid;400==e&&(s.openid=a,r.default.setLItem("openid",a),s.$router.push({path:"/wx-sign-up-bd"}))}else if(t.data.attributes.location)s.wxurl=t.data.attributes.location,window.location.href=t.data.attributes.location;else if(t.data.attributes.access_token){s.$toast.success("登录成功");var i=t.data.attributes.access_token,n=t.data.id;r.default.setLItem("Authorization",i),r.default.setLItem("tokenId",n);var o=r.default.getSItem("beforeVisiting");o?(s.$router.replace({path:o}),r.default.setSItem("beforeState",1)):(s.$router.push({path:"/"}),s.$router.go(0))}})).catch((function(t){}))},getWatchHrefPC:function(t,e,a){var s=this;this.appFetch({url:"wxLogin",method:"get",data:{code:t,state:e,sessionId:a}}).then((function(t){if(t.errors){var e=t.errors[0].status,a=t.errors[0].user.openid;400==e&&(s.openid=a,r.default.setLItem("openid",a),s.$router.push({path:"/wx-sign-up-bd"}))}else if(t.data.attributes.location)s.wxurl=t.data.attributes.location,window.location.href=t.data.attributes.location;else if(t.data.attributes.access_token){s.$toast.success("登录成功");var i=t.data.attributes.access_token,n=t.data.id;r.default.setLItem("Authorization",i),r.default.setLItem("tokenId",n);var o=r.default.getSItem("beforeVisiting");o?(s.$router.replace({path:o}),r.default.setSItem("beforeState",1)):(s.$router.push({path:"/"}),s.$router.go(0))}})).catch((function(t){}))}},created:function(){this.getForum(),this.openid=r.default.getLItem("openid");var t=n.default.isWeixin().isWeixin,e=this.$router.history.current.query.code,a=this.$router.history.current.query.state,s=this.$router.history.current.query.sessionId;r.default.setLItem("code",e),r.default.setLItem("state",a),t?(this.platform="mp",e||a?this.getWatchHref(e,a,s):this.getWatchHref()):(this.platform="dev",""===this.openid&&this.getWatchHrefPC(e,a,s))}}},oe1W:function(t,e,a){"use strict";a.r(e);var s=a("8MtQ"),i=a("8nXa");for(var r in i)"default"!==r&&function(t){a.d(e,t,(function(){return i[t]}))}(r);var n=a("KHd+"),o=Object(n.a)(i.default,s.a,s.b,!1,null,null,null);e.default=o.exports},"pz4+":function(t,e,a){"use strict";a.r(e);var s=a("3AWV"),i=a.n(s);for(var r in s)"default"!==r&&function(t){a.d(e,t,(function(){return s[t]}))}(r);e.default=i.a},zFIK:function(t,e,a){"use strict";Object.defineProperty(e,"__esModule",{value:!0});var s=r(a("QbLZ"));a("NdMT");var i=r(a("nWjw"));function r(t){return t&&t.__esModule?t:{default:t}}e.default=(0,s.default)({name:"wx-sign-up-bd"},i.default)},zkMY:function(t,e,a){"use strict";Object.defineProperty(e,"__esModule",{value:!0});var s,i=a("VVfg"),r=(s=i)&&s.__esModule?s:{default:s};e.default={data:function(){return{pageName:"login",siteMode:"",registerClose:!0,qcloudSms:!0}},methods:{retrieveClick:function(){this.$router.push("retrieve-pwd")},signUpClick:function(){this.$router.push("sign-up")},wxSignUpBdClick:function(){this.$router.push("/wx-sign-up-bd")},wxLoginBdClick:function(){this.$router.push("/wx-login-bd")},loginClick:function(){this.$router.push("/login-user")},homeClick:function(){switch(this.siteMode){case"pay":this.$router.push({path:"pay-the-fee"});break;case"public":this.$router.push({path:"/"})}},getForum:function(){var t=this;this.appFetch({url:"forum",method:"get",data:{}}).then((function(e){t.siteMode=e.readdata._data.set_site.site_mode,t.registerClose=e.readdata._data.set_reg.register_close,t.qcloudSms=e.readdata._data.qcloud.qcloud_sms,r.default.setLItem("siteInfo",e.readdata)})).catch((function(t){}))}},created:function(){this.pageName=this.$router.history.current.name,this.getForum()}}}}]);