(window.webpackJsonp=window.webpackJsonp||[]).push([[36],{"3AWV":function(e,t,a){"use strict";Object.defineProperty(t,"__esModule",{value:!0});var s=r(a("QbLZ"));a("iUmJ");var i=r(a("zkMY"));function r(e){return e&&e.__esModule?e:{default:e}}t.default=(0,s.default)({name:"login-sign-up-footer"},i.default)},"7Ths":function(e,t,a){"use strict";a.r(t);var s=a("eujX"),i=a("U+hY");for(var r in i)"default"!==r&&function(e){a.d(t,e,(function(){return i[e]}))}(r);var n=a("KHd+"),o=Object(n.a)(i.default,s.a,s.b,!1,null,null,null);t.default=o.exports},NdMT:function(e,t,a){},Ra63:function(e,t,a){"use strict";var s=function(){var e=this,t=e.$createElement,a=e._self._c||t;return a("footer",{staticClass:"login-user-footer"},["login-user"===e.pageName||"login-phone"===e.pageName?[e.qcloudSms?a("span",{on:{click:e.retrieveClick}},[e._v("忘记密码？找回")]):e._e(),e._v(" "),e.registerClose&&e.qcloudSms?a("i"):e._e(),e._v(" "),e.registerClose?a("span",{on:{click:e.signUpClick}},[e._v("注册")]):e._e()]:"wx-login-bd"===e.pageName?[a("span",{on:{click:e.wxSignUpBdClick}},[e._v("没有账号？注册，绑定微信新账号")])]:"wx-sign-up-bd"===e.pageName?[a("span",{on:{click:e.wxLoginBdClick}},[e._v("已有账号？登录，微信绑定账号")])]:"sign-up"===e.pageName?[a("span",{on:{click:e.loginClick}},[e._v("已有账号立即登录")])]:"bind-phone"===e.pageName?[a("span",{on:{click:e.homeClick}},[e._v(e._s("pay"===e.siteMode?"跳过，进入支付费用":"跳过，进入首页"))])]:(e.pageName,[a("span")])],2)},i=[];a.d(t,"a",(function(){return s})),a.d(t,"b",(function(){return i}))},"U+hY":function(e,t,a){"use strict";a.r(t);var s=a("jJ/q"),i=a.n(s);for(var r in s)"default"!==r&&function(e){a.d(t,e,(function(){return s[e]}))}(r);t.default=i.a},UjaL:function(e,t,a){"use strict";a.r(t);var s=a("Ra63"),i=a("pz4+");for(var r in i)"default"!==r&&function(e){a.d(t,e,(function(){return i[e]}))}(r);var n=a("KHd+"),o=Object(n.a)(i.default,s.a,s.b,!1,null,null,null);t.default=o.exports},ZKVN:function(e,t,a){"use strict";Object.defineProperty(t,"__esModule",{value:!0});var s=n(a("JZuw")),i=n(a("UjaL")),r=n(a("VVfg"));function n(e){return e&&e.__esModule?e:{default:e}}t.default={data:function(){return{newpwd:"",verifyNum:"",phoneNum:"",type:"",btnContent:"获取验证码",time:1,disabled:!1,insterVal:"",isGray:!1,btnLoading:!1,data:{},payPassword:"",payPasswordConfirmation:"",tokenId:""}},components:{retrievePWDHeader:s.default,retrievePWDFooter:i.default},created:function(){this.tokenId=r.default.getLItem("tokenId"),this.$route.query.type&&"forget"===this.$route.query.type?(this.type="reset_pay_pwd",this.getUserInfo()):this.type="reset_pwd"},methods:{forgetSendSmsCode:function(){var e=this;if("reset_pay_pwd"!==this.type){var t=this.phoneNum;if(!t)return void this.$toast("请输入手机号码");/^((13|14|15|17|18)[0-9]{1}\d{8})$/.test(t)?this.appFetch({url:"sendSms",method:"post",data:{data:{attributes:{mobile:this.phoneNum,type:this.type}}}}).then((function(t){t.errors?e.$toast.fail(t.errors[0].code):(e.insterVal=t.data.attributes.interval,e.time=e.insterVal,e.timer())})):this.$toast("您输入的手机号码不合法，请重新输入")}else this.appFetch({url:"sendSms",method:"post",data:{data:{attributes:{type:this.type}}}}).then((function(t){t.errors?e.$toast.fail(t.errors[0].code):(e.insterVal=t.data.attributes.interval,e.time=e.insterVal,e.timer())}))},timer:function(){if(this.time>1){this.time--,this.btnContent=this.time+"s后重新获取",this.disabled=!0;var e=setTimeout(this.timer,1e3);this.isGray=!0}else 1==this.time&&(this.btnContent="获取验证码",clearTimeout(e),this.disabled=!1,this.isGray=!1)},submissionPassword:function(){var e=this;if(this.phoneNum.length<1)this.$toast("请输入手机号");else if(/^((13|14|15|16|17|18|19)[0-9]{1}\d{8})$/.test(this.phoneNum))if(this.verifyNum.length<1)this.$toast("请输入验证码");else{this.btnLoading=!0;var t={attributes:{mobile:this.phoneNum,code:this.verifyNum,type:this.type}};"reset_pay_pwd"===this.type?(t.attributes.pay_password=this.payPassword,t.attributes.pay_password_confirmation=this.payPasswordConfirmation):"reset_pwd"===this.type&&(t.attributes.password=this.newpwd),this.appFetch({url:"smsVerify",method:"post",data:{data:t}}).then((function(t){e.btnLoading=!1,t.errors?t.errors[0].detail?e.$toast.fail(t.errors[0].code+"\n"+t.errors[0].detail[0]):e.$toast.fail(t.errors[0].code):(e.$router.push({path:"login-user"}),e.$toast.success("提交成功"))}))}else this.$toast("请输入正确的手机号")},getUserInfo:function(){var e=this;this.appFetch({url:"users",method:"get",splice:"/"+this.tokenId}).then((function(t){console.log(t),t.errors?t.errors[0].detail?e.$toast.fail(t.errors[0].code+"\n"+t.errors[0].detail[0]):e.$toast.fail(t.errors[0].code):"reset_pay_pwd"===e.type?e.phoneNum=t.readdata._data.mobile:e.phoneNum=t.readdata._data.originalMobile})).catch((function(e){console.log(e)}))}}}},eujX:function(e,t,a){"use strict";var s=function(){var e=this,t=e.$createElement,a=e._self._c||t;return a("div",{staticClass:"retrieve-password-box"},[a("retrievePWDHeader"),e._v(" "),a("main",{staticClass:"retrieve-password-main"},[e._m(0),e._v(" "),a("div",{staticClass:"login-module-form"},[a("van-cell-group",[a("van-field",{attrs:{label:"手机号",clearable:"reset_pay_pwd"!==e.type,readonly:"reset_pay_pwd"===e.type,placeholder:"请输入您的手机号",maxlength:"13"},model:{value:e.phoneNum,callback:function(t){e.phoneNum=t},expression:"phoneNum"}}),e._v(" "),a("van-field",{attrs:{center:"",clearable:"",label:"验证码",placeholder:"请输入验证码",type:"number"},model:{value:e.verifyNum,callback:function(t){e.verifyNum=t},expression:"verifyNum"}},[a("van-button",{class:{grayBg:e.isGray},attrs:{slot:"button",size:"small",type:"default"},on:{click:e.forgetSendSmsCode},slot:"button"},[e._v(e._s(e.btnContent))])],1),e._v(" "),"reset_pwd"===e.type?a("van-field",{attrs:{label:"新密码",clearable:"",placeholder:"请输入新密码"},model:{value:e.newpwd,callback:function(t){e.newpwd=t},expression:"newpwd"}}):e._e(),e._v(" "),"reset_pay_pwd"===e.type?a("van-field",{attrs:{label:"新密码",clearable:"",type:"number",maxlength:"6",placeholder:"请输入新密码"},model:{value:e.payPassword,callback:function(t){e.payPassword=t},expression:"payPassword"}}):e._e(),e._v(" "),"reset_pay_pwd"===e.type?a("van-field",{attrs:{label:"确认密码",clearable:"",type:"number",maxlength:"6",placeholder:"请输入新密码"},model:{value:e.payPasswordConfirmation,callback:function(t){e.payPasswordConfirmation=t},expression:"payPasswordConfirmation"}}):e._e()],1)],1),e._v(" "),a("div",{staticClass:"retrieve-password-btn"},[a("van-button",{attrs:{type:"primary",loading:e.btnLoading},on:{click:e.submissionPassword}},[e._v("提交")])],1)])],1)},i=[function(){var e=this.$createElement,t=this._self._c||e;return t("div",{staticClass:"login-module-title-box"},[t("h2",{staticClass:"login-module-title"},[this._v("忘记密码")])])}];a.d(t,"a",(function(){return s})),a.d(t,"b",(function(){return i}))},"jJ/q":function(e,t,a){"use strict";Object.defineProperty(t,"__esModule",{value:!0});var s=r(a("QbLZ"));a("NdMT"),a("iUmJ");var i=r(a("ZKVN"));function r(e){return e&&e.__esModule?e:{default:e}}t.default=(0,s.default)({name:"retrieve-password-view"},i.default)},"pz4+":function(e,t,a){"use strict";a.r(t);var s=a("3AWV"),i=a.n(s);for(var r in s)"default"!==r&&function(e){a.d(t,e,(function(){return s[e]}))}(r);t.default=i.a},zkMY:function(e,t,a){"use strict";Object.defineProperty(t,"__esModule",{value:!0});var s,i=a("VVfg"),r=(s=i)&&s.__esModule?s:{default:s};t.default={data:function(){return{pageName:"login",siteMode:"",registerClose:!0,qcloudSms:!0}},methods:{retrieveClick:function(){this.$router.push("retrieve-pwd")},signUpClick:function(){this.$router.push("sign-up")},wxSignUpBdClick:function(){this.$router.push("/wx-sign-up-bd")},wxLoginBdClick:function(){this.$router.push("/wx-login-bd")},loginClick:function(){this.$router.push("/login-user")},homeClick:function(){switch(this.siteMode){case"pay":this.$router.push({path:"pay-the-fee"});break;case"public":this.$router.push({path:"/"})}},getForum:function(){var e=this;this.appFetch({url:"forum",method:"get",data:{}}).then((function(t){e.siteMode=t.readdata._data.set_site.site_mode,e.registerClose=t.readdata._data.set_reg.register_close,e.qcloudSms=t.readdata._data.qcloud.qcloud_sms,r.default.setLItem("siteInfo",t.readdata)})).catch((function(e){}))}},created:function(){this.pageName=this.$router.history.current.name,this.getForum()}}}}]);