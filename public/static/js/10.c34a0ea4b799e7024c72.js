(window.webpackJsonp=window.webpackJsonp||[]).push([[10],{"0Ndr":function(t,e,a){"use strict";Object.defineProperty(e,"__esModule",{value:!0});var n=s(a("QbLZ")),i=s(a("RKEP"));function s(t){return t&&t.__esModule?t:{default:t}}a("iUmJ"),e.default=(0,n.default)({name:"paymentMethodView"},i.default)},"0VOr":function(t,e,a){"use strict";a.r(e);var n=a("0Ndr"),i=a.n(n);for(var s in n)"default"!==s&&function(t){a.d(e,t,(function(){return n[t]}))}(s);e.default=i.a},"2qD6":function(t,e,a){"use strict";a.r(e);var n=a("brYC"),i=a("mNuq");for(var s in i)"default"!==s&&function(t){a.d(e,t,(function(){return i[t]}))}(s);var o=a("KHd+"),c=Object(o.a)(i.default,n.a,n.b,!1,null,null,null);e.default=c.exports},"3XTc":function(t,e,a){"use strict";a.r(e);var n=a("yCIz"),i=a("0VOr");for(var s in i)"default"!==s&&function(t){a.d(e,t,(function(){return i[t]}))}(s);var o=a("KHd+"),c=Object(o.a)(i.default,n.a,n.b,!1,null,null,null);e.default=c.exports},RKEP:function(t,e,a){"use strict";Object.defineProperty(e,"__esModule",{value:!0});var n,i=a("VVfg"),s=(n=i)&&n.__esModule?n:{default:n};e.default={data:function(){return{paySelectShow:!1,payImmediatelyShow:!1,radio:0,descriptionShow:!1,pwdValue:"",showKeyboard:!1}},props:{value:{type:Boolean,default:!1},money:{type:String,default:"0.00"},balance:{type:String,default:"0.00"},data:{type:Array,default:[{name:"钱包",icon:""}]},error:{type:String},walletStatus:{type:Boolean,default:!1},payUrl:{type:String}},methods:{onInput:function(t){this.pwdValue=(this.pwdValue+t).slice(0,6),this.$emit("oninput",t)},onDelete:function(){this.pwdValue=this.pwdValue.slice(0,this.pwdValue.length-1),this.$emit("delete")},onClose:function(){this.$emit("close")},payImmediatelyClick:function(){"钱包"===this.data[this.radio].name&&(this.paySelectShow=!this.paySelectShow,this.payImmediatelyShow=!this.payImmediatelyShow),this.$emit("payImmediatelyClick",this.data[this.radio])},payStatusClick:function(){this.payUrl&&(this.$router.push({path:"/"+this.payUrl}),s.default.setLItem("payUrl",this.$route.fullPath))}},watch:{value:function(t){this.paySelectShow=t,this.descriptionShow=parseFloat(this.money)>parseFloat(this.balance)},paySelectShow:function(t){t||this.$emit("input",!1)},payImmediatelyShow:function(t){t||(this.pwdValue="")}}}},brYC:function(t,e,a){"use strict";var n=function(){var t=this,e=t.$createElement,a=t._self._c||e;return a("div",{model:{value:t.themeCon,callback:function(e){t.themeCon=e},expression:"themeCon"}},[a("div",{staticClass:"postTop"},[a("div",{staticClass:"postPer"},[t.themeCon.user&&t.themeCon.user._data.avatarUrl?a("img",{staticClass:"postHead",attrs:{src:t.themeCon.user._data.avatarUrl,alt:""},on:{click:function(e){return t.jumpPerDet(t.themeCon.user._data.id)}}}):a("img",{staticClass:"postHead",attrs:{src:t.appConfig.staticBaseUrl+"/images/noavatar.gif"},on:{click:function(e){return t.jumpPerDet(t.themeCon.user._data.id)}}}),t._v(" "),a("div",{staticClass:"perDet"},[t.themeCon.user?a("div",{staticClass:"perName",on:{click:function(e){return t.jumpPerDet(t.themeCon.user._data.id)}}},[t._v(t._s(t.themeCon.user._data.username))]):a("div",{staticClass:"perName"},[t._v("该用户已被删除")]),t._v(" "),a("div",{staticClass:"postTime"},[t._v(t._s(t.$moment(t.themeCon._data.createdAt).format("YYYY-MM-DD HH:mm")))])])]),t._v(" "),a("div",{staticClass:"postOpera"},[t.themeCon._data.isSticky?a("span",{staticClass:"icon iconfont icon-top"}):t._e()])]),t._v(" "),a("div",{staticClass:"postTitle"},[t._v(t._s(t.themeCon._data.title))]),t._v(" "),a("div",{staticClass:"longTextContent",domProps:{innerHTML:t._s(t.themeCon.firstPost._data.contentHtml)}}),t._v(" "),!t.themeCon._data.paid&&t.themeCon._data.price>0?a("div",{staticClass:"payTipBox"},[a("p",{staticClass:"tipPrice"},[t._v("本内容需向作者支付 "),a("span",[t._v(t._s(t.themeCon._data.price))]),t._v(" 元 才能浏览")]),t._v(" "),a("a",{staticClass:"buyBtn",attrs:{href:"javascript:;"},on:{click:t.buyTheme}},[t._v("购买内容")])]):t._e(),t._v(" "),t.firstpostImageListProp.length>0?a("div",{staticClass:"postImgBox"},[a("div",{staticClass:"postImgList"},[t.isWeixin||t.isPhone?a("div",t._l(t.firstpostImageListProp,(function(e,n){return a("van-image",{key:n,attrs:{"lazy-load":"",key:"index",src:e},on:{click:function(e){return t.imageSwiper(n,"detailImg")}}})})),1):a("div",t._l(t.firstpostImageListProp,(function(t,e){return a("van-image",{key:e,attrs:{"lazy-load":"",key:"index",src:t}})})),1)])]):t._e(),t._v(" "),t.isiOS&&t.themeCon.firstPost.attachments.length>0?a("div",{staticClass:"uploadFileList"},t._l(t.themeCon.firstPost.attachments,(function(e,n){return a("a",{key:n,staticClass:"fileChi",on:{click:function(a){return t.downAttachment(e._data.url)}}},["rar"===e._data.extension?a("span",{staticClass:"icon iconfont icon-rar"}):t._e(),t._v(" "),"zip"===e._data.extension?a("span",{staticClass:"icon iconfont icon-rar"}):"doc"===e._data.extension?a("span",{staticClass:"icon iconfont icon-word"}):"docx"===e._data.extension?a("span",{staticClass:"icon iconfont icon-word"}):"pdf"===e._data.extension?a("span",{staticClass:"icon iconfont icon-pdf"}):"jpg"===e._data.extension?a("span",{staticClass:"icon iconfont icon-jpg"}):"mp"===e._data.extension?a("span",{staticClass:"icon iconfont icon-mp3"}):"mp1"===e._data.extension?a("span",{staticClass:"icon iconfont icon-mp4"}):"png"===e._data.extension?a("span",{staticClass:"icon iconfont icon-PNG"}):"ppt"===e._data.extension?a("span",{staticClass:"icon iconfont icon-ppt"}):"swf"===e._data.extension?a("span",{staticClass:"icon iconfont icon-swf"}):"TIFF"===e._data.extension?a("span",{staticClass:"icon iconfont icon-TIFF"}):"txt"===e._data.extension?a("span",{staticClass:"icon iconfont icon-txt"}):"xls"===e._data.extension?a("span",{staticClass:"icon iconfont icon-xls"}):a("span",{staticClass:"icon iconfont icon-doubt"}),t._v(" "),a("span",{staticClass:"fileName"},[t._v(t._s(e._data.fileName))])])})),0):t._e(),t._v(" "),t.themeCon.firstPost.attachments.length>0?a("div",{staticClass:"uploadFileList"},t._l(t.themeCon.firstPost.attachments,(function(e,n){return a("a",{key:n,staticClass:"fileChi",attrs:{href:e._data.url,download:""}},["rar"===e._data.extension?a("span",{staticClass:"icon iconfont icon-rar"}):t._e(),t._v(" "),"zip"===e._data.extension?a("span",{staticClass:"icon iconfont icon-rar"}):"doc"===e._data.extension?a("span",{staticClass:"icon iconfont icon-word"}):"docx"===e._data.extension?a("span",{staticClass:"icon iconfont icon-word"}):"pdf"===e._data.extension?a("span",{staticClass:"icon iconfont icon-pdf"}):"jpg"===e._data.extension?a("span",{staticClass:"icon iconfont icon-jpg"}):"mp"===e._data.extension?a("span",{staticClass:"icon iconfont icon-mp3"}):"mp1"===e._data.extension?a("span",{staticClass:"icon iconfont icon-mp4"}):"png"===e._data.extension?a("span",{staticClass:"icon iconfont icon-PNG"}):"ppt"===e._data.extension?a("span",{staticClass:"icon iconfont icon-ppt"}):"swf"===e._data.extension?a("span",{staticClass:"icon iconfont icon-swf"}):"TIFF"===e._data.extension?a("span",{staticClass:"icon iconfont icon-TIFF"}):"txt"===e._data.extension?a("span",{staticClass:"icon iconfont icon-txt"}):"xls"===e._data.extension?a("span",{staticClass:"icon iconfont icon-xls"}):a("span",{staticClass:"icon iconfont icon-doubt"}),t._v(" "),a("span",{staticClass:"fileName"},[t._v(t._s(e._data.fileName))])])})),0):t._e(),t._v(" "),a("van-popup",{staticClass:"qrCodeBox",attrs:{round:"","close-icon-position":"top-right",closeable:"","get-container":"body"},model:{value:t.qrcodeShow,callback:function(e){t.qrcodeShow=e},expression:"qrcodeShow"}},[a("span",{staticClass:"popupTit"},[t._v("立即支付")]),t._v(" "),a("div",{staticClass:"payNum"},[t._v("￥"),a("span",[t._v(t._s(t.themeCon._data.price))])]),t._v(" "),a("div",{staticClass:"payType"},[a("span",{staticClass:"typeLeft"},[t._v("支付方式")]),t._v(" "),a("span",{staticClass:"typeRight"},[a("i",{staticClass:"icon iconfont icon-wepay"}),t._v("微信支付")])]),t._v(" "),a("img",{staticClass:"qrCode",attrs:{src:t.codeUrl,alt:"微信支付二维码"}}),t._v(" "),a("p",{staticClass:"payTip"},[t._v("微信识别二维码支付")])]),t._v(" "),t.userDet?a("PayMethod",{attrs:{data:t.payList,money:t.themeCon._data.price,balance:t.walletBalance,walletStatus:t.userDet._data.canWalletPay,payUrl:"setup-pay-pwd",error:t.errorInfo},on:{oninput:t.onInput,delete:t.onDelete,close:t.onClose,payImmediatelyClick:t.payImmediatelyClick},model:{value:t.show,callback:function(e){t.show=e},expression:"show"}}):t._e()],1)},i=[];a.d(e,"a",(function(){return n})),a.d(e,"b",(function(){return i}))},mNuq:function(t,e,a){"use strict";a.r(e);var n=a("wGCH"),i=a.n(n);for(var s in n)"default"!==s&&function(t){a.d(e,t,(function(){return n[t]}))}(s);e.default=i.a},wGCH:function(t,e,a){"use strict";Object.defineProperty(e,"__esModule",{value:!0});var n=o(a("QbLZ")),i=o(a("zGyv")),s=o(a("3XTc"));function o(t){return t&&t.__esModule?t:{default:t}}a("iUmJ"),a("N960"),e.default=(0,n.default)({name:"longTextDetailsView",components:{PayMethod:s.default}},i.default)},yCIz:function(t,e,a){"use strict";var n=function(){var t=this,e=t.$createElement,a=t._self._c||e;return a("div",{staticClass:"payment-method-box"},[a("van-popup",{staticClass:"way-to-choose-box",attrs:{round:"","close-icon-position":"top-right",closeable:"","get-container":"body"},on:{close:t.onClose},model:{value:t.paySelectShow,callback:function(e){t.paySelectShow=e},expression:"paySelectShow"}},[a("div",{staticClass:"way-to-choose-main"},[a("div",{staticClass:"manner-title"},[a("h1",[t._v("立即支付")]),t._v(" "),a("p",[a("span",[t._v("￥")]),t._v(t._s(t.money))]),t._v(" "),a("i")]),t._v(" "),a("div",{staticClass:"way-to-choose_cont"},[a("p",{staticClass:"way-to-choose_cont-title"},[t._v("支付方式")]),t._v(" "),a("div",{staticClass:"way-to-choose_cont-select"},[a("van-radio-group",{model:{value:t.radio,callback:function(e){t.radio=e},expression:"radio"}},t._l(t.data,(function(e,n){return a("div",{staticClass:"way-to-choose_cont-select_cell"},[a("div",{staticClass:"way-to-choose_cont-select_cell-left",on:{click:function(e){t.descriptionShow&&!t.walletStatus&&(t.radio=n)}}},[a("span",{staticClass:"icon iconfont",class:e.icon}),t._v(" "),a("div",{staticClass:"way-to-choose_cont-select_cell-left-title"},[a("span",[t._v(t._s(e.name))]),t._v(" "),t.walletStatus||"钱包"!==e.name?t.descriptionShow&&"钱包"===e.name?a("p",{staticClass:"way-to-choose_cont-select_cell-left-title_description"},[t._v("钱包余额不足，剩余"+t._s(t.balance)+"元")]):t._e():a("p",{staticClass:"way-to-choose_cont-select_cell-left-title_description",on:{click:t.payStatusClick}},[t._v("请设置钱包支付密码")])])]),t._v(" "),a("van-radio",{attrs:{slot:"right-icon",disabled:(t.descriptionShow||!t.walletStatus)&&"钱包"===e.name,name:n},slot:"right-icon"})],1)})),0)],1)]),t._v(" "),a("div",{staticClass:"way-to-choose_footer"},[a("van-button",{attrs:{type:"primary"},on:{click:t.payImmediatelyClick}},[t._v("立即支付")])],1)])]),t._v(" "),a("van-popup",{staticClass:"pay-immediately-box",class:t.error?"pay-immediately-box-err":"",attrs:{round:"","close-icon-position":"top-right",closeable:"","get-container":"body"},on:{close:t.onClose},model:{value:t.payImmediatelyShow,callback:function(e){t.payImmediatelyShow=e},expression:"payImmediatelyShow"}},[a("div",{staticClass:"pay-immediately-main"},[a("div",{staticClass:"manner-title"},[a("h1",[t._v("立即支付")]),t._v(" "),a("p",[a("span",[t._v("￥")]),t._v(t._s(t.money))]),t._v(" "),a("i")]),t._v(" "),a("div",{staticClass:"pay-immediately-main_cont"},[a("van-cell",{attrs:{title:"支付方式","is-link":""},on:{click:function(e){t.paySelectShow=!t.paySelectShow,t.payImmediatelyShow=!t.payImmediatelyShow}}},[a("template",{slot:"default"},[a("span",{staticClass:"icon iconfont",class:t.data[t.radio].icon}),t._v(" "),a("span",{staticClass:"custom-title"},[t._v(t._s(t.data[t.radio].name))])])],2)],1),t._v(" "),a("van-password-input",{staticClass:"passwordInp",attrs:{value:t.pwdValue,focused:t.showKeyboard,"error-info":t.error},on:{focus:function(e){t.showKeyboard=!0}}})],1)]),t._v(" "),a("van-number-keyboard",{attrs:{"safe-area-inset-bottom":"","z-index":3e3,show:t.showKeyboard},on:{input:t.onInput,delete:t.onDelete,blur:function(e){t.showKeyboard=!1}}})],1)},i=[];a.d(e,"a",(function(){return n})),a.d(e,"b",(function(){return i}))},zGyv:function(t,e,a){"use strict";Object.defineProperty(e,"__esModule",{value:!0});var n=o(a("KKL4")),i=o(a("6NK7")),s=o(a("VVfg"));a("uXAG");function o(t){return t&&t.__esModule?t:{default:t}}e.default={data:function(){return{show:!1,payList:[{name:"钱包",icon:"icon-weixin"}],qrcodeShow:!1,walletBalance:"",errorInfo:"",value:"",userId:"",codeUrl:""}},props:{themeCon:{type:Object},firstpostImageListProp:{type:Array},userDet:{type:Object}},created:function(){var t=this,e=navigator.userAgent;this.isAndroid=e.indexOf("Android")>-1||e.indexOf("Adr")>-1,this.isiOS=!!e.match(/\(i[^;]+;( U;)? CPU.+Mac OS X/),this.isWeixin=i.default.isWeixin().isWeixin,this.isPhone=i.default.isWeixin().isPhone,this.userId=s.default.getLItem("tokenId"),this.loadUserInfo(),this.getForum(),this.getUsers(s.default.getLItem("tokenId")).then((function(e){t.getAuthority(e.readdata.groups[0]._data.id),t.walletBalance=e.readdata._data.walletBalance}))},computed:{themeId:function(){return this.$route.params.themeId}},methods:{downAttachment:function(t){this.isiOS&&this.$message("因iphone系统限制，您的手机无法下载文件。请使用安卓手机或电脑访问下载")},jumpPerDet:function(t){this.token?this.$router.push({path:"/home-page/"+t}):this.$router.push({path:"/login-user",name:"login-user"})},loadUserInfo:function(){var t=this;if(!this.userId)return!1;this.appFetch({url:"users",method:"get",splice:"/"+this.userId,data:{}}).then((function(e){t.walletBalance=e.readdata._data.walletBalance}))},getForum:function(){var t=this;this.appFetch({url:"forum",method:"get",data:{}}).then((function(e){if(e.errors)t.$toast.fail(e.errors[0].code);else{t.sitePrice=e.readdata._data.set_site.site_price;var a=e.readdata._data.set_site.site_expire;switch(a){case"":case"0":t.siteExpire="永久有效";break;default:t.siteExpire="有效期自加入起"+a+"天"}"1"===e.readdata._data.paycenter.wxpay_close&&t.payList.unshift({name:"微信支付",icon:"icon-money"})}})).catch((function(t){}))},buyTheme:function(){this.show=!this.show},payImmediatelyClick:function(t){var e=this,a=this.appCommonH.isWeixin().isWeixin,n=this.appCommonH.isWeixin().isPhone;"微信支付"===t.name&&(this.show=!1,a?this.getOrderSn().then((function(){e.orderPay(12).then((function(t){"undefined"==typeof WeixinJSBridge?document.addEventListener?document.addEventListener("WeixinJSBridgeReady",e.onBridgeReady(t),!1):document.attachEvent&&(document.attachEvent("WeixinJSBridgeReady",e.onBridgeReady(t)),document.attachEvent("onWeixinJSBridgeReady",e.onBridgeReady(t))):e.onBridgeReady(t)}))})):n?this.getOrderSn().then((function(){e.orderPay(11).then((function(t){e.wxPayHref=t.readdata._data.wechat_h5_link,window.location.href=e.wxPayHref;var a=setInterval((function(){e.payStatus&&e.payStatusNum>10&&clearInterval(a),e.getOrderStatus()}),3e3)}))})):this.getOrderSn().then((function(){e.orderPay(10).then((function(t){e.codeUrl=t.readdata._data.wechat_qrcode,e.qrcodeShow=!0;var a=setInterval((function(){e.payStatus&&e.payStatusNum>10&&clearInterval(a),e.getOrderStatus()}),3e3)}))})))},onInput:function(t){var e=this;this.value=this.value+t,6===this.value.length&&(this.errorInfo="",this.getOrderSn().then((function(){e.orderPay(20,e.value).then((function(t){var a=setInterval((function(){e.payStatus&&e.payStatusNum>10&&clearInterval(a),e.getOrderStatus()}),3e3)}))})))},onDelete:function(){},onClose:function(){this.value="",this.errorInfo=""},onBridgeReady:function(t){var e=this;WeixinJSBridge.invoke("getBrandWCPayRequest",{appId:t.data.attributes.wechat_js.appId,timeStamp:t.data.attributes.wechat_js.timeStamp,nonceStr:t.data.attributes.wechat_js.nonceStr,package:t.data.attributes.wechat_js.package,signType:"MD5",paySign:t.data.attributes.wechat_js.paySign},(function(t){}));var a=setInterval((function(){"1"==e.payStatus||e.payStatusNum>10?clearInterval(a):e.getOrderStatus()}),3e3)},getOrderSn:function(){var t=this;return this.appFetch({url:"orderList",method:"post",data:{type:3,thread_id:this.themeId}}).then((function(e){e.errors?t.$toast.fail(e.errors[0].code):t.orderSn=e.readdata._data.order_sn})).catch((function(t){}))},orderPay:function(t,e){var a=this;return this.appFetch({url:"orderPay",method:"post",splice:"/"+this.orderSn,data:{payment_type:t,pay_password:e}}).then((function(t){if(!t.errors)return t;a.$toast.fail(t.errors[0].code)})).catch((function(t){}))},getUsersInfo:function(){},getOrderStatus:function(){var t=this;return this.appFetch({url:"order",method:"get",splice:"/"+this.orderSn,data:{}}).then((function(e){if(e.errors){if(!e.errors[0].detail)throw t.$toast.fail(e.errors[0].code),new Error(e.error);t.$toast.fail(e.errors[0].code+"\n"+e.errors[0].detail[0])}else t.payStatus=e.readdata._data.status,t.payStatusNum++,("1"==t.payStatus||t.payStatusNum>10)&&("1"==t.payStatus&&(location.reload(),t.sendMsgToParent()),t.rewardShow=!1,t.qrcodeShow=!1,t.payStatusNum=11)}))},sendMsgToParent:function(){this.$emit("listenToChildEvent",!0)},getUsers:function(t){var e=this;return this.appFetch({url:"users",method:"get",splice:"/"+t,headers:{Authorization:"Bearer "+s.default.getLItem("Authorization")},data:{include:["groups"]}}).then((function(t){if(!t.errors)return t;e.$toast.fail(t.errors[0].code)})).catch((function(t){}))},getAuthority:function(t){var e=this;return this.appFetch({url:"authority",method:"get",splice:"/"+t,data:{include:["permission"]}}).then((function(t){if(!t.errors)return t;e.$toast.fail(t.errors[0].code)})).catch((function(t){}))},imageSwiper:function(t,e,a){(0,n.default)({images:this.firstpostImageListProp,startPosition:t,showIndex:!0,showIndicators:!0,loop:!0})}},mounted:function(){},beforeRouteLeave:function(t,e,a){a()}}}}]);