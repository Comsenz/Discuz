(window.webpackJsonp=window.webpackJsonp||[]).push([[57],{"6hgt":function(t,e,a){"use strict";var s=function(){var t=this,e=t.$createElement,a=t._self._c||e;return a("div",{staticClass:"pay-the-fee-box"},[a("PayHeader"),t._v(" "),a("main",{staticClass:"pay-the-fee-main"},[t._m(0),t._v(" "),a("van-button",{attrs:{type:"primary"},on:{click:t.payClick}},[t._v("立即付费,获得权限")]),t._v(" "),a("p",{staticClass:"pay-the-fee-title-footer"},[t._v("￥"+t._s(t.sitePrice)+" / "+t._s(t.siteExpire))])],1),t._v(" "),a("div",{staticClass:"pay-the-fee-permission"},[t._m(1),t._v(" "),a("div",{staticClass:"pay-the-fee-per-list"},[a("div",{staticClass:"pay-the-fee-per-name"},[t._v("权限列表")]),t._v(" "),t._l(t.limitList.permission,(function(e,s){return a("div",{},[e._data.permission&&"viewThreads"==e._data.permission?a("p",{staticClass:"powerChi"},[t._v("查看主题列表")]):t._e(),t._v(" "),e._data.permission&&"thread.viewPosts"==e._data.permission?a("p",{staticClass:"powerChi"},[t._v("查看主题")]):t._e(),t._v(" "),e._data.permission&&"createThread"==e._data.permission?a("p",{staticClass:"powerChi"},[t._v("发表主题")]):t._e(),t._v(" "),e._data.permission&&"thread.reply"==e._data.permission?a("p",{staticClass:"powerChi"},[t._v("回复主题")]):t._e(),t._v(" "),e._data.permission&&"attachment.create.0"==e._data.permission?a("p",{staticClass:"powerChi"},[t._v("上传附件")]):t._e(),t._v(" "),e._data.permission&&"attachment.create.1"==e._data.permission?a("p",{staticClass:"powerChi"},[t._v("上传图片")]):t._e(),t._v(" "),e._data.permission&&"attachment.view.0"==e._data.permission?a("p",{staticClass:"powerChi"},[t._v("查看附件")]):t._e(),t._v(" "),e._data.permission&&"attachment.view.1"==e._data.permission?a("p",{staticClass:"powerChi"},[t._v("查看图片")]):t._e(),t._v(" "),e._data.permission&&"viewUserList"==e._data.permission?a("p",{staticClass:"powerChi"},[t._v("站点会员列表")]):t._e(),t._v(" "),e._data.permission&&"attachment.delete"==e._data.permission?a("p",{staticClass:"powerChi"},[t._v("删除附件")]):t._e(),t._v(" "),e._data.permission&&"cash.create"==e._data.permission?a("p",{staticClass:"powerChi"},[t._v("申请提现")]):t._e(),t._v(" "),e._data.permission&&"order.create"==e._data.permission?a("p",{staticClass:"powerChi"},[t._v("创建订单")]):t._e(),t._v(" "),e._data.permission&&"thread.hide"==e._data.permission?a("p",{staticClass:"powerChi"},[t._v("删除主题")]):t._e(),t._v(" "),e._data.permission&&"thread.hidePosts"==e._data.permission?a("p",{staticClass:"powerChi"},[t._v("删除回复")]):t._e(),t._v(" "),e._data.permission&&"thread.favorite"==e._data.permission?a("p",{staticClass:"powerChi"},[t._v("帖子收藏")]):t._e(),t._v(" "),e._data.permission&&"thread.likePosts"==e._data.permission?a("p",{staticClass:"powerChi"},[t._v("帖子点赞")]):t._e(),t._v(" "),e._data.permission&&"user.view"==e._data.permission?a("p",{staticClass:"powerChi"},[t._v("查看用户信息")]):t._e(),t._v(" "),e._data.permission&&"viewSiteInfo"==e._data.permission?a("p",{staticClass:"powerChi"},[t._v("站点信息")]):t._e(),t._v(" "),e._data.permission&&"user.edit"==e._data.permission?a("p",{staticClass:"powerChi"},[t._v("编辑用户状态")]):t._e(),t._v(" "),e._data.permission&&"group.edit"==e._data.permission?a("p",{staticClass:"powerChi"},[t._v("编辑用户组")]):t._e(),t._v(" "),e._data.permission&&"createInvite"==e._data.permission?a("p",{staticClass:"powerChi"},[t._v("管理-邀请加入")]):t._e(),t._v(" "),e._data.permission&&"thread.batchEdit"==e._data.permission?a("p",{staticClass:"powerChi"},[t._v("批量管理主题")]):t._e(),t._v(" "),e._data.permission&&"thread.editPosts"==e._data.permission?a("p",{staticClass:"powerChi"},[t._v("编辑")]):t._e(),t._v(" "),e._data.permission&&"thread.essence"==e._data.permission?a("p",{staticClass:"powerChi"},[t._v("加精")]):t._e(),t._v(" "),e._data.permission&&"thread.sticky"==e._data.permission?a("p",{staticClass:"powerChi"},[t._v("置顶")]):t._e()])}))],2),t._v(" "),a("div",{staticClass:"pay-the-fee-per-list-footer"},[a("p",{on:{click:t.leapFrogClick}},[t._v("跳过，进入首页")])])]),t._v(" "),a("van-popup",{staticClass:"qrCodeBox",attrs:{round:"","close-icon-position":"top-right",closeable:"","get-container":"body"},model:{value:t.qrcodeShow,callback:function(e){t.qrcodeShow=e},expression:"qrcodeShow"}},[a("span",{staticClass:"popupTit"},[t._v("立即支付")]),t._v(" "),a("div",{staticClass:"payNum"},[t._v("￥"),a("span",[t._v(t._s(t.amountNum))])]),t._v(" "),a("div",{staticClass:"payType"},[a("span",{staticClass:"typeLeft"},[t._v("支付方式")]),t._v(" "),a("span",{staticClass:"typeRight"},[a("i",{staticClass:"icon iconfont icon-wepay"}),t._v("微信支付")])]),t._v(" "),a("img",{staticClass:"qrCode",attrs:{src:t.codeUrl,alt:"微信支付二维码"}}),t._v(" "),a("p",{staticClass:"payTip"},[t._v("微信识别二维码支付")])])],1)},i=[function(){var t=this.$createElement,e=this._self._c||t;return e("div",{staticClass:"pay-the-fee-titel"},[e("h2",[this._v("支付费用")])])},function(){var t=this.$createElement,e=this._self._c||t;return e("div",{staticClass:"pay-the-fee-per-title"},[e("h3",[this._v("作为成员，您将获得以下权限")])])}];a.d(e,"a",(function(){return s})),a.d(e,"b",(function(){return i}))},NdMT:function(t,e,a){},Q3yS:function(t,e,a){"use strict";Object.defineProperty(e,"__esModule",{value:!0});var s=r(a("JZuw")),i=r(a("VVfg"));function r(t){return t&&t.__esModule?t:{default:t}}e.default={data:function(){return{sitePrice:"",siteExpire:"",orderSn:"",wxPayHref:"",qrcodeShow:!1,codeUrl:"",amountNum:"",payStatus:!1,payStatusNum:0,authorityList:"",tokenId:"",dialogShow:!1,groupId:"",limitList:[]}},components:{PayHeader:s.default},methods:{leapFrogClick:function(){this.$router.push({path:"pay-circle-login"})},onBridgeReady:function(t){var e=this,a=this;WeixinJSBridge.invoke("getBrandWCPayRequest",{appId:t.data.attributes.wechat_js.appId,timeStamp:t.data.attributes.wechat_js.timeStamp,nonceStr:t.data.attributes.wechat_js.nonceStr,package:t.data.attributes.wechat_js.package,signType:"MD5",paySign:t.data.attributes.wechat_js.paySign},(function(t){})),setTimeout((function(){var t=a.$toast.loading({duration:0,forbidClick:!0,message:"支付状态查询中..."}),s=5,i=setInterval((function(){s--,e.getUsers(a.tokenId).then((function(e){console.log(s),e.errors?(clearInterval(i),t.message="支付失败，请重新支付！",setTimeout((function(){t.clear()}),2e3)):s>0||!e.readdata._data.paid?t.message="正在查询订单...":e.readdata._data.paid?(clearInterval(i),t.message="支付成功，正在跳转首页...",t.clear(),a.$router.push({path:"/"})):(clearInterval(i),t.message="支付失败，请重新支付！",t.clear())}))}),1e3)}),3e3)},payClick:function(){var t=this,e=this.appCommonH.isWeixin().isWeixin,a=this.appCommonH.isWeixin().isPhone;e?(console.log("微信"),this.getOrderSn().then((function(){t.orderPay(12).then((function(e){"undefined"==typeof WeixinJSBridge?document.addEventListener?document.addEventListener("WeixinJSBridgeReady",t.onBridgeReady(e),!1):document.attachEvent&&(document.attachEvent("WeixinJSBridgeReady",t.onBridgeReady(e)),document.attachEvent("onWeixinJSBridgeReady",t.onBridgeReady(e))):t.onBridgeReady(e)}))}))):a?(console.log("手机浏览器"),this.getOrderSn().then((function(){t.orderPay(11).then((function(e){t.wxPayHref=e.readdata._data.wechat_h5_link,window.location.href=t.wxPayHref;var a=setInterval((function(){t.payStatus&&t.payStatusNum>10&&clearInterval(a),t.getUsersInfo()}),3e3)}))}))):(console.log("pc"),this.getOrderSn().then((function(){t.orderPay(10).then((function(e){console.log(e),t.codeUrl=e.readdata._data.wechat_qrcode,t.qrcodeShow=!0;var a=setInterval((function(){t.payStatus&&t.payStatusNum>10&&clearInterval(a),t.getUsersInfo()}),3e3)}))})))},completePayment:function(){var t=this;this.getUsers(this.tokenId).then((function(e){e.errors?t.$toast.message="支付失败，请重新支付！":e.readdata._data.paid?(t.$toast.message="支付成功，正在跳转首页...",t.dialogShow=!1):t.$toast.message="支付失败，请重新支付！"}))},getForum:function(){var t=this;this.appFetch({url:"forum",method:"get",data:{}}).then((function(e){if(console.log(e),e.errors)t.$toast.fail(e.errors[0].code);else{t.sitePrice=e.readdata._data.set_site.site_price;var a=e.readdata._data.set_site.site_expire;switch(a){case"":case"0":t.siteExpire="永久有效";break;default:t.siteExpire="有效期自加入起"+a+"天"}}})).catch((function(t){console.log(t)}))},getOrderSn:function(){var t=this;return this.appFetch({url:"orderList",method:"post",data:{type:1}}).then((function(e){console.log(e),e.errors?t.$toast.fail(e.errors[0].code):t.orderSn=e.readdata._data.order_sn})).catch((function(t){console.log(t)}))},orderPay:function(t){var e=this;return this.appFetch({url:"orderPay",method:"post",splice:"/"+this.orderSn,data:{payment_type:t}}).then((function(t){if(console.log(t),!t.errors)return t;e.$toast.fail(t.errors[0].code)})).catch((function(t){console.log(t)}))},getUsersInfo:function(){var t=this;this.appFetch({url:"users",method:"get",splice:"/"+i.default.getLItem("tokenId"),data:{include:["groups"]}}).then((function(e){console.log(e),console.log(e.readdata._data.paid),e.errors?t.$toast.fail(e.errors[0].code):(t.payStatus=e.readdata._data.paid,t.payStatusNum=1,t.payStatus&&(t.qrcodeShow=!1,t.$router.push("/"),t.payStatusNum=11,clearInterval(pay)))})).catch((function(t){console.log(t)}))},getUsers:function(t){var e=this;return this.appFetch({url:"users",method:"get",splice:"/"+t,headers:{Authorization:"Bearer "+i.default.getLItem("Authorization")},data:{include:["groups"]}}).then((function(t){if(!t.errors)return console.log(t),t;e.$toast.fail(t.errors[0].code)})).catch((function(t){console.log(t)}))},getAuthority:function(t){var e=this;return this.appFetch({url:"authority",method:"get",splice:"/"+t,data:{include:["permission"]}}).then((function(t){if(console.log(t),!t.errors)return t;e.$toast.fail(t.errors[0].code)})).catch((function(t){console.log(t)}))},getGroups:function(){var t=this;this.appFetch({url:"groups",method:"get",data:{include:["permission"],"filter[isDefault]":1}}).then((function(e){e.errors?t.$toast.fail(e.errors[0].code):(t.groupId=e.readdata[0]._data.id,t.getGroupsList())}))},getGroupsList:function(){var t=this;this.appFetch({url:"groups",method:"get",splice:"/"+this.groupId,data:{include:["permission"]}}).then((function(e){e.errors?t.$toast.fail(e.errors[0].code):t.limitList=e.readdata}))}},created:function(){var t=this;this.getForum(),this.getGroups(),this.getUsers(i.default.getLItem("tokenId")).then((function(e){t.getAuthority(e.readdata.groups[0]._data.id)})),this.tokenId=i.default.getLItem("tokenId"),this.amountNum=i.default.getLItem("siteInfo")._data.set_site.site_price}}},YXY0:function(t,e,a){"use strict";a.r(e);var s=a("eHCm"),i=a.n(s);for(var r in s)"default"!==r&&function(t){a.d(e,t,(function(){return s[t]}))}(r);e.default=i.a},eHCm:function(t,e,a){"use strict";Object.defineProperty(e,"__esModule",{value:!0});var s=r(a("QbLZ"));a("NdMT");var i=r(a("Q3yS"));function r(t){return t&&t.__esModule?t:{default:t}}e.default=(0,s.default)({name:"pay-the-fee-view"},i.default)},iX2B:function(t,e,a){"use strict";a.r(e);var s=a("6hgt"),i=a("YXY0");for(var r in i)"default"!==r&&function(t){a.d(e,t,(function(){return i[t]}))}(r);var o=a("KHd+"),n=Object(o.a)(i.default,s.a,s.b,!1,null,null,null);e.default=n.exports}}]);