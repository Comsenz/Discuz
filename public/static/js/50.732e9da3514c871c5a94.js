(window.webpackJsonp=window.webpackJsonp||[]).push([[50,110],{"+1ub":function(t,e,i){"use strict";Object.defineProperty(e,"__esModule",{value:!0});e.autoTextarea=function(t,e,i,n){e=e||0;var a=!!document.getBoxObjectFor||"mozInnerScreenX"in window,o=!!window.opera&&!!window.opera.toString().indexOf("Opera"),s=function(e,i){t.addEventListener?t.addEventListener(e,i,!1):t.attachEvent("on"+e,i)},r=t.currentStyle?function(e){var i=t.currentStyle[e];if("height"===e&&1!==i.search(/px/i)){var n=t.getBoundingClientRect();return n.bottom-n.top-parseFloat(r("paddingTop"))-parseFloat(r("paddingBottom"))+"px"}return i}:function(e){return getComputedStyle(t,null)[e]},l=parseFloat(r("height"));t.style.resize="none";var c=function(){var s,c,d=0,u=t.style;t._length!==t.value.length&&(t._length=t.value.length,a||o||(d=parseInt(r("paddingTop"))+parseInt(r("paddingBottom"))),s=document.body.scrollTop||document.documentElement.scrollTop,t.style.height=l+"px",t.scrollHeight>l&&(i&&t.scrollHeight>i?(c=i-d,u.overflowY="hidden"):(c=t.scrollHeight-d,u.overflowY="hidden"),u.height=c+e+"px",s+=parseInt(u.height)-t.currHeight,document.body.scrollTop=s,document.documentElement.scrollTop=s,t.currHeight=parseInt(u.height),n(parseInt(u.height))))};s("propertychange",c),s("input",c),s("focus",c),c()},e.debounce=function(t,e){var i=void 0;return function(){for(var n=this,a=arguments.length,o=Array(a),s=0;s<a;s++)o[s]=arguments[s];i&&clearTimeout(i),i=setTimeout((function(){t.apply(n,o)}),e||500)}}},"6GI9":function(t,e,i){"use strict";Object.defineProperty(e,"__esModule",{value:!0}),e.default={data:function(){return{active:0,faceIndex:0}},props:{faceData:{type:Array}},computed:{faces:function(){for(var t=this.faceData,e=(this.faceIndex,t),i=0,n=[];28*i<e.length;)n.push(e.slice(28*i,28*(i+1))),i+=1;return n},scrollWidth:function(){return this.faces.length*document.body.clientWidth},scrollPosition:function(){return this.active*document.body.clientWidth}},mounted:function(){var t=this,e=this.$refs.faceContent,i=0,n=0;e.ontouchstart=function(t){i=t.targetTouches[0].pageX},e.ontouchend=function(e){(n=e.changedTouches[0].pageX)-i>50?0!==t.active&&t.active--:n-i<-50&&t.active!==t.faces.length-1&&t.active++}},created:function(){},methods:{getUrlCode:function(){var t=this;this.code=this.$utils.getUrlKey("code"),alert(code),this.appFetch({url:"weixin",method:"get",data:{code:this.code}}).then((function(t){alert(65756765)}),(function(e){100004==e.errors[0].status&&t.$router.go(-1)}))},loginWxClick:function(){this.$router.push({path:"/wx-login-bd"})},loginPhoneClick:function(){this.$router.push({path:"/login-phone"})},onFaceClick:function(t){this.$emit("onFaceChoose",t)}}}},IFzr:function(t,e,i){"use strict";var n=function(){var t=this,e=t.$createElement,i=t._self._c||e;return i("div",{staticClass:"face-container"},[i("div",{staticClass:"scroll-wrapper"},[i("div",{ref:"faceContent",staticClass:"face-content",style:{width:t.scrollWidth+"px",marginLeft:-t.scrollPosition+"px"},on:{touchmove:function(t){t.preventDefault()}}},t._l(t.faces,(function(e,n){return i("div",{key:n,staticClass:"face-page"},t._l(e,(function(e,n){return i("a",{key:n},[i("img",{staticClass:"emoji",attrs:{src:e._data.url},on:{click:function(i){return t.onFaceClick(" "+e._data.code+" ")}}})])})),0)})),0),t._v(" "),i("div",{staticClass:"page-dot"},t._l(t.faces.length,(function(e){return i("div",{key:e,staticClass:"dot-item",class:e===t.active+1?"active":"",on:{click:function(i){t.active=e-1}}})})),0)])])},a=[];i.d(e,"a",(function(){return n})),i.d(e,"b",(function(){return a}))},N4Ng:function(t,e,i){"use strict";var n=function(){var t=this,e=t.$createElement,i=t._self._c||e;return i("div",{staticClass:"post-topic-box"},[i("header",{staticClass:"post-topic-header",style:{overflow:"hidden",width:t.isPhone||t.isWeixin?"100%":"640px",left:t.isPhone||t.isWeixin?"0":(t.viewportWidth-640)/2+"px"}},[i("span",{staticClass:"icon iconfont icon-back post-topic-header-icon",on:{click:t.backClick}}),t._v(" "),i("h2",{staticClass:"postHeadTit"},[t._v(t._s(t.headerTitle))]),t._v(" "),i("van-button",{attrs:{type:"primary",size:"mini"},on:{click:t.publish}},[t._v("发布")])],1),t._v(" "),i("div",{staticClass:"post-topic-form",attrs:{id:"postForm"}},[i("textarea",{directives:[{name:"model",rawName:"v-model",value:t.replyText,expression:"replyText"}],ref:"textarea",staticClass:"reply-box",attrs:{id:"post-topic-form-text",name:"post-topic",placeholder:"请输入内容",maxlength:t.keywordsMax},domProps:{value:t.replyText},on:{change:t.searchChange,focus:function(e){t.showFacePanel=!1,t.footMove=!1,t.keyboard=!1},input:function(e){e.target.composing||(t.replyText=e.target.value)}}}),t._v(" "),t.isAndroid&&t.isWeixin?i("div",{staticClass:"uploadBox"},[t.uploadShow?i("div",{staticClass:"uploadBox"},[i("van-uploader",{attrs:{"max-count":12,"after-read":t.handleFile,multiple:""},on:{delete:function(e){return t.deleteEnclosure(e,"img")}},model:{value:t.fileListOne,callback:function(e){t.fileListOne=e},expression:"fileListOne"}})],1):t._e()]):i("div",{},[t.uploadShow?i("div",{staticClass:"uploadBox"},[i("van-uploader",{attrs:{"max-count":12,accept:t.supportImgExtRes,multiple:"false","after-read":t.handleFile},on:{delete:function(e){return t.deleteEnclosure(e,"img")}},model:{value:t.fileListOne,callback:function(e){t.fileListOne=e},expression:"fileListOne"}})],1):t._e()])]),t._v(" "),i("footer",{staticClass:"post-topic-footer",class:{footMove:t.footMove},attrs:{id:"post-topic-footer"}},[i("div",{staticClass:"post-topic-footer-left reply-topic-footer-left"},[i("span",{staticClass:"icon iconfont icon-label post-topic-header-icon",class:{"icon-keyboard":t.keyboard},on:{click:t.addExpression}}),t._v(" "),t.canUploadImages&&t.limitMaxLength?i("span",{staticClass:"icon iconfont icon-picture post-topic-header-icon uploadIcon"},[t.isAndroid&&t.isWeixin?i("input",{staticClass:"hiddenInput",attrs:{type:"file"},on:{change:t.handleFileUp}}):i("input",{staticClass:"hiddenInput",attrs:{type:"file",accept:t.supportImgExtRes,multiple:""},on:{change:t.handleFileUp}})]):i("span",{staticClass:"icon iconfont icon-picture post-topic-header-icon uploadIcon",on:{click:t.beforeHandleFile}})])]),t._v(" "),t.showFacePanel?i("Expression",{staticClass:"expressionBox",style:{overflow:"hidden",width:t.isPhone||t.isWeixin?"100%":"640px",left:t.isPhone||t.isWeixin?"0":(t.viewportWidth-640)/2+"px"},attrs:{faceData:t.faceData,id:"showFacePanel"},on:{onFaceChoose:t.handleFaceChoose}}):t._e()],1)},a=[];i.d(e,"a",(function(){return n})),i.d(e,"b",(function(){return a}))},"QuT+":function(t,e,i){"use strict";i.r(e);var n=i("N4Ng"),a=i("iN0f");for(var o in a)"default"!==o&&function(t){i.d(e,t,(function(){return a[t]}))}(o);var s=i("KHd+"),r=Object(s.a)(a.default,n.a,n.b,!1,null,null,null);e.default=r.exports},SDcr:function(t,e,i){"use strict";i.r(e);var n=i("IFzr"),a=i("uwTP");for(var o in a)"default"!==o&&function(t){i.d(e,t,(function(){return a[t]}))}(o);var s=i("KHd+"),r=Object(s.a)(a.default,n.a,n.b,!1,null,null,null);e.default=r.exports},cIj4:function(t,e,i){"use strict";Object.defineProperty(e,"__esModule",{value:!0});var n,a=c(i("YEIV")),o=i("ULRk"),s=i("+1ub"),r=c(i("6NK7")),l=c(i("VVfg"));function c(t){return t&&t.__esModule?t:{default:t}}var d=parseFloat(document.documentElement.style.fontSize);e.default={data:function(){var t;return t={headerTitle:"回复主题",showFacePanel:!1,keyboard:!1,replyText:"",replyQuote:"",replyQuoteCont:"",keywordsMax:1e3,footMove:!1,faceData:[],fileList:[],uploadShow:!1,fileListOne:[],enclosureList:[],isWeixin:!1,isPhone:!1,supportImgExt:"",supportImgExtRes:"",limitMaxLength:!0},(0,a.default)(t,"fileListOne",[]),(0,a.default)(t,"fileListOneLen",""),(0,a.default)(t,"canUploadImages",""),(0,a.default)(t,"backGo",-3),(0,a.default)(t,"viewportWidth",""),(0,a.default)(t,"queryEdit",""),(0,a.default)(t,"canEdit",""),t},computed:{themeId:function(){return this.$route.params.themeId},replyId:function(){return this.$route.params.replyId}},created:function(){this.queryEdit=this.$route.query.edit,"reply"==this.queryEdit&&(this.replyDetailsLoad(),this.headerTitle="编辑回复"),this.isWeixin=r.default.isWeixin().isWeixin,this.isPhone=r.default.isWeixin().isPhone,this.viewportWidth=window.innerWidth;var t=navigator.userAgent;this.isAndroid=t.indexOf("Android")>-1||t.indexOf("Adr")>-1,this.isiOS=!!t.match(/\(i[^;]+;( U;)? CPU.+Mac OS X/),this.replyQuoteCont=l.default.getLItem("replyQuote"),this.replyQuote='<blockquote class="quoteCon">'+this.replyQuoteCont+"</blockquote>",this.getInfo()},mounted:function(){var t=this;this.$nextTick((function(){var e=t.$refs.textarea;e.focus();var i=300;e&&(0,s.autoTextarea)(e,5,65535,(function(t){if((t+=20)!==i){i=t}}))})),1!=this.isWeixin&&1!=this.isPhone&&this.limitWidth()},watch:{"fileListOne.length":function(t,e){this.fileListOneLen=t,this.fileListOneLen>=12?this.limitMaxLength=!1:this.limitMaxLength=!0},showFacePanel:function(t,e){this.showFacePanel=t,this.showFacePanel?document.getElementById("postForm").style.height=this.viewportHeight-240+"px":document.getElementById("postForm").style.height="100%"}},beforeDestroy:function(){o.Bus.$off("message")},methods:(n={getInfo:function(){var t=this;this.appFetch({url:"forum",method:"get",data:{include:["users"]}}).then((function(e){if(e.errors)throw t.$toast.fail(e.errors[0].code),new Error(e.error);for(var i=e.readdata._data.set_attach.support_img_ext.split(","),n="",a="",o=0;o<i.length;o++)n="."+i[o]+",",a="image/"+i[o]+",",t.supportImgExt+=n,t.supportImgExtRes+=a;t.canUploadImages=e.readdata._data.other.can_upload_images}))},limitWidth:function(){document.getElementById("post-topic-footer").style.width="640px";var t=window.innerWidth;document.getElementById("post-topic-footer").style.left=(t-640)/2+"px"},beforeHandleFile:function(){this.canUploadImages?this.limitMaxLength||this.$toast.fail("已达上传图片上限"):this.$toast.fail("没有上传图片的权限")},handleFile:function(t){var e=this,i=[];void 0===t.length?i.push(t):i=t,this.limitMaxLength?i.map((function(t,n){e.isAndroid&&e.isWeixin?(e.testingType(t.file,e.supportImgExt),e.testingRes&&e.compressFile(t.file,15e4,!1,i.length-n)):e.compressFile(t.file,15e4,!1,i.length-n)})):this.$toast.fail("已达上传图片上限")},handleFileUp:function(t){for(var e=t.target.files.length+this.fileListOne.length<=12?t.target.files.length:12-this.fileListOne.length,i=0;i<e;i++){var n=t.target.files[i];this.isAndroid&&this.isWeixin?(this.testingType(n,this.supportImgExt),this.testingRes&&this.compressFile(n,15e4,!0)):this.compressFile(n,15e4,!0)}},testingType:function(t,e){var i=t.name.substring(t.name.lastIndexOf(".")).toLowerCase();"-1"==e.indexOf(i+",")?(this.$toast.fail("文件格式不正确!"),this.testingRes=!1):this.testingRes=!0},deleteEnclosure:function(t,e){this.fileListOne.length<1&&(this.uploadShow=!1),this.appFetch({url:"attachment",method:"delete",splice:"/"+t.id})},uploaderEnclosure:function(t,e,i,n,a){var o=this;this.appFetch({url:"attachment",method:"post",data:t}).then((function(t){if(t.errors)throw o.$toast.fail(t.errors[0].code),new Error(t.error);i&&(o.fileList.push({url:t.readdata._data.url,id:t.readdata._data.id}),o.fileListOne[o.fileListOne.length-a].id=t.data.attributes.id),e&&(o.fileListOne.push({url:t.readdata._data.url,id:t.readdata._data.id}),o.fileListOne.length>0&&(o.uploadShow=!0))}))},compressFile:function(t,e,i,n){var a=t.size||.8*t.length,o=(Math.max(e/a,.8),this);lrz(t,{quality:.8}).then((function(e){var a=new FormData;a.append("file",e.file,t.name),a.append("isGallery",1),o.uploaderEnclosure(a,i,!i,!1,n),o.loading=!1})).catch((function(t){})).always((function(){}))},clearKeywords:function(){this.keywords="",this.list=[];var t=this.$refs.textarea,e=40/d;t.style.height=e+"rem",e=60/d,t.focus()},searchChange:(0,s.debounce)((function(){if(this.keywords&&this.keywords.trim())this.keywords;else this.list=[]})),handleFaceChoose:function(t){var e=this.replyText,i=this.$refs.textarea,n=i.selectionStart,a=i.selectionEnd,o=e.substring(0,n)+t+e.substring(a,e.length);this.replyText=o,i.setSelectionRange&&setTimeout((function(){var e=n+t.length;i.setSelectionRange(e,e)}),0)},addExpression:function(){var t=this;this.keyboard=!this.keyboard,this.appFetch({url:"emojis",method:"get",data:{include:""}}).then((function(e){if(e.errors)throw t.$toast.fail(e.errors[0].code),new Error(e.error);t.faceData=e.readdata})),this.showFacePanel=!this.showFacePanel,this.showFacePanel?document.getElementById("postForm").style.height=this.viewportHeight-240+"px":document.getElementById("postForm").style.height="100%",this.footMove=!this.footMove},backClick:function(){this.$router.go(-1)},dClick:function(){this.showPopup=!0},onConfirm:function(t,e){var i=t.id;this.cateId=i;t.text;this.showPopup=!1,this.selectSort=t.text},replyDetailsLoad:function(){var t=this;this.appFetch({url:"posts",method:"get",splice:"/"+this.replyId,data:{include:["user","images"]}}).then((function(e){if(e.errors)throw t.$toast.fail(e.errors[0].code),new Error(e.error);t.canEdit=e.readdata._data.canEdit,t.canEdit||(t.$toast.fail("您没有权限进行此操作"),t.$router.replace({path:"/"}));var i=e.readdata.images;t.replyText=e.readdata._data.content;for(var n=0;n<i.length;n++)t.fileListOne.push({url:i[n]._data.thumbUrl,id:i[n]._data.id});t.fileListOne.length>0&&(t.uploadShow=!0)}))},publish:function(){var t=this;if(""!=this.replyText&&null!=this.replyText){this.attriAttachment=this.fileListOne;for(var e=0;e<this.attriAttachment.length;e++)this.attriAttachment[e]={type:"attachments",id:this.attriAttachment[e].id};var i="",n="",a="";"reply"==this.queryEdit?(i="posts/"+this.replyId,n="patch",a={data:{attributes:{content:this.replyText},relationships:{attachments:{data:this.attriAttachment}}}}):(i="posts",n="post",a=this.replyId&&this.replyQuoteCont&&""!=this.replyText?{data:{type:"posts",attributes:{replyId:this.replyId,content:this.replyQuote+this.replyText},relationships:{thread:{data:{type:"threads",id:this.themeId}},attachments:{data:this.attriAttachment}}}}:{data:{type:"posts",attributes:{content:this.replyText},relationships:{thread:{data:{type:"threads",id:this.themeId}},attachments:{data:this.attriAttachment}}}}),this.appFetch({url:i,method:n,data:a}).then((function(e){e.errors?e.errors[0].detail?t.$toast.fail(e.errors[0].code+"\n"+e.errors[0].detail[0]):t.$toast.fail(e.errors[0].code):t.$router.replace({path:"/details/"+t.themeId,query:{backGo:t.backGo},replace:!0})}))}else this.$toast.fail("内容不能为空")}},(0,a.default)(n,"clearKeywords",(function(){this.keywords="",this.list=[];var t=this.$refs.textarea,e=40/d;t.style.height=e+"rem",e=60/d,t.focus()})),(0,a.default)(n,"backClick",(function(){this.$router.go(-1)})),n),destroyed:function(){l.default.removeLItem("replyQuote")}}},iN0f:function(t,e,i){"use strict";i.r(e);var n=i("xS81"),a=i.n(n);for(var o in n)"default"!==o&&function(t){i.d(e,t,(function(){return n[t]}))}(o);e.default=a.a},uwTP:function(t,e,i){"use strict";i.r(e);var n=i("yaIx"),a=i.n(n);for(var o in n)"default"!==o&&function(t){i.d(e,t,(function(){return n[t]}))}(o);e.default=a.a},xS81:function(t,e,i){"use strict";Object.defineProperty(e,"__esModule",{value:!0});var n=s(i("QbLZ")),a=s(i("SDcr")),o=s(i("cIj4"));function s(t){return t&&t.__esModule?t:{default:t}}i("iUmJ"),i("N960"),e.default=(0,n.default)({name:"reply-to-topic-view",components:{Expression:a.default}},o.default)},yaIx:function(t,e,i){"use strict";Object.defineProperty(e,"__esModule",{value:!0});var n=o(i("QbLZ")),a=o(i("6GI9"));function o(t){return t&&t.__esModule?t:{default:t}}e.default=(0,n.default)({name:"expressionView"},a.default)}}]);