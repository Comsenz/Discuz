(window.webpackJsonp=window.webpackJsonp||[]).push([[15],{"1BLf":function(t,e,n){"use strict";n.r(e);var i=n("Hb8c"),o=n.n(i);for(var u in i)"default"!==u&&function(t){n.d(e,t,(function(){return i[t]}))}(u);e.default=o.a},AvVb:function(t,e,n){"use strict";var i=function(){var t=this,e=t.$createElement,n=t._self._c||e;return n("div",{staticClass:"bind-phone-box"},[n("BindPhoneHeader"),t._v(" "),n("main",{staticClass:"bind-phone-main"},[t._m(0),t._v(" "),n("div",{staticClass:"login-module-form"},[n("van-cell-group",[n("van-field",{attrs:{label:"手机号",placeholder:"请输入您的手机号"},model:{value:t.phoneNum,callback:function(e){t.phoneNum=e},expression:"phoneNum"}}),t._v(" "),n("van-field",{attrs:{center:"",clearable:"",label:"验证码",placeholder:"请输入验证码"},model:{value:t.verifyNum,callback:function(e){t.verifyNum=e},expression:"verifyNum"}},[n("van-button",{attrs:{slot:"button",size:"small",type:"default"},on:{click:function(e){return t.sendSmsCode()}},slot:"button"},[t._v(t._s(t.btnContent))])],1)],1)],1),t._v(" "),n("div",{staticClass:"bind-phone-btn"},[n("van-button",{attrs:{type:"primary"}},[t._v("提交")])],1)]),t._v(" "),n("BindPhoneFooter")],1)},o=[function(){var t=this.$createElement,e=this._self._c||t;return e("div",{staticClass:"login-module-title-box"},[e("h2",{staticClass:"login-module-title"},[this._v("绑定手机号")])])}];n.d(e,"a",(function(){return i})),n.d(e,"b",(function(){return o}))},Hb8c:function(t,e,n){"use strict";Object.defineProperty(e,"__esModule",{value:!0});var i=u(n("bS4n"));n("i4TU");var o=u(n("d8De"));function u(t){return t&&t.__esModule?t:{default:t}}e.default=(0,i.default)({name:"bind-phone-view"},o.default)},OW72:function(t,e,n){"use strict";Object.defineProperty(e,"__esModule",{value:!0});var i=u(n("bS4n"));n("v/Xo");var o=u(n("zkMY"));function u(t){return t&&t.__esModule?t:{default:t}}e.default=(0,i.default)({name:"login-sign-up-footer"},o.default)},PvCI:function(t,e,n){"use strict";n.r(e);var i=n("AvVb"),o=n("1BLf");for(var u in o)"default"!==u&&function(t){n.d(e,t,(function(){return o[t]}))}(u);var a=n("ZpG+"),s=Object(a.a)(o.default,i.a,i.b,!1,null,null,null);e.default=s.exports},UjaL:function(t,e,n){"use strict";n.r(e);var i=n("x5Rc"),o=n("pz4+");for(var u in o)"default"!==u&&function(t){n.d(e,t,(function(){return o[t]}))}(u);var a=n("ZpG+"),s=Object(a.a)(o.default,i.a,i.b,!1,null,null,null);e.default=s.exports},d8De:function(t,e,n){"use strict";Object.defineProperty(e,"__esModule",{value:!0});var i=u(n("JZuw")),o=u(n("UjaL"));function u(t){return t&&t.__esModule?t:{default:t}}e.default={data:function(){return{phoneNum:"13524405426",verifyNum:"",btnContent:"获取验证码",time:1,disabled:!1,bind:"bind"}},components:{BindPhoneHeader:i.default,BindPhoneFooter:o.default},created:function(){console.log(this.time)},methods:{sendSmsCode:function(){var t=this,e=this.time,n=this.phoneNum;n?(/^((13|14|15|17|18)[0-9]{1}\d{8})$/.test(n)||this.$toast("您输入的手机号码不合法，请重新输入"),this.time=6,console.log(e),this.timer(),this.appFetch({url:"sendSms",method:"post",data:{data:{attributes:{mobile:this.phoneNum,type:this.bind}}}},(function(e){console.log(e),200===e.status?t.aaa=e.interval:console.log("400")}),(function(t){alert("45656")}))):this.$toast("请输入手机号码")},timer:function(){if(this.time>1){this.time--,this.btnContent=this.time+"s后重新获取",this.disabled=!0;var t=setTimeout(this.timer,1e3)}else 1==this.time&&(this.btnContent="获取验证码",clearTimeout(t),this.disabled=!1)},verificationCode:function(){var t=this.phoneNum,e=this.verifyNum;this.$http.post("http://bosstan.asuscomm.com/api/common/verificationCode",{username:t,code:e},{emulateJSON:!0}).then((function(t){console.log(t.body)}))},fillContent:function(){}}}},i4TU:function(t,e,n){},"pz4+":function(t,e,n){"use strict";n.r(e);var i=n("OW72"),o=n.n(i);for(var u in i)"default"!==u&&function(t){n.d(e,t,(function(){return i[t]}))}(u);e.default=o.a},"v/Xo":function(t,e,n){},x5Rc:function(t,e,n){"use strict";var i=function(){var t=this,e=t.$createElement,n=t._self._c||e;return n("footer",{staticClass:"login-user-footer"},["login-user"===t.pageName||"login-phone"===t.pageName?[n("span",{on:{click:t.retrieveClick}},[t._v("忘记密码？找回")]),t._v(" "),n("i"),t._v(" "),n("span",{on:{click:t.signUpClick}},[t._v("注册")])]:"wx-login-bd"===t.pageName||"wx-sign-up-bd"===t.pageName?[n("span",{on:{click:t.wxSignUpBdClick}},[t._v("没有账号？注册，绑定微信新账号")])]:"sign-up"===t.pageName?[n("span",{on:{click:t.loginClick}},[t._v("已有账号立即登录")])]:"bind-phone"===t.pageName?[n("span",{on:{click:t.homeClick}},[t._v("跳过，进入首页")])]:(t.pageName,[n("span")])],2)},o=[];n.d(e,"a",(function(){return i})),n.d(e,"b",(function(){return o}))},zkMY:function(t,e,n){"use strict";Object.defineProperty(e,"__esModule",{value:!0}),e.default={data:function(){return{pageName:"login"}},methods:{retrieveClick:function(){this.$router.push("/retrieve-pwd")},signUpClick:function(){this.$router.push("/sign-up")},wxSignUpBdClick:function(){this.$router.push("/wx-sign-up-bd")},loginClick:function(){this.$router.push("/login-user")},homeClick:function(){this.$router.push("/")}},created:function(){this.pageName=this.$router.history.current.name,console.log(this.$router.history.current.name)}}}}]);