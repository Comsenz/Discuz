(window.webpackJsonp=window.webpackJsonp||[]).push([[14],{"4d7F":function(t,e,n){t.exports={default:n("aW7e"),__esModule:!0}},"8gHz":function(t,e,n){var r=n("5K7Z"),i=n("eaoh"),o=n("UWiX")("species");t.exports=function(t,e){var n,a=r(t).constructor;return void 0===a||null==(n=r(a)[o])?e:i(n)}},EXMj:function(t,e){t.exports=function(t,e,n,r){if(!(t instanceof e)||void 0!==r&&r in t)throw TypeError(n+": incorrect invocation!");return t}},"JMW+":function(t,e,n){"use strict";var r,i,o,a,s=n("uOPS"),c=n("5T2Y"),u=n("2GTP"),f=n("QMMT"),l=n("Y7ZC"),d=n("93I4"),h=n("eaoh"),p=n("EXMj"),v=n("oioR"),_=n("8gHz"),m=n("QXhf").set,g=n("q6LJ")(),y=n("ZW5q"),w=n("RDmV"),x=n("vBP9"),P=n("zXhZ"),C=c.TypeError,S=c.process,b=S&&S.versions,E=b&&b.v8||"",k=c.Promise,T="process"==f(S),I=function(){},W=i=y.f,j=!!function(){try{var t=k.resolve(1),e=(t.constructor={})[n("UWiX")("species")]=function(t){t(I,I)};return(T||"function"==typeof PromiseRejectionEvent)&&t.then(I)instanceof e&&0!==E.indexOf("6.6")&&-1===x.indexOf("Chrome/66")}catch(t){}}(),B=function(t){var e;return!(!d(t)||"function"!=typeof(e=t.then))&&e},M=function(t,e){if(!t._n){t._n=!0;var n=t._c;g((function(){for(var r=t._v,i=1==t._s,o=0,a=function(e){var n,o,a,s=i?e.ok:e.fail,c=e.resolve,u=e.reject,f=e.domain;try{s?(i||(2==t._h&&U(t),t._h=1),!0===s?n=r:(f&&f.enter(),n=s(r),f&&(f.exit(),a=!0)),n===e.promise?u(C("Promise-chain cycle")):(o=B(n))?o.call(n,c,u):c(n)):u(r)}catch(t){f&&!a&&f.exit(),u(t)}};n.length>o;)a(n[o++]);t._c=[],t._n=!1,e&&!t._h&&R(t)}))}},R=function(t){m.call(c,(function(){var e,n,r,i=t._v,o=q(t);if(o&&(e=w((function(){T?S.emit("unhandledRejection",i,t):(n=c.onunhandledrejection)?n({promise:t,reason:i}):(r=c.console)&&r.error&&r.error("Unhandled promise rejection",i)})),t._h=T||q(t)?2:1),t._a=void 0,o&&e.e)throw e.v}))},q=function(t){return 1!==t._h&&0===(t._a||t._c).length},U=function(t){m.call(c,(function(){var e;T?S.emit("rejectionHandled",t):(e=c.onrejectionhandled)&&e({promise:t,reason:t._v})}))},J=function(t){var e=this;e._d||(e._d=!0,(e=e._w||e)._v=t,e._s=2,e._a||(e._a=e._c.slice()),M(e,!0))},F=function(t){var e,n=this;if(!n._d){n._d=!0,n=n._w||n;try{if(n===t)throw C("Promise can't be resolved itself");(e=B(t))?g((function(){var r={_w:n,_d:!1};try{e.call(t,u(F,r,1),u(J,r,1))}catch(t){J.call(r,t)}})):(n._v=t,n._s=1,M(n,!1))}catch(t){J.call({_w:n,_d:!1},t)}}};j||(k=function(t){p(this,k,"Promise","_h"),h(t),r.call(this);try{t(u(F,this,1),u(J,this,1))}catch(t){J.call(this,t)}},(r=function(t){this._c=[],this._a=void 0,this._s=0,this._d=!1,this._v=void 0,this._h=0,this._n=!1}).prototype=n("XJU/")(k.prototype,{then:function(t,e){var n=W(_(this,k));return n.ok="function"!=typeof t||t,n.fail="function"==typeof e&&e,n.domain=T?S.domain:void 0,this._c.push(n),this._a&&this._a.push(n),this._s&&M(this,!1),n.promise},catch:function(t){return this.then(void 0,t)}}),o=function(){var t=new r;this.promise=t,this.resolve=u(F,t,1),this.reject=u(J,t,1)},y.f=W=function(t){return t===k||t===a?new o(t):i(t)}),l(l.G+l.W+l.F*!j,{Promise:k}),n("RfKB")(k,"Promise"),n("TJWN")("Promise"),a=n("WEpk").Promise,l(l.S+l.F*!j,"Promise",{reject:function(t){var e=W(this);return(0,e.reject)(t),e.promise}}),l(l.S+l.F*(s||!j),"Promise",{resolve:function(t){return P(s&&this===a?k:this,t)}}),l(l.S+l.F*!(j&&n("TuGD")((function(t){k.all(t).catch(I)}))),"Promise",{all:function(t){var e=this,n=W(e),r=n.resolve,i=n.reject,o=w((function(){var n=[],o=0,a=1;v(t,!1,(function(t){var s=o++,c=!1;n.push(void 0),a++,e.resolve(t).then((function(t){c||(c=!0,n[s]=t,--a||r(n))}),i)})),--a||r(n)}));return o.e&&i(o.v),n.promise},race:function(t){var e=this,n=W(e),r=n.reject,i=w((function(){v(t,!1,(function(t){e.resolve(t).then(n.resolve,r)}))}));return i.e&&r(i.v),n.promise}})},MCSJ:function(t,e){t.exports=function(t,e,n){var r=void 0===n;switch(e.length){case 0:return r?t():t.call(n);case 1:return r?t(e[0]):t.call(n,e[0]);case 2:return r?t(e[0],e[1]):t.call(n,e[0],e[1]);case 3:return r?t(e[0],e[1],e[2]):t.call(n,e[0],e[1],e[2]);case 4:return r?t(e[0],e[1],e[2],e[3]):t.call(n,e[0],e[1],e[2],e[3])}return t.apply(n,e)}},PBE1:function(t,e,n){"use strict";var r=n("Y7ZC"),i=n("WEpk"),o=n("5T2Y"),a=n("8gHz"),s=n("zXhZ");r(r.P+r.R,"Promise",{finally:function(t){var e=a(this,i.Promise||o.Promise),n="function"==typeof t;return this.then(n?function(n){return s(e,t()).then((function(){return n}))}:t,n?function(n){return s(e,t()).then((function(){throw n}))}:t)}})},"Q/yX":function(t,e,n){"use strict";var r=n("Y7ZC"),i=n("ZW5q"),o=n("RDmV");r(r.S,"Promise",{try:function(t){var e=i.f(this),n=o(t);return(n.e?e.reject:e.resolve)(n.v),e.promise}})},Q3yS:function(t,e,n){"use strict";Object.defineProperty(e,"__esModule",{value:!0});var r=a(n("4d7F")),i=a(n("JZuw"));n("cm59");var o=a(n("VVfg"));function a(t){return t&&t.__esModule?t:{default:t}}e.default={data:function(){return{sitePrice:"",siteExpire:"",orderSn:"",wxPayHref:"",qrcodeShow:!1,codeUrl:"",amountNum:"",payStatus:!1,payStatusNum:0,authorityList:"",tokenId:""}},components:{PayHeader:i.default},methods:{leapFrogClick:function(){this.$router.push({path:"pay-circle-login"})},onBridgeReady:function(t){var e=this;new r.default((function(e,n){WeixinJSBridge.invoke("getBrandWCPayRequest",{appId:t.data.attributes.wechat_js.appId,timeStamp:t.data.attributes.wechat_js.timeStamp,nonceStr:t.data.attributes.wechat_js.nonceStr,package:t.data.attributes.wechat_js.package,signType:"MD5",paySign:t.data.attributes.wechat_js.paySign},(function(t){console.log(t),"get_brand_wcpay_request:ok"==t.err_msg?(alert("支付成功"),alert(t.err_msg)):"get_brand_wcpay_request:cancel"==t.err_msg?(alert("支付过程中用户取消"),alert(t.err_msg)):"get_brand_wcpay_request:fail"==t.err_msg&&(alert("支付失败"),alert(t.err_msg))}))})).then((function(){alert("开始查询接口");var t=e.$toast.loading({duration:0,forbidClick:!0,message:"正在查询订单..."}),n=5,r=setInterval((function(){n--,e.getUsers(e.tokenId).then((function(e){console.log(n),e.errors?(clearInterval(r),t.message="支付失败，请重新支付！",setTimeout((function(){t.clear()}),2e3)):n>0||!e.readdata._data.paid?t.message="正在查询订单...":e.readdata._data.paid?(clearInterval(r),t.message="支付成功，正在跳转首页...",t.clear()):(clearInterval(r),t.message="支付失败，请重新支付！",t.clear())}))}),1e3)}))},payClick:function(){var t=this,e=this.appCommonH.isWeixin().isWeixin,n=this.appCommonH.isWeixin().isPhone;e?(console.log("微信"),this.getOrderSn().then((function(){t.orderPay(12).then((function(e){"undefined"==typeof WeixinJSBridge?document.addEventListener?document.addEventListener("WeixinJSBridgeReady",t.onBridgeReady(e),!1):document.attachEvent&&(document.attachEvent("WeixinJSBridgeReady",t.onBridgeReady(e)),document.attachEvent("onWeixinJSBridgeReady",t.onBridgeReady(e))):t.onBridgeReady(e)}))}))):n?(console.log("手机浏览器"),this.getOrderSn().then((function(){t.orderPay(11).then((function(e){t.wxPayHref=e.readdata._data.wechat_h5_link,window.location.href=t.wxPayHref}))}))):(console.log("pc"),this.getOrderSn().then((function(){t.orderPay(10).then((function(e){if(console.log(e),t.codeUrl="data:image/jpg;base64,"+e.readdata._data.wechat_qrcode,t.qrcodeShow=!0,t.payStatus&&t.payStatusNum<10)clearInterval(n);else var n=setInterval((function(){t.getUsersInfo()}),3e3)}))})))},getForum:function(){var t=this;this.appFetch({url:"forum",method:"get",data:{}}).then((function(e){console.log(e),t.sitePrice=e.readdata._data.setsite.site_price;var n=e.readdata._data.setsite.site_expire;switch(n){case"":case"0":t.siteExpire="永久有效";break;default:t.siteExpire="有效期自加入起"+n+"天"}})).catch((function(t){console.log(t)}))},getOrderSn:function(){var t=this;return this.appFetch({url:"orderList",method:"post",data:{type:1}}).then((function(e){console.log(e),t.orderSn=e.readdata._data.order_sn})).catch((function(t){console.log(t)}))},orderPay:function(t){return this.appFetch({url:"orderPay",method:"post",splice:"/"+this.orderSn,data:{payment_type:t}}).then((function(t){return console.log(t),t})).catch((function(t){console.log(t)}))},getUsersInfo:function(){var t=this;this.appFetch({url:"users",method:"get",splice:"/"+o.default.getLItem("tokenId"),data:{include:["groups"]}}).then((function(e){console.log(e),console.log(e.readdata._data.paid),t.payStatus=e.readdata._data.paid,t.payStatusNum=1,t.payStatus&&(t.qrcodeShow=!1,t.$router.push("/"),t.payStatusNum=11,clearInterval(pay))})).catch((function(t){console.log(t)}))},getUsers:function(t){return this.appFetch({url:"users",method:"get",splice:"/"+t,headers:{Authorization:"Bearer "+o.default.getLItem("Authorization")},data:{include:["groups"]}}).then((function(t){return console.log(t),t})).catch((function(t){console.log(t)}))},getAuthority:function(t){return this.appFetch({url:"authority",method:"get",splice:"/"+t,data:{include:["permission"]}}).then((function(t){return console.log(t),t})).catch((function(t){console.log(t)}))}},created:function(){var t=this;this.getForum(),this.getUsers(o.default.getLItem("tokenId")).then((function(e){t.getAuthority(e.readdata.groups[0]._data.id)})),this.tokenId=o.default.getLItem("tokenId"),this.amountNum=o.default.getLItem("siteInfo")._data.setsite.site_price}}},QXhf:function(t,e,n){var r,i,o,a=n("2GTP"),s=n("MCSJ"),c=n("MvwC"),u=n("Hsns"),f=n("5T2Y"),l=f.process,d=f.setImmediate,h=f.clearImmediate,p=f.MessageChannel,v=f.Dispatch,_=0,m={},g=function(){var t=+this;if(m.hasOwnProperty(t)){var e=m[t];delete m[t],e()}},y=function(t){g.call(t.data)};d&&h||(d=function(t){for(var e=[],n=1;arguments.length>n;)e.push(arguments[n++]);return m[++_]=function(){s("function"==typeof t?t:Function(t),e)},r(_),_},h=function(t){delete m[t]},"process"==n("a0xu")(l)?r=function(t){l.nextTick(a(g,t,1))}:v&&v.now?r=function(t){v.now(a(g,t,1))}:p?(o=(i=new p).port2,i.port1.onmessage=y,r=a(o.postMessage,o,1)):f.addEventListener&&"function"==typeof postMessage&&!f.importScripts?(r=function(t){f.postMessage(t+"","*")},f.addEventListener("message",y,!1)):r="onreadystatechange"in u("script")?function(t){c.appendChild(u("script")).onreadystatechange=function(){c.removeChild(this),g.call(t)}}:function(t){setTimeout(a(g,t,1),0)}),t.exports={set:d,clear:h}},RDmV:function(t,e){t.exports=function(t){try{return{e:!1,v:t()}}catch(t){return{e:!0,v:t}}}},TJWN:function(t,e,n){"use strict";var r=n("5T2Y"),i=n("WEpk"),o=n("2faE"),a=n("jmDH"),s=n("UWiX")("species");t.exports=function(t){var e="function"==typeof i[t]?i[t]:r[t];a&&e&&!e[s]&&o.f(e,s,{configurable:!0,get:function(){return this}})}},"XJU/":function(t,e,n){var r=n("NegM");t.exports=function(t,e,n){for(var i in e)n&&t[i]?t[i]=e[i]:r(t,i,e[i]);return t}},YXY0:function(t,e,n){"use strict";n.r(e);var r=n("eHCm"),i=n.n(r);for(var o in r)"default"!==o&&function(t){n.d(e,t,(function(){return r[t]}))}(o);e.default=i.a},ZW5q:function(t,e,n){"use strict";var r=n("eaoh");function i(t){var e,n;this.promise=new t((function(t,r){if(void 0!==e||void 0!==n)throw TypeError("Bad Promise constructor");e=t,n=r})),this.resolve=r(e),this.reject=r(n)}t.exports.f=function(t){return new i(t)}},aW7e:function(t,e,n){n("wgeU"),n("FlQf"),n("bBy9"),n("JMW+"),n("PBE1"),n("Q/yX"),t.exports=n("WEpk").Promise},cm59:function(t,e,n){},eHCm:function(t,e,n){"use strict";Object.defineProperty(e,"__esModule",{value:!0});var r=o(n("QbLZ"));n("i4TU");var i=o(n("Q3yS"));function o(t){return t&&t.__esModule?t:{default:t}}e.default=(0,r.default)({name:"pay-the-fee-view"},i.default)},i4TU:function(t,e,n){},iX2B:function(t,e,n){"use strict";n.r(e);var r=n("qCUt"),i=n("YXY0");for(var o in i)"default"!==o&&function(t){n.d(e,t,(function(){return i[t]}))}(o);var a=n("KHd+"),s=Object(a.a)(i.default,r.a,r.b,!1,null,null,null);e.default=s.exports},oioR:function(t,e,n){var r=n("2GTP"),i=n("sNwI"),o=n("NwJ3"),a=n("5K7Z"),s=n("tEej"),c=n("fNZA"),u={},f={};(e=t.exports=function(t,e,n,l,d){var h,p,v,_,m=d?function(){return t}:c(t),g=r(n,l,e?2:1),y=0;if("function"!=typeof m)throw TypeError(t+" is not iterable!");if(o(m)){for(h=s(t.length);h>y;y++)if((_=e?g(a(p=t[y])[0],p[1]):g(t[y]))===u||_===f)return _}else for(v=m.call(t);!(p=v.next()).done;)if((_=i(v,g,p.value,e))===u||_===f)return _}).BREAK=u,e.RETURN=f},q6LJ:function(t,e,n){var r=n("5T2Y"),i=n("QXhf").set,o=r.MutationObserver||r.WebKitMutationObserver,a=r.process,s=r.Promise,c="process"==n("a0xu")(a);t.exports=function(){var t,e,n,u=function(){var r,i;for(c&&(r=a.domain)&&r.exit();t;){i=t.fn,t=t.next;try{i()}catch(r){throw t?n():e=void 0,r}}e=void 0,r&&r.enter()};if(c)n=function(){a.nextTick(u)};else if(!o||r.navigator&&r.navigator.standalone)if(s&&s.resolve){var f=s.resolve(void 0);n=function(){f.then(u)}}else n=function(){i.call(r,u)};else{var l=!0,d=document.createTextNode("");new o(u).observe(d,{characterData:!0}),n=function(){d.data=l=!l}}return function(r){var i={fn:r,next:void 0};e&&(e.next=i),t||(t=i,n()),e=i}}},qCUt:function(t,e,n){"use strict";var r=function(){var t=this,e=t.$createElement,n=t._self._c||e;return n("div",{staticClass:"pay-the-fee-box"},[n("PayHeader"),t._v(" "),n("main",{staticClass:"pay-the-fee-main"},[t._m(0),t._v(" "),n("van-button",{attrs:{type:"primary"},on:{click:t.payClick}},[t._v("立即付费,获得权限")]),t._v(" "),n("p",{staticClass:"pay-the-fee-title-footer"},[t._v("￥"+t._s(t.sitePrice)+" / "+t._s(t.siteExpire))])],1),t._v(" "),n("div",{staticClass:"pay-the-fee-permission"},[t._m(1),t._v(" "),t._m(2),t._v(" "),n("div",{staticClass:"pay-the-fee-per-list-footer"},[n("p",{on:{click:t.leapFrogClick}},[t._v("跳过，进入首页")])])]),t._v(" "),n("van-popup",{staticClass:"qrCodeBox",attrs:{round:"","close-icon-position":"top-right",closeable:"","get-container":"body"},model:{value:t.qrcodeShow,callback:function(e){t.qrcodeShow=e},expression:"qrcodeShow"}},[n("span",{staticClass:"popupTit"},[t._v("立即支付")]),t._v(" "),n("div",{staticClass:"payNum"},[t._v("￥"),n("span",[t._v(t._s(t.amountNum))])]),t._v(" "),n("div",{staticClass:"payType"},[n("span",{staticClass:"typeLeft"},[t._v("支付方式")]),t._v(" "),n("span",{staticClass:"typeRight"},[n("i",{staticClass:"icon iconfont icon-wepay"}),t._v("微信支付")])]),t._v(" "),n("img",{staticClass:"qrCode",attrs:{src:t.codeUrl,alt:"微信支付二维码"}}),t._v(" "),n("p",{staticClass:"payTip"},[t._v("微信识别二维码支付")])])],1)},i=[function(){var t=this.$createElement,e=this._self._c||t;return e("div",{staticClass:"pay-the-fee-titel"},[e("h2",[this._v("支付费用")])])},function(){var t=this.$createElement,e=this._self._c||t;return e("div",{staticClass:"pay-the-fee-per-title"},[e("h3",[this._v("作为成员，您将获得以下权限")])])},function(){var t=this.$createElement,e=this._self._c||t;return e("div",{staticClass:"pay-the-fee-per-list"},[e("div",{staticClass:"pay-the-fee-per-name"},[this._v("帖子操作")]),this._v(" "),e("p",[this._v("查看主题")]),this._v(" "),e("p",[this._v("发图文贴")])])}];n.d(e,"a",(function(){return r})),n.d(e,"b",(function(){return i}))},vBP9:function(t,e,n){var r=n("5T2Y").navigator;t.exports=r&&r.userAgent||""},zXhZ:function(t,e,n){var r=n("5K7Z"),i=n("93I4"),o=n("ZW5q");t.exports=function(t,e){if(r(t),i(e)&&e.constructor===t)return e;var n=o.f(t);return(0,n.resolve)(e),n.promise}}}]);