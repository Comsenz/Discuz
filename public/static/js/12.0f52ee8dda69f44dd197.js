(window.webpackJsonp=window.webpackJsonp||[]).push([[12],{dWbn:function(t,e,a){"use strict";Object.defineProperty(e,"__esModule",{value:!0});var i=d(a("D1tn")),s=d(a("/umX")),r=d(a("y0A3")),n=d(a("VVfg")),o=d(a("6NK7"));d(a("eeyD")),a("a2Oh");function d(t){return t&&t.__esModule?t:{default:t}}e.default={data:function(){var t;return t={headBackShow:!0,rewardShow:!1,themeCon:!1,themeShow:!1,examineNum:"qqqq",rewardNumList:[{rewardNum:"0.01"},{rewardNum:"2"},{rewardNum:"5"},{rewardNum:"10"},{rewardNum:"20"},{rewardNum:"50"},{rewardNum:"88"},{rewardNum:"128"},{rewardNum:"666"}],qrcodeShow:!1,amountNum:"",codeUrl:"",showScreen:!1,request:!1,isliked:"",likedClass:"",imageShow:!1,index:1,firstpostImageList:[],siteMode:"",isPaid:"",situation1:!1,loginBtnFix:!1,loginHide:!1,siteInfo:!1,siteUsername:"",joinedAt:"",sitePrice:"",username:"",roleList:[],loading:!1,finished:!1,isLoading:!1,pageIndex:1,pageLimit:20,offset:100,groupId:"",menuStatus:!1,collectStatus:!1,collectFlag:"",postCount:0,postsList:"",likedUsers:[],rewardedUsers:[],token:!1,isWeixin:!1,isPhone:!1,isAndroid:!1,isiOS:!1,orderSn:"",payStatus:!1,payStatusNum:0,canViewPosts:"",canLike:"",canReply:"",themeUserId:"",userId:"",currentUserName:"",currentUserAvatarUrl:"",likedData:[],postsImages:[],allowRegister:"",loginWord:"登录 / 注册",viewportWidth:"",themeIsLiked:"",themeTitle:"",wxpay:"",twoChi:"",show:!1,payList:[{name:"钱包",icon:"icon-wallet"}]},(0,s.default)(t,"qrcodeShow",!1),(0,s.default)(t,"walletBalance",""),(0,s.default)(t,"errorInfo",""),(0,s.default)(t,"value",""),(0,s.default)(t,"codeUrl",""),(0,s.default)(t,"isLongArticle",!1),(0,s.default)(t,"userDet",""),(0,s.default)(t,"hideStyle",""),(0,s.default)(t,"likeTipShow",!0),(0,s.default)(t,"likeTipFlag","展开"),(0,s.default)(t,"likeLen",""),(0,s.default)(t,"limitLen",7),(0,s.default)(t,"rewardTipFlag","展开"),(0,s.default)(t,"userArrStatus",!1),(0,s.default)(t,"rewardTipShow",!0),(0,s.default)(t,"payLoading",!1),(0,s.default)(t,"clickStatus",!0),t},created:function(){this.viewportWidth=window.innerWidth;var t=navigator.userAgent;this.isAndroid=t.indexOf("Android")>-1||t.indexOf("Adr")>-1,this.isiOS=!!t.match(/\(i[^;]+;( U;)? CPU.+Mac OS X/),this.isWeixin=o.default.isWeixin().isWeixin,this.isPhone=o.default.isWeixin().isPhone,this.getInfo(),this.userId=n.default.getLItem("tokenId"),this.token=n.default.getLItem("Authorization"),this.getUser(),this.detailsLoad(!0),window.likeIsFold=this.likeIsFold,this.themeCon?this.themeShow=!0:this.themeShow=!1,1===n.default.getSItem("beforeState")&&(this.$router.go(0),n.default.setSItem("beforeState",2))},computed:{themeId:function(){return this.$route.params.themeId}},updated:function(){1!=this.isWeixin&&1!=this.isPhone&&this.limitWidth("detailsFooter")},methods:{downAttachment:function(t){this.isiOS&&this.$message("因iphone系统限制，您的手机无法下载文件。请使用安卓手机或电脑访问下载")},userArr:function(t,e){var a=this,i=[];return this.hideStyle=e?"":"display:none",t.forEach((function(t,e){i.push('<a  href="/home-page/'+t._data.id+'" style="'+(e>10?a.hideStyle:"")+'">'+t._data.username+"</a>")})),i=i.join(","),this.likeLen>10&&(i=i+"等"+this.likeLen+"人觉得很赞"),i},likeIsFold:function(){this.likeTipShow=!this.likeTipShow,this.likeTipFlag=this.likeTipShow?"展开":"收起",this.hideStyle=this.likeTipShow?"":"display:none",document.getElementById("likedUserList").innerHTML=this.userArr(this.themeCon.firstPost.likedUsers,!0)},rewardIsFold:function(t){this.rewardTipShow=!this.rewardTipShow,this.rewardTipFlag=this.rewardTipShow?"展开":"收起",this.limitLen=this.rewardTipShow?5:t},limitWidth:function(t){var e=window.innerWidth;document.getElementById(t).style.width="640px",document.getElementById(t).style.marginLeft=(e-640)/2+"px"},getInfo:function(){var t=this;this.appFetch({url:"forum",method:"get",data:{include:["users"]}}).then((function(e){if(e.errors)throw t.$toast.fail(e.errors[0].code),new Error(e.error);t.siteInfo=e.readdata,t.wxpay=e.readdata._data.paycenter.wxpay_close,"0"!=t.wxpay&&0!=t.wxpay||(t.twoChi=!0),t.isPayVal=e.readdata._data.set_site.site_mode,t.allowRegister=e.readdata._data.set_reg.register_close,t.allowRegister||(t.loginWord="登录"),null!=t.isPayVal&&""!=t.isPayVal&&(t.isPayVal=e.readdata._data.set_site.site_mode,t.detailIf(t.isPayVal,!1)),"1"===e.readdata._data.paycenter.wxpay_close&&t.payList.unshift({name:"微信支付",icon:"icon-wxpay"})}))},getUser:function(){var t=this,e=n.default.getLItem("tokenId");this.userId=e,this.userId&&this.appFetch({url:"users",method:"get",splice:"/"+this.userId,data:{include:"groups"}}).then((function(e){if(e.errors)throw t.$toast.fail(e.errors[0].code),new Error(e.error);t.userDet=e.readdata,t.currentUserName=e.readdata._data.username,t.currentUserAvatarUrl=e.readdata._data.avatarUrl,t.walletBalance=e.readdata._data.walletBalance,t.groupId=e.readdata.groups[0]._data.id}))},detailIf:function(t){var e=n.default.getLItem("Authorization");this.token=e,"public"==t&&(e?(this.loginBtnFix=!1,this.loginHide=!0,this.menuStatus=!0):(this.loginBtnFix=!0,this.loginHide=!1))},footFix:function(){var t=window.pageYOffset||document.documentElement.scrollTop||document.body.scrollTop;1==this.loginBtnFix&&(this.loginHide=!0,this.loginHide=t>80)},detailsLoad:function(){var t=this,e=arguments.length>0&&void 0!==arguments[0]&&arguments[0],a="threads/"+this.themeId;return this.appFetch({url:a,method:"get",data:{"filter[isDeleted]":"no",include:["user","posts","posts.user","posts.likedUsers","posts.images","firstPost","firstPost.likedUsers","firstPost.images","firstPost.attachments","rewardedUsers","category"],"page[number]":this.pageIndex,"page[limit]":this.pageLimit}}).then((function(a){if(a.errors)throw t.$toast.fail(a.errors[0].code),new Error(a.error);if(t.likeLen=a.readdata.firstPost.likedUsers.length,t.finished=a.readdata.posts.length<t.pageLimit,e){t.collectStatus=a.readdata._data.isFavorite,t.essenceStatus=a.readdata._data.isEssence,t.stickyStatus=a.readdata._data.isSticky,a.readdata._data.isLongArticle?t.themeTitle=a.readdata._data.title:t.themeTitle=a.readdata.firstPost._data.contentHtml,t.collectStatus?t.collectFlag="已收藏":t.collectFlag="收藏",t.essenceStatus?t.essenceFlag="取消加精":t.essenceFlag="加精",t.stickyStatus?t.stickyFlag="取消置顶":t.stickyFlag="置顶",t.themeShow=!0,t.themeCon=a.readdata,t.canLike=a.readdata.firstPost._data.canLike,t.canViewPosts=a.readdata._data.canViewPosts,t.canReply=a.readdata._data.canReply,t.postsList=a.readdata.posts,t.likedUsers=a.readdata.firstPost.likedUsers,t.rewardedUsers=a.readdata.rewardedUsers,t.themeUserId=a.readdata.user._data.id,t.isLongArticle=a.readdata._data.isLongArticle,a.readdata.firstPost._data.isLiked?t.themeIsLiked=!0:t.themeIsLiked=!1;var i=t.themeCon.firstPost.images.length;if(0===i)return;for(var s=[],r=0;r<i;r++)s.push(t.themeCon.firstPost.images[r]._data.thumbUrl);t.firstpostImageList=s,t.postsList.map((function(e){var a=[];e.images.map((function(t){return a.push(t._data.url)})),t.postsImages.push(a)}))}else t.themeCon.posts=t.themeCon.posts.concat(a.readdata.posts),t.likeLen=themeCon.firstPost.likedUsers.length})).catch((function(e){t.loading&&1!==t.pageIndex&&t.pageIndex--})).finally((function(){t.loading=!1}))},imageSwiper:function(t,e,a){"detailImg"==e?(0,i.default)({images:this.firstpostImageList,startPosition:t,showIndex:!0,showIndicators:!0,loop:!0}):"replyImg"==e&&(0,i.default)({images:this.postsImages[a],startPosition:t,showIndex:!0,showIndicators:!0,loop:!0})},onChangeImgPreview:function(){this.index=index},cutString:function(t,e){if(2*t.length<=e)return t;for(var a=0,i="",s=0;s<t.length;s++)if(i+=t.charAt(s),t.charCodeAt(s)>128){if((a+=2)>=e)return i.substring(0,i.length-1)+"..."}else if((a+=1)>=e)return i.substring(0,i.length-2)+"...";return i},shareTheme:function(){var t="";t="pay"===this.isPayVal?r.default.baseUrl+"/pay-circle-con/"+this.themeId+"/"+this.groupId:r.default.baseUrl+"/details/"+this.themeId;var e=document.createElement("input");this.themeTitle=this.themeTitle.replace(/<img(?:.|\s)*?>/g,""),this.themeTitle=this.themeTitle.replace(/(<\/?br.*?>)/gi,""),this.themeTitle=this.themeTitle.replace(/(<\/?p.*?>)/gi,""),this.themeTitle=this.themeTitle.replace(/\s+/g,""),this.themeTitle=this.cutString(this.themeTitle,40),e.value=this.themeTitle+"  "+t,document.body.appendChild(e),e.select(),document.execCommand("Copy"),e.className="oInput",e.style.display="none",this.$toast.success("分享链接已复制成功")},signOut:function(){n.default.removeLItem("tokenId"),n.default.removeLItem("Authorization"),this.$router.push({path:"/login-user"})},loginJump:function(){n.default.setSItem("beforeVisiting",this.$route.path),this.$router.replace({path:"/login-user"}),n.default.setLItem("themeId",this.themeId)},registerJump:function(){this.$router.push({path:"/sign-up"}),n.default.setLItem("themeId",this.themeId)},jumpPerDet:function(t){this.$router.push({path:"/home-page/"+t})},bindScreen:function(){this.showScreen=!this.showScreen},listenEvt:function(t){this.$refs.screenBox&&(this.$refs.screenBox.contains(t.target)||(this.showScreen=!1))},themeOpera:function(t,e,a,i){if(this.token){var s=new Object;1==e?(this.collectStatus?s.isFavorite=!1:s.isFavorite=!0,"",this.themeOpeRequest(s,a,"1")):2==e?("",this.essenceStatus?s.isEssence=!1:s.isEssence=!0,this.themeOpeRequest(s,a,"2")):3==e?("",this.stickyStatus?s.isSticky=!1:s.isSticky=!0,this.themeOpeRequest(s,a,"3")):4==e?(s.isDeleted=!0,"",this.themeOpeRequest(s,a,"4")):this.isLongArticle?this.$router.replace({path:"/edit-long-text/"+this.themeId}):this.$router.replace({path:"/edit-topic/"+this.themeId})}else this.$router.push({path:"/login-user",name:"login-user"})},themeOpeRequest:function(t,e,a){var i=this,s="threads/"+this.themeId;this.appFetch({url:s,method:"patch",data:{data:{type:"threads",attributes:t},relationships:{category:{data:{type:"categories",id:e}}}}}).then((function(t){if(t.errors)throw i.$toast.fail(t.errors[0].code),new Error(t.error);"1"==a?(i.collectStatus=t.readdata._data.isFavorite,i.collectStatus?i.collectFlag="已收藏":i.collectFlag="收藏"):"2"==a?(i.essenceStatus=t.readdata._data.isEssence,i.essenceStatus?i.essenceFlag="取消加精":i.essenceFlag="加精"):"3"==a?(i.stickyStatus=t.readdata._data.isSticky,i.stickyStatus?i.stickyFlag="取消置顶":i.stickyFlag="置顶"):"4"==a&&(i.deletedStatus=t.readdata._data.isDeleted,i.deletedStatus&&(i.$toast.success("删除成功，跳转到首页"),i.$router.push({path:"/circle",name:"circle"})))}))},deleteOpear:function(t,e){var a=this,i=new Object;i.isDeleted=!0,this.appFetch({url:"posts",splice:"/"+t,method:"patch",data:{data:{type:"posts",attributes:i}}}).then((function(t){if(t.errors)throw a.$toast.fail(t.errors[0].code),new Error(t.error);a.$toast.success("删除成功"),a.pageIndex=1,a.postsList.splice(e,1)}))},replyOpera:function(t,e,a,i,s){var r=this;if(this.token){if(!this.clickStatus)return!1;this.clickStatus=!1;var n=new Object;if(2==e){if(!i)return this.$toast.fail("没有权限，请联系站点管理员"),!1;n.isLiked=!a}var o="posts/"+t;this.appFetch({url:o,method:"patch",data:{data:{type:"posts",attributes:n}}}).then((function(t){if(t.errors)throw r.$toast.fail(t.errors[0].code),new Error(t.error);a?(r.postsList[s]._data.likeCount=r.postsList[s]._data.likeCount-1,r.postsList[s]._data.isLiked=!1):(r.postsList[s]._data.likeCount=r.postsList[s]._data.likeCount+1,r.postsList[s]._data.isLiked=!0),r.pageIndex=1,r.clickStatus=!0}))}else this.$router.push({path:"/login-user",name:"login-user"})},footReplyOpera:function(t,e,a,i,s){var r=this;if(this.token){var n=new Object;if(3==e){if(!this.canLike)return this.$toast.fail("没有权限，请联系站点管理员"),!1;n.isLiked=!a}var o="posts/"+t;this.appFetch({url:o,method:"patch",data:{data:{type:"posts",attributes:n}}}).then((function(t){if(t.errors)throw r.$toast.fail(t.errors[0].code),new Error(t.error);a?(r.likedUsers.map((function(t,e,a){t._data.id===r.userId&&a.splice(e,1)})),r.likeLen=r.likeLen-1,r.userArr(r.likedUsers),r.themeCon.firstPost._data.isLiked=!1,r.themeIsLiked=!1):(r.likedUsers.unshift({_data:{username:r.currentUserName,id:r.userId}}),r.themeCon.firstPost._data.isLiked=!0,r.likeLen=r.likeLen+1,r.themeIsLiked=!0),r.pageIndex=1}))}else this.$router.push({path:"/login-user",name:"login-user"})},showRewardPopup:function(){this.token?this.userId==this.themeUserId?this.$toast.fail("不能打赏自己"):(this.rewardShow=!0,1!=this.isWeixin&&1!=this.isPhone&&this.rewardShow):this.$router.push({path:"/login-user",name:"login-user"})},replyToJump:function(t,e,a){this.token?this.canReply?(this.$router.replace({path:"/reply-to-topic/"+t+"/"+e,replace:!0}),n.default.setLItem("replyQuote",a)):this.$toast.fail("没有权限，请联系站点管理员"):this.$router.push({path:"/login-user",name:"login-user"})},onBridgeReady:function(t){var e=this;WeixinJSBridge.invoke("getBrandWCPayRequest",{appId:t.data.attributes.wechat_js.appId,timeStamp:t.data.attributes.wechat_js.timeStamp,nonceStr:t.data.attributes.wechat_js.nonceStr,package:t.data.attributes.wechat_js.package,signType:"MD5",paySign:t.data.attributes.wechat_js.paySign});var a=setInterval((function(){"1"==e.payStatus||e.payStatusNum>10?clearInterval(a):e.getOrderStatus()}),3e3)},payClick:function(t){this.amountNum=t,this.show=!this.show},payImmediatelyClick:function(t){var e=this;this.rewardShow=!1;var a=this.appCommonH.isWeixin().isWeixin,i=this.appCommonH.isWeixin().isPhone;"微信支付"===t.name&&(this.show=!1,a?this.getOrderSn(this.amountNum).then((function(){e.orderPay(12).then((function(t){"undefined"==typeof WeixinJSBridge?document.addEventListener?document.addEventListener("WeixinJSBridgeReady",e.onBridgeReady(t),!1):document.attachEvent&&(document.attachEvent("WeixinJSBridgeReady",e.onBridgeReady(t)),document.attachEvent("onWeixinJSBridgeReady",e.onBridgeReady(t))):e.onBridgeReady(t)}))})):i?this.getOrderSn(this.amountNum).then((function(){e.orderPay(11).then((function(t){e.wxPayHref=t.readdata._data.wechat_h5_link,window.location.href=e.wxPayHref;var a=setInterval((function(){e.payStatus&&e.payStatusNum>10?clearInterval(a):e.getOrderStatus()}),3e3)}))})):this.getOrderSn(this.amountNum).then((function(){e.orderPay(10).then((function(t){e.codeUrl=t.readdata._data.wechat_qrcode,e.qrcodeShow=!0;var a=setInterval((function(){e.payStatus&&e.payStatusNum>10?clearInterval(a):e.getOrderStatus()}),3e3)}))})))},onInput:function(t){var e=this;this.value=this.value+t,6===this.value.length&&(this.errorInfo="",this.getOrderSn(this.amountNum).then((function(){e.orderPay(20,e.value).then((function(t){var a=setInterval((function(){e.payStatus&&e.payStatusNum>10?clearInterval(a):e.getOrderStatus()}),3e3)}))})))},onDelete:function(){this.value=this.value.slice(0,this.value.length-1)},onClose:function(){this.value="",this.errorInfo="",this.payLoading=!1},getOrderSn:function(t){var e=this;return this.appFetch({url:"orderList",method:"post",data:{type:2,thread_id:this.themeId,amount:t}}).then((function(t){e.orderSn=t.readdata._data.order_sn}))},orderPay:function(t,e){var a=this;return this.appFetch({url:"orderPay",method:"post",splice:"/"+this.orderSn,data:{payment_type:t,pay_password:e}}).then((function(t){if(!t.errors)return a.payLoading=!0,t;a.value="",t.errors[0].detail?a.$toast.fail(t.errors[0].code+"\n"+t.errors[0].detail[0]):a.$toast.fail(t.errors[0].code)}))},getOrderStatus:function(){var t=this;return this.appFetch({url:"order",method:"get",splice:"/"+this.orderSn,data:{}}).then((function(e){if(e.errors){if(!e.errors[0].detail)throw t.$toast.fail(e.errors[0].code),new Error(e.error);t.$toast.fail(e.errors[0].code+"\n"+e.errors[0].detail[0])}else t.payStatus=e.readdata._data.status,t.payStatusNum++,("1"==t.payStatus||t.payStatusNum>10)&&(t.rewardShow=!1,t.qrcodeShow=!1,t.show=!1,"1"==t.payStatus&&(t.rewardedUsers.unshift({_data:{avatarUrl:t.currentUserAvatarUrl,id:t.userId}}),t.payLoading=!1,t.$toast.success("支付成功")),t.payStatusNum=11)}))},onLoad:function(){this.loading=!0,this.pageIndex++,this.detailsLoad()},onRefresh:function(){var t=this;this.pageIndex=1,this.detailsLoad(!0).then((function(e){t.$toast("刷新成功"),t.isLoading=!1,t.finished=!1})).catch((function(e){t.$toast("刷新失败"),t.isLoading=!1}))}},mounted:function(){document.addEventListener("click",this.listenEvt,!1)},destroyed:function(){document.removeEventListener("click",this.listenEvt,!1)},beforeRouteLeave:function(t,e,a){document.removeEventListener("click",this.listenEvt,!1),a()}}}}]);