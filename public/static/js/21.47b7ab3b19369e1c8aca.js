(window.webpackJsonp=window.webpackJsonp||[]).push([[21],{"3KiV":function(t,e,a){"use strict";var n=function(){var t=this,e=t.$createElement,a=t._self._c||e;return a("div",{staticClass:"annex-set-box"},[a("Card",{attrs:{header:"支持的图片扩展名："}},[a("CardRow",{attrs:{description:"多个请用,隔开，例如 png,gif,jpg"}},[a("el-input",{model:{value:t.picture,callback:function(e){t.picture=e},expression:"picture"}})],1)],1),t._v(" "),a("Card",{attrs:{header:"支持的文件扩展名："}},[a("CardRow",{attrs:{description:"多个请用,隔开，例如 doc,docx,pdf,zip"}},[a("el-input",{model:{value:t.fileExtension,callback:function(e){t.fileExtension=e},expression:"fileExtension"}})],1)],1),t._v(" "),a("Card",{attrs:{header:"支持的最大尺寸："}},[a("CardRow",{attrs:{description:"单位：MB"}},[a("el-input",{model:{value:t.maximumSize,callback:function(e){t.maximumSize=e},expression:"maximumSize"}})],1)],1),t._v(" "),a("Card",{staticClass:"footer-btn"},[a("el-button",{attrs:{type:"primary",size:"medium"},on:{click:t.submi}},[t._v("提交")])],1)],1)},i=[];a.d(e,"a",(function(){return n})),a.d(e,"b",(function(){return i}))},"3vYn":function(t,e,a){"use strict";var n=function(){var t=this.$createElement,e=this._self._c||t;return e("div",{staticClass:"card-row-box"},[e("div",{staticClass:"card-row-lf"},[this._t("default")],2),this._v(" "),e("div",{staticClass:"card-row-rf"},[e("span",[this._v(this._s(this.$attrs.description))]),this._v(" "),this._t("tail")],2)])},i=[];a.d(e,"a",(function(){return n})),a.d(e,"b",(function(){return i}))},"4gYi":function(t,e,a){"use strict";a.r(e);var n=a("D0zz"),i=a("gxDo");for(var r in i)"default"!==r&&function(t){a.d(e,t,(function(){return i[t]}))}(r);var u=a("KHd+"),s=Object(u.a)(i.default,n.a,n.b,!1,null,null,null);e.default=s.exports},"6Akm":function(t,e,a){"use strict";Object.defineProperty(e,"__esModule",{value:!0}),a("CmEe"),e.default={name:"card"}},"8ss3":function(t,e,a){"use strict";a.r(e);var n=a("3KiV"),i=a("cabW");for(var r in i)"default"!==r&&function(t){a.d(e,t,(function(){return i[t]}))}(r);var u=a("KHd+"),s=Object(u.a)(i.default,n.a,n.b,!1,null,null,null);e.default=s.exports},ARSS:function(t,e,a){},Bouk:function(t,e,a){"use strict";Object.defineProperty(e,"__esModule",{value:!0});var n=r(a("4gYi")),i=r(a("pNQN"));function r(t){return t&&t.__esModule?t:{default:t}}e.default={data:function(){return{picture:"",fileExtension:"",maximumSize:""}},created:function(){this.annexSet()},methods:{annexSet:function(){var t=this;this.appFetch({url:"forum",method:"get",data:{}}).then((function(e){t.picture=e.readdata._data.supportImgExt,t.fileExtension=e.readdata._data.supportFileExt,t.maximumSize=e.readdata._data.supportMaxSize,console.log(e)}))},submi:function(){var t=this,e=this.picture,a=this.fileExtension,n=this.maximumSize;e?a?n?/^(?:[a-zA-Z]{3},)*[a-zA-Z]{3}$/.test(e)?/^\d+$|^\d+[.]?\d+$/.test(n)?this.appFetch({url:"settings",method:"post",data:{data:[{attributes:{key:"support_img_ext",value:this.picture,tag:"default"}},{attributes:{key:"support_file_ext",value:this.fileExtension,tag:"default"}},{attributes:{key:"support_max_size",value:this.maximumSize,tag:"default"}}]}}).then((function(e){t.$message({message:"提交成功",type:"success"})})).catch((function(t){})):this.$toast("请输入正确的支持最大尺寸格式"):this.$toast("请输入正确的扩展名格式"):this.$toast("请您输入支持的最大尺寸"):this.$toast("请您输入文件扩展名"):this.$toast("请您输入图片扩展名")}},components:{Card:n.default,CardRow:i.default}}},CmEe:function(t,e,a){},D0zz:function(t,e,a){"use strict";var n=function(){var t=this,e=t.$createElement,a=t._self._c||e;return a("div",{staticClass:"card-box"},[t.$attrs.header?a("div",{staticClass:"card-box__header",class:t.$slots.default?"":"not-main"},[a("header",{staticClass:"card-title",class:t.$attrs.intercept?"card-intercept-title":""},[t._v(t._s(t.$attrs.header))]),t._v(" "),t._t("header")],2):t._e(),t._v(" "),a("main",{staticClass:"card-box__main"},[t._t("default")],2)])},i=[];a.d(e,"a",(function(){return n})),a.d(e,"b",(function(){return i}))},Nn0y:function(t,e,a){"use strict";a.r(e);var n=a("XMfV"),i=a.n(n);for(var r in n)"default"!==r&&function(t){a.d(e,t,(function(){return n[t]}))}(r);e.default=i.a},XMfV:function(t,e,a){"use strict";Object.defineProperty(e,"__esModule",{value:!0}),a("ARSS"),e.default={name:"form-row"}},cabW:function(t,e,a){"use strict";a.r(e);var n=a("uflW"),i=a.n(n);for(var r in n)"default"!==r&&function(t){a.d(e,t,(function(){return n[t]}))}(r);e.default=i.a},gxDo:function(t,e,a){"use strict";a.r(e);var n=a("6Akm"),i=a.n(n);for(var r in n)"default"!==r&&function(t){a.d(e,t,(function(){return n[t]}))}(r);e.default=i.a},pNQN:function(t,e,a){"use strict";a.r(e);var n=a("3vYn"),i=a("Nn0y");for(var r in i)"default"!==r&&function(t){a.d(e,t,(function(){return i[t]}))}(r);var u=a("KHd+"),s=Object(u.a)(i.default,n.a,n.b,!1,null,null,null);e.default=s.exports},uflW:function(t,e,a){"use strict";Object.defineProperty(e,"__esModule",{value:!0});var n=r(a("QbLZ")),i=r(a("Bouk"));function r(t){return t&&t.__esModule?t:{default:t}}a("zt69"),e.default=(0,n.default)({name:"annex-set-view"},i.default)},zt69:function(t,e,a){}}]);