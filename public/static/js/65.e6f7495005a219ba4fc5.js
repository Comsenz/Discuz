(window.webpackJsonp=window.webpackJsonp||[]).push([[65],{"56wF":function(e,t,a){"use strict";Object.defineProperty(t,"__esModule",{value:!0});var n=c(a("4gYi")),r=c(a("pNQN"));function c(e){return e&&e.__esModule?e:{default:e}}t.default={data:function(){return{checked:!0,pwdLength:"",checkList:[]}},methods:{},components:{Card:n.default,CardRow:r.default}}},OfIh:function(e,t,a){"use strict";var n=function(){var e=this,t=e.$createElement,a=e._self._c||t;return a("div",{staticClass:"sign-up-set-box"},[a("Card",{attrs:{header:"新用户注册："}},[a("CardRow",{attrs:{description:"设置是否允许游客注册成为会员"}},[a("el-checkbox",{model:{value:e.checked,callback:function(t){e.checked=t},expression:"checked"}},[e._v("允许新用户注册")])],1)],1),e._v(" "),a("Card",{attrs:{header:"注册密码最小长度："}},[a("CardRow",{attrs:{description:"新用户注册时密码最小长度，0或不填为不限制"}},[a("el-input",{attrs:{clearable:""},model:{value:e.pwdLength,callback:function(t){e.pwdLength=t},expression:"pwdLength"}})],1)],1),e._v(" "),a("Card",{attrs:{header:"密码字符类型："}},[a("CardRow",{attrs:{description:"新用户注册时密码中必须存在所选字符类型，不选则为无限制"}},[a("el-checkbox-group",{model:{value:e.checkList,callback:function(t){e.checkList=t},expression:"checkList"}},[a("el-checkbox",{attrs:{label:"数字"}},[e._v("数字")]),e._v(" "),a("el-checkbox",{attrs:{label:"小写字母"}},[e._v("小写字母")]),e._v(" "),a("el-checkbox",{attrs:{label:"符号"}},[e._v("符号")]),e._v(" "),a("el-checkbox",{attrs:{label:"大写字母"}},[e._v("大写字母")])],1)],1)],1),e._v(" "),a("Card",[a("el-button",{attrs:{type:"primary",size:"medium"}},[e._v("提交")])],1)],1)},r=[];a.d(t,"a",(function(){return n})),a.d(t,"b",(function(){return r}))},"e+0/":function(e,t,a){"use strict";a.r(t);var n=a("OfIh"),r=a("pOCi");for(var c in r)"default"!==c&&function(e){a.d(t,e,(function(){return r[e]}))}(c);var u=a("ZpG+"),l=Object(u.a)(r.default,n.a,n.b,!1,null,null,null);t.default=l.exports},pOCi:function(e,t,a){"use strict";a.r(t);var n=a("veRR"),r=a.n(n);for(var c in n)"default"!==c&&function(e){a.d(t,e,(function(){return n[e]}))}(c);t.default=r.a},veRR:function(e,t,a){"use strict";Object.defineProperty(t,"__esModule",{value:!0});var n=c(a("bS4n")),r=c(a("56wF"));function c(e){return e&&e.__esModule?e:{default:e}}a("RXJm"),t.default=(0,n.default)({name:"sign-up-set-view"},r.default)}}]);