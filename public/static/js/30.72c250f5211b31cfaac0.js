(window.webpackJsonp=window.webpackJsonp||[]).push([[30,109],{"+1ub":function(t,e,n){"use strict";Object.defineProperty(e,"__esModule",{value:!0});e.autoTextarea=function(t,e,n,i){e=e||0;var s=!!document.getBoxObjectFor||"mozInnerScreenX"in window,o=!!window.opera&&!!window.opera.toString().indexOf("Opera"),a=function(e,n){t.addEventListener?t.addEventListener(e,n,!1):t.attachEvent("on"+e,n)},c=t.currentStyle?function(e){var n=t.currentStyle[e];if("height"===e&&1!==n.search(/px/i)){var i=t.getBoundingClientRect();return i.bottom-i.top-parseFloat(c("paddingTop"))-parseFloat(c("paddingBottom"))+"px"}return n}:function(e){return getComputedStyle(t,null)[e]},l=parseFloat(c("height"));t.style.resize="none";var r=function(){var a,r,d=0,u=t.style;t._length!==t.value.length&&(t._length=t.value.length,s||o||(d=parseInt(c("paddingTop"))+parseInt(c("paddingBottom"))),a=document.body.scrollTop||document.documentElement.scrollTop,t.style.height=l+"px",t.scrollHeight>l&&(n&&t.scrollHeight>n?(r=n-d,u.overflowY="auto"):(r=t.scrollHeight-d,u.overflowY="scroll"),u.height=r+e+"px",a+=parseInt(u.height)-t.currHeight,document.body.scrollTop=a,document.documentElement.scrollTop=a,t.currHeight=parseInt(u.height),i(parseInt(u.height))))};a("propertychange",r),a("input",r),a("focus",r),r()},e.debounce=function(t,e){var n=void 0;return function(){for(var i=this,s=arguments.length,o=Array(s),a=0;a<s;a++)o[a]=arguments[a];n&&clearTimeout(n),n=setTimeout((function(){t.apply(i,o)}),e||500)}}},"/ohI":function(t,e,n){"use strict";Object.defineProperty(e,"__esModule",{value:!0});var i,s=n("+1ub"),o=n("6NK7"),a=(i=o)&&i.__esModule?i:{default:i};n("Czht");var c=parseFloat(document.documentElement.style.fontSize);e.default={data:function(){return{headerTitle:"发布长文",selectSort:"选择分类",showPopup:!1,categories:[],categoriesId:[],cateId:"",content:"",showFacePanel:!1,keyboard:!1,keywordsMax:1e3,list:[],footMove:!1,payMove:!1,markMove:!1,faceData:[],fileList:[],fileListOne:[],uploadShow:!1,enclosureList:[],avatar:"",themeId:"",postsId:"",files:{name:"",type:""},headerImage:null,picValue:null,upImgUrl:"",enclosureShow:!1,isWeixin:!1,isPhone:!1,themeCon:!1,attriAttachment:!1,canUploadImages:"",canUploadAttachments:"",supportImgExt:"",supportImgExtRes:"",supportFileExt:"",supportFileArr:"",limitMaxLength:!0,limitMaxEncLength:!0,fileListOneLen:"",enclosureListLen:"",isiOS:!1,encuploadShow:!1,testingRes:!1,backGo:-2,formdataList:[],viewportWidth:"",themeTitle:"",payValue:"",paySetShow:!1,isCli:!1,moneyVal:"",timeout:null,paySetValue:"",titleMaxLength:80}},mounted:function(){var t=this;this.$nextTick((function(){var e=t.$refs.textarea;e.focus();var n=300;e&&(0,s.autoTextarea)(e,5,0,(function(t){if((t+=20)!==n){n=t}}))})),1!=this.isWeixin&&1!=this.isPhone&&this.limitWidth()},created:function(){this.viewportWidth=window.innerWidth,this.isWeixin=a.default.isWeixin().isWeixin,this.isPhone=a.default.isWeixin().isPhone;var t=navigator.userAgent;if(this.isAndroid=t.indexOf("Android")>-1||t.indexOf("Adr")>-1,this.isiOS=!!t.match(/\(i[^;]+;( U;)? CPU.+Mac OS X/),this.isiOS&&(this.encuploadShow=!0),this.$route.params.themeId){var e=this.$route.params.themeId,n=this.$route.params.postsId,i=this.$route.params.themeContent;this.themeId=e,this.postsId=n,this.content=i}this.loadCategories(),this.detailsLoad(),this.getInfo()},watch:{"fileListOne.length":function(t,e){this.fileListOneLen=t,this.fileListOneLen>=12?this.limitMaxLength=!1:this.limitMaxLength=!0},"enclosureList.length":function(t,e){this.enclosureListLen=t,this.enclosureListLen>=3?this.limitMaxEncLength=!1:this.limitMaxEncLength=!0},themeTitle:function(){this.themeTitle.length>this.titleMaxLength&&(this.themeTitle=String(this.themeTitle).slice(0,this.titleMaxLength))}},methods:{getInfo:function(){var t=this;this.appFetch({url:"forum",method:"get",data:{include:["users"]}}).then((function(e){if(e.errors)throw t.$toast.fail(e.errors[0].code),new Error(e.error);var n="";if(e.readdata._data.set_attach.support_img_ext){n=e.readdata._data.set_attach.support_img_ext.split(",");for(var i="",s="",o=0;o<n.length;o++)i="."+n[o]+",",s="image/"+n[o]+",",t.supportImgExt+=i,t.supportImgExtRes+=s}else n="*";var a="";if(e.readdata._data.set_attach.support_file_ext){a=e.readdata._data.set_attach.support_file_ext.split(",");var c="";for(o=0;o<a.length;o++)c="."+a[o]+",",t.supportFileExt+=c}else a="*";t.canUploadImages=e.readdata._data.other.can_upload_images,t.canUploadAttachments=e.readdata._data.other.can_upload_attachments}))},detailsLoad:function(){var t=this;if(this.postsId&&this.content){var e="threads/"+this.themeId;this.appFetch({url:e,method:"get",data:{include:["firstPost","firstPost.images","firstPost.attachments","category"]}}).then((function(e){if(e.errors)throw t.$toast.fail(e.errors[0].code),new Error(e.error);var n=e.readdata.category._data.id;t.selectSort=e.readdata.category._data.description,t.cateId!=n&&(t.cateId=n)}))}},publish:function(){var t=this;if(this.postsId&&this.content){var e="posts/"+this.postsId;this.appFetch({url:e,method:"patch",data:{data:{type:"posts",attributes:{is_long_article:!0,content:this.content}}}}).then((function(e){e.errors?e.errors[0].detail?t.$toast.fail(e.errors[0].code+"\n"+e.errors[0].detail[0]):t.$toast.fail(e.errors[0].code):t.$router.push({path:"details/"+t.themeId,query:{backGo:t.backGo}})}))}else{if(this.themeTitle.length<4)return this.$toast.fail("标题不得少于三个字符"),!1;if(this.content.length<1)return this.$toast.fail("内容不得为空"),!1;this.attriAttachment=this.fileListOne.concat(this.enclosureList);for(var n=0;n<this.attriAttachment.length;n++)this.attriAttachment[n]={type:"attachments",id:this.attriAttachment[n].id};this.appFetch({url:"threads",method:"post",data:{data:{type:"threads",attributes:{price:this.paySetValue,title:this.themeTitle,is_long_article:!0,content:this.content},relationships:{category:{data:{type:"categories",id:this.cateId}},attachments:{data:this.attriAttachment}}}}}).then((function(e){if(e.errors)e.errors[0].detail?t.$toast.fail(e.errors[0].code+"\n"+e.errors[0].detail[0]):t.$toast.fail(e.errors[0].code);else{var n=e.readdata._data.id;t.$router.push({path:"details/"+n,query:{backGo:t.backGo}})}}))}},limitWidth:function(){document.getElementById("post-topic-footer").style.width="640px";var t=window.innerWidth;document.getElementById("post-topic-footer").style.left=(t-640)/2+"px"},deleteEnclosure:function(t,e){this.fileListOne.length<1&&(this.uploadShow=!1),this.appFetch({url:"attachment",method:"delete",splice:"/"+t.id})},deleteEnc:function(t,e){var n=this;this.fileListOne.length<1&&(this.uploadShow=!1),this.appFetch({url:"attachment",method:"delete",splice:"/"+t.id}).then((function(e){var i=n.enclosureList.filter((function(e){return e.id!==t.id}));n.enclosureList=i}))},beforeHandleFile:function(){this.canUploadImages?this.limitMaxLength||this.$toast.fail("已达上传图片上限"):this.$toast.fail("没有上传图片的权限")},beforeHandleEnclosure:function(){this.canUploadAttachments?this.limitMaxEncLength||this.$toast.fail("已达上传附件上限"):this.$toast.fail("没有上传附件的权限")},handleFile:function(t){var e=this,n=[];void 0===t.length?n.push(t):n=t,this.limitMaxLength?n.map((function(t,i){e.isAndroid&&e.isWeixin?(e.testingType(t.file,e.supportImgExt),e.testingRes&&e.compressFile(t.file,15e4,!1,n.length-i)):e.compressFile(t.file,15e4,!1,n.length-i)})):this.$toast.fail("已达上传图片上限")},handleFileUp:function(t){for(var e=t.target.files.length+this.fileListOne.length<=12?t.target.files.length:12-this.fileListOne.length,n=0;n<e;n++){var i=t.target.files[n];this.isAndroid&&this.isWeixin?(this.testingType(i,this.supportImgExt),this.testingRes&&this.compressFile(i,15e4,!0)):this.compressFile(i,15e4,!0)}},handleEnclosure:function(t){if(this.testingType(t.target.files[0],this.supportFileExt),this.testingRes){var e=t.target.files[0],n=new FormData;n.append("file",e),n.append("isGallery",0),this.uploaderEnclosure(n,!1,!1,!0)}},testingType:function(t,e){var n=t.name.substring(t.name.lastIndexOf(".")).toLowerCase();"-1"==e.indexOf(n+",")?(this.$toast.fail("文件格式不正确!"),this.testingRes=!1):this.testingRes=!0},getAllEvens:function(t){},uploaderEnclosure:function(t,e,n,i,s){var o=this;this.appFetch({url:"attachment",method:"post",data:t}).then((function(t){if(t.errors)throw o.$toast.fail(t.errors[0].code),new Error(t.error);n&&(o.fileList.push({url:t.readdata._data.url,id:t.readdata._data.id}),o.fileListOne[o.fileListOne.length-s].id=t.data.attributes.id),e&&(o.fileListOne.push({url:t.readdata._data.url,id:t.readdata._data.id}),o.fileListOne.length>0&&(o.uploadShow=!0)),i&&(o.enclosureShow=!0,o.enclosureList.push({type:t.readdata._data.extension,name:t.readdata._data.fileName,id:t.readdata._data.id})),o.loading=!1}))},compressFile:function(t,e,n,i){var s=t.size||.8*t.length,o=(Math.max(e/s,.8),this);lrz(t,{quality:.8}).then((function(e){var s=new FormData;s.append("file",e.file,t.name),s.append("isGallery",1),o.uploaderEnclosure(s,n,!n,!1,i),o.loading=!1})).catch((function(t){})).always((function(){}))},clearKeywords:function(){this.keywords="",this.list=[];var t=this.$refs.textarea,e=40/c;t.style.height=e+"rem",e=60/c,t.focus()},searchChange:(0,s.debounce)((function(){if(this.keywords&&this.keywords.trim())this.keywords;else this.list=[]})),handleFaceChoose:function(t){var e=this.content,n=this.$refs.textarea,i=n.selectionStart,s=n.selectionEnd,o=e.substring(0,i)+t+e.substring(s,e.length);this.content=o,n.setSelectionRange&&setTimeout((function(){var e=i+t.length;n.setSelectionRange(e,e)}),0)},addExpression:function(){var t=this;this.keyboard=!this.keyboard,this.appFetch({url:"emojis",method:"get",data:{include:""}}).then((function(e){t.faceData=e.readdata})),this.showFacePanel=!this.showFacePanel,this.footMove=!this.footMove,this.payMove=!this.payMove,this.markMove=!this.markMove},backClick:function(){this.$router.go(-1)},dClick:function(){this.showPopup=!0},onConfirm:function(t,e){var n=t.id;this.cateId=n;t.text;this.showPopup=!1,this.selectSort=t.text},loadCategories:function(){var t=this;this.appFetch({url:"categories",method:"get",data:{include:""}}).then((function(e){if(e.errors)throw t.$toast.fail(e.errors[0].code),new Error(e.error);for(var n,i=0,s=(n=e.readdata).length;i<s;i++)t.categories.push({text:n[i]._data.name,id:n[i]._data.id}),t.categoriesId.push(n[i]._data.id)}))},onCancel:function(){this.showPopup=!1},paySetting:function(){this.paySetShow=!0},closePaySet:function(){this.paySetShow=!1,this.paySetValue=""},search:function(t){null!=t.target.value&&t.target.value>"0"?this.isCli=!0:this.isCli=!1},paySetSure:function(){this.paySetShow=!1,this.payValue=this.paySetValue+"元"}}}},"21oY":function(t,e,n){"use strict";var i=function(){var t=this,e=t.$createElement,n=t._self._c||e;return n("div",{staticClass:"post-topic-box"},[n("header",{staticClass:"post-topic-header"},[n("span",{staticClass:"icon iconfont icon-back post-topic-header-icon",on:{click:t.backClick}}),t._v(" "),n("h2",{staticClass:"postHeadTit"},[t._v(t._s(t.headerTitle))]),t._v(" "),n("van-button",{attrs:{type:"primary",size:"mini"},on:{click:t.publish}},[t._v("发布")])],1),t._v(" "),n("div",{staticClass:"post-longText-form"},[n("input",{directives:[{name:"model",rawName:"v-model",value:t.themeTitle,expression:"themeTitle"}],staticClass:"pubThemeTitle",attrs:{type:"text",placeholder:"请输入标题",autofocus:"autofocus"},domProps:{value:t.themeTitle},on:{input:function(e){e.target.composing||(t.themeTitle=e.target.value)}}}),t._v(" "),n("textarea",{directives:[{name:"model",rawName:"v-model",value:t.content,expression:"content"}],ref:"textarea",staticClass:"markdownText",attrs:{id:"textarea_id",name:"post-topic",placeholder:"请输入内容"},domProps:{value:t.content},on:{focus:function(e){t.showFacePanel=!1,t.footMove=!1,t.keyboard=!1},input:function(e){e.target.composing||(t.content=e.target.value)}}}),t._v(" "),t.isAndroid&&t.isWeixin?n("div",{staticClass:"uploadBox"},[t.uploadShow?n("div",{staticClass:"uploadBox"},[n("van-uploader",{attrs:{"max-count":12,"after-read":t.handleFile,multiple:""},on:{delete:function(e){return t.deleteEnclosure(e,"img")}},model:{value:t.fileListOne,callback:function(e){t.fileListOne=e},expression:"fileListOne"}})],1):t._e()]):n("div",{},[t.uploadShow?n("div",{staticClass:"uploadBox"},[n("van-uploader",{attrs:{"max-count":12,accept:t.supportImgExtRes,multiple:"false","after-read":t.handleFile},on:{delete:function(e){return t.deleteEnclosure(e,"img")}},model:{value:t.fileListOne,callback:function(e){t.fileListOne=e},expression:"fileListOne"}})],1):t._e()]),t._v(" "),t.enclosureShow?n("div",{staticClass:"enclosure"},t._l(t.enclosureList,(function(e,i){return n("div",{key:i,staticClass:"enclosureChi"},["rar"===e.type?n("span",{staticClass:"icon iconfont icon-rar"}):t._e(),t._v(" "),"zip"===e.type?n("span",{staticClass:"icon iconfont icon-rar"}):"docx"===e.type?n("span",{staticClass:"icon iconfont icon-word"}):"doc"===e.type?n("span",{staticClass:"icon iconfont icon-word"}):"pdf"===e.type?n("span",{staticClass:"icon iconfont icon-pdf"}):"jpg"===e.type?n("span",{staticClass:"icon iconfont icon-jpg"}):"mp"===e.type?n("span",{staticClass:"icon iconfont icon-mp3"}):"mp1"===e.type?n("span",{staticClass:"icon iconfont icon-mp4"}):"png"===e.type?n("span",{staticClass:"icon iconfont icon-PNG"}):"ppt"===e.type?n("span",{staticClass:"icon iconfont icon-ppt"}):"swf"===e.type?n("span",{staticClass:"icon iconfont icon-swf"}):"TIFF"===e.type?n("span",{staticClass:"icon iconfont icon-TIFF"}):"txt"===e.type?n("span",{staticClass:"icon iconfont icon-txt"}):"xls"===e.type?n("span",{staticClass:"icon iconfont icon-xls"}):n("span",{staticClass:"icon iconfont icon-doubt"}),t._v(" "),n("span",{staticClass:"encName"},[t._v(t._s(e.name))]),t._v(" "),n("van-icon",{staticClass:"encDelete",attrs:{name:"clear"},on:{click:function(n){return t.deleteEnc(e,"enclosure")}}})],1)})),0):t._e()]),t._v(" "),n("markdown-toolbar",{staticClass:"markdownBox markdownFix",class:{markMove:t.markMove},attrs:{for:"textarea_id"}},[n("md-bold",[n("span",{staticClass:"icon iconfont icon-bold"})]),t._v(" "),n("md-header",[n("span",{staticClass:"icon iconfont icon-title"})]),t._v(" "),n("md-italic",[n("span",{staticClass:"icon iconfont icon-italic"})]),t._v(" "),n("md-quote",[n("span",{staticClass:"icon iconfont icon-quote"})]),t._v(" "),n("md-code",[n("span",{staticClass:"icon iconfont icon-code"})]),t._v(" "),n("md-link",[n("span",{staticClass:"icon iconfont icon-link"})]),t._v(" "),n("md-unordered-list",[n("span",{staticClass:"icon iconfont icon-unordered-list"})]),t._v(" "),n("md-ordered-list",[n("span",{staticClass:"icon iconfont icon-ordered-list"})])],1),t._v(" "),n("van-cell",{staticClass:"paySetting",class:{payMove:t.payMove},attrs:{title:"付费设置","is-link":"",value:t.payValue},on:{click:t.paySetting}}),t._v(" "),n("footer",{staticClass:"post-topic-footer",class:{footMove:t.footMove},attrs:{id:"post-topic-footer"}},[n("div",{staticClass:"post-topic-footer-left",class:{width20:t.encuploadShow}},[n("span",{staticClass:"icon iconfont icon-label post-topic-header-icon",class:{"icon-keyboard":t.keyboard},on:{click:t.addExpression}}),t._v(" "),t.canUploadImages&&t.limitMaxLength?n("span",{staticClass:"icon iconfont icon-picture post-topic-header-icon uploadIcon"},[t.isAndroid&&t.isWeixin?n("input",{staticClass:"hiddenInput",attrs:{type:"file"},on:{change:t.handleFileUp}}):n("input",{staticClass:"hiddenInput",attrs:{type:"file",accept:t.supportImgExtRes,multiple:""},on:{change:t.handleFileUp}})]):n("span",{staticClass:"icon iconfont icon-picture post-topic-header-icon uploadIcon",on:{click:t.beforeHandleFile}}),t._v(" "),t.canUploadAttachments&&t.limitMaxEncLength?n("span",{staticClass:"icon iconfont icon-enclosure post-topic-header-icon uploadIcon",class:{hide:t.encuploadShow}},[n("input",{staticClass:"hiddenInput",attrs:{type:"file"},on:{change:t.handleEnclosure}})]):n("span",{staticClass:"icon iconfont icon-enclosure post-topic-header-icon uploadIcon",class:{hide:t.encuploadShow},on:{click:t.beforeHandleEnclosure}})]),t._v(" "),n("div",{staticClass:"post-topic-footer-right",on:{click:t.dClick}},[n("span",{staticClass:"post-topic-footer-right-sort"},[t._v(t._s(t.selectSort))]),t._v(" "),n("span",{staticClass:"icon iconfont icon-down-menu post-topic-header-icon",staticStyle:{color:"#888888"}})])]),t._v(" "),n("van-popup",{staticClass:"paySetShow",attrs:{"click-overlay":"closePaySet"},model:{value:t.paySetShow,callback:function(e){t.paySetShow=e},expression:"paySetShow"}},[n("div",{staticClass:"popTitBox"},[n("span",{staticClass:"popupTit"},[t._v("设置金额")]),t._v(" "),n("span",{staticClass:"icon iconfont icon-closeCho",on:{click:t.closePaySet}})]),t._v(" "),n("div",{staticClass:"payMoneyBox"},[n("span",[t._v("￥")]),t._v(" "),n("input",{directives:[{name:"model",rawName:"v-model",value:t.paySetValue,expression:"paySetValue"}],staticClass:"payMoneyInp",attrs:{type:"number",autofocus:"autofocus"},domProps:{value:t.paySetValue},on:{keyup:function(e){return!e.type.indexOf("key")&&t._k(e.keyCode,"enter",13,e.key,"Enter")?null:t.search(e)},input:[function(e){e.target.composing||(t.paySetValue=e.target.value)},function(e){return t.search(e)}]}})]),t._v(" "),n("a",{staticClass:"popSureBtn",class:{sureBtnCli:t.isCli,forbiddenCli:!t.isCli},attrs:{href:"javascript:;"},on:{click:function(e){t.isCli&&t.paySetSure()}}},[t._v("确定")])]),t._v(" "),t.showFacePanel?n("Expression",{staticClass:"expressionBox",style:{overflow:"hidden",width:t.isPhone||t.isWeixin?"100%":"640px",left:t.isPhone||t.isWeixin?"0":(t.viewportWidth-640)/2+"px"},attrs:{faceData:t.faceData,id:"showFacePanel"},on:{onFaceChoose:t.handleFaceChoose}}):t._e(),t._v(" "),n("div",{staticClass:"popup"},[n("van-popup",{style:{height:"50%"},attrs:{position:"bottom",round:""},model:{value:t.showPopup,callback:function(e){t.showPopup=e},expression:"showPopup"}},[n("van-picker",{attrs:{columns:t.categories,"show-toolbar":"",title:"选择分类"},on:{cancel:t.onCancel,confirm:t.onConfirm}})],1)],1)],1)},s=[];n.d(e,"a",(function(){return i})),n.d(e,"b",(function(){return s}))},"55Ud":function(t,e,n){"use strict";n.r(e);var i=n("21oY"),s=n("vgwd");for(var o in s)"default"!==o&&function(t){n.d(e,t,(function(){return s[t]}))}(o);var a=n("KHd+"),c=Object(a.a)(s.default,i.a,i.b,!1,null,null,null);e.default=c.exports},"6GI9":function(t,e,n){"use strict";Object.defineProperty(e,"__esModule",{value:!0}),e.default={data:function(){return{active:0,faceIndex:0}},props:{faceData:{type:Array}},computed:{faces:function(){for(var t=this.faceData,e=(this.faceIndex,t),n=0,i=[];28*n<e.length;)i.push(e.slice(28*n,28*(n+1))),n+=1;return i},scrollWidth:function(){return this.faces.length*document.body.clientWidth},scrollPosition:function(){return this.active*document.body.clientWidth}},mounted:function(){var t=this,e=this.$refs.faceContent,n=0,i=0;e.ontouchstart=function(t){n=t.targetTouches[0].pageX},e.ontouchend=function(e){(i=e.changedTouches[0].pageX)-n>50?0!==t.active&&t.active--:i-n<-50&&t.active!==t.faces.length-1&&t.active++}},created:function(){},methods:{getUrlCode:function(){var t=this;this.code=this.$utils.getUrlKey("code"),alert(code),this.appFetch({url:"weixin",method:"get",data:{code:this.code}}).then((function(t){alert(65756765)}),(function(e){100004==e.errors[0].status&&t.$router.go(-1)}))},loginWxClick:function(){this.$router.push({path:"/wx-login-bd"})},loginPhoneClick:function(){this.$router.push({path:"/login-phone"})},onFaceClick:function(t){this.$emit("onFaceChoose",t)}}}},Aves:function(t,e,n){"use strict";var i=function(){var t=this,e=t.$createElement,n=t._self._c||e;return n("div",{staticClass:"face-container"},[n("div",{staticClass:"scroll-wrapper"},[n("div",{ref:"faceContent",staticClass:"face-content",style:{width:t.scrollWidth+"px",marginLeft:-t.scrollPosition+"px"},on:{touchmove:function(t){t.preventDefault()}}},t._l(t.faces,(function(e,i){return n("div",{key:i,staticClass:"face-page"},t._l(e,(function(e,i){return n("a",{key:i},[n("img",{staticClass:"emoji",attrs:{src:e._data.url},on:{click:function(n){return t.onFaceClick(" "+e._data.code+" ")}}})])})),0)})),0),t._v(" "),n("div",{staticClass:"page-dot"},t._l(t.faces.length,(function(e){return n("div",{key:e,staticClass:"dot-item",class:e===t.active+1?"active":"",on:{click:function(n){t.active=e-1}}})})),0)])])},s=[];n.d(e,"a",(function(){return i})),n.d(e,"b",(function(){return s}))},Czht:function(t,e,n){"use strict";function i(t,e,n){return e in t?Object.defineProperty(t,e,{value:n,enumerable:!0,configurable:!0,writable:!0}):t[e]=n,t}n.r(e);const s=new WeakMap;class o extends HTMLElement{constructor(){super();const t=()=>{const t=s.get(this);t&&M(this,t)};var e;this.addEventListener("keydown",(e=t,function(t){" "!==t.key&&"Enter"!==t.key||(t.preventDefault(),e(t))})),this.addEventListener("click",t)}connectedCallback(){this.hasAttribute("tabindex")||this.setAttribute("tabindex","0"),this.hasAttribute("role")||this.setAttribute("role","button")}click(){const t=s.get(this);t&&M(this,t)}}class a extends o{constructor(){super();const t=parseInt(this.getAttribute("level")||3,10);if(t<1||t>6)return;const e="".concat("#".repeat(t)," ");s.set(this,{prefix:e})}}window.customElements.get("md-header")||(window.MarkdownHeaderButtonElement=a,window.customElements.define("md-header",a));class c extends o{constructor(){super(),s.set(this,{prefix:"**",suffix:"**",trimFirst:!0})}connectedCallback(){super.connectedCallback(),this.setAttribute("hotkey","b")}}window.customElements.get("md-bold")||(window.MarkdownBoldButtonElement=c,window.customElements.define("md-bold",c));class l extends o{constructor(){super(),s.set(this,{prefix:"_",suffix:"_",trimFirst:!0})}connectedCallback(){super.connectedCallback(),this.setAttribute("hotkey","i")}}window.customElements.get("md-italic")||(window.MarkdownItalicButtonElement=l,window.customElements.define("md-italic",l));class r extends o{constructor(){super(),s.set(this,{prefix:"> ",multiline:!0,surroundWithNewlines:!0})}}window.customElements.get("md-quote")||(window.MarkdownQuoteButtonElement=r,window.customElements.define("md-quote",r));class d extends o{constructor(){super(),s.set(this,{prefix:"`",suffix:"`",blockPrefix:"```",blockSuffix:"```"})}}window.customElements.get("md-code")||(window.MarkdownCodeButtonElement=d,window.customElements.define("md-code",d));class u extends o{constructor(){super(),s.set(this,{prefix:"[",suffix:"](url)",replaceNext:"url",scanFor:"https?://"})}connectedCallback(){super.connectedCallback(),this.setAttribute("hotkey","k")}}window.customElements.get("md-link")||(window.MarkdownLinkButtonElement=u,window.customElements.define("md-link",u));class h extends o{constructor(){super(),s.set(this,{prefix:"![",suffix:"](url)",replaceNext:"url",scanFor:"https?://"})}}window.customElements.get("md-image")||(window.MarkdownImageButtonElement=h,window.customElements.define("md-image",h));class p extends o{constructor(){super(),s.set(this,{prefix:"- ",multiline:!0,surroundWithNewlines:!0})}}window.customElements.get("md-unordered-list")||(window.MarkdownUnorderedListButtonElement=p,window.customElements.define("md-unordered-list",p));class f extends o{constructor(){super(),s.set(this,{prefix:"1. ",multiline:!0,orderedList:!0})}}window.customElements.get("md-ordered-list")||(window.MarkdownOrderedListButtonElement=f,window.customElements.define("md-ordered-list",f));class m extends o{constructor(){super(),s.set(this,{prefix:"- [ ] ",multiline:!0,surroundWithNewlines:!0})}connectedCallback(){super.connectedCallback(),this.setAttribute("hotkey","L")}}window.customElements.get("md-task-list")||(window.MarkdownTaskListButtonElement=m,window.customElements.define("md-task-list",m));class g extends o{constructor(){super(),s.set(this,{prefix:"@",prefixSpace:!0})}}window.customElements.get("md-mention")||(window.MarkdownMentionButtonElement=g,window.customElements.define("md-mention",g));class v extends o{constructor(){super(),s.set(this,{prefix:"#",prefixSpace:!0})}}window.customElements.get("md-ref")||(window.MarkdownRefButtonElement=v,window.customElements.define("md-ref",v));const w=navigator.userAgent.match(/Macintosh/)?"Meta":"Control";class x extends HTMLElement{constructor(){super()}connectedCallback(){const t=E.bind(null,this);this.field&&(this.field.addEventListener("keydown",t),y.set(this,t))}disconnectedCallback(){const t=y.get(this);t&&this.field&&(this.field.removeEventListener("keydown",t),y.delete(this))}get field(){const t=this.getAttribute("for");if(!t)return;const e=document.getElementById(t);return e instanceof HTMLTextAreaElement?e:null}}const y=new WeakMap;function E(t,e){if(e.metaKey&&"Meta"===w||e.ctrlKey&&"Control"===w){const n=t.querySelector('[hotkey="'.concat(e.key,'"]'));n&&(n.click(),e.preventDefault())}}function C(t){return t.trim().split("\n").length>1}function k(t,e){return Array(e+1).join(t)}function b(t,e,n){let i=e;const s=n?/\n/:/\s/;for(;t[i]&&!t[i].match(s);)i++;return i}window.customElements.get("markdown-toolbar")||(window.MarkdownToolbarElement=x,window.customElements.define("markdown-toolbar",x));let _=null;function S(t,e){const n=t.value.slice(t.selectionStart,t.selectionEnd);let i;i=e.orderedList?function(t){const e=/^\d+\.\s+/,n=t.selectionStart===t.selectionEnd;let i,s,o,a,c=t.value.slice(t.selectionStart,t.selectionEnd),l=c,r=c.split("\n");if(n){const e=t.value.slice(0,t.selectionStart).split(/\n/);o=t.selectionStart-e[e.length-1].length,a=b(t.value,t.selectionStart,!0),l=t.value.slice(o,a)}const d=l.split("\n");if(d.every(t=>e.test(t))){if(r=d.map(t=>t.replace(e,"")),c=r.join("\n"),n&&o&&a){const e=d[0].length-r[0].length;s=i=t.selectionStart-e,t.selectionStart=o,t.selectionEnd=a}}else{r=function(){let t,e,n;const i=[];for(n=t=0,e=r.length;t<e;n=++t){const t=r[n];i.push("".concat(n+1,". ").concat(t))}return i}(),c=r.join("\n");const{newlinesToAppend:e,newlinesToPrepend:o}=L(t);s=t.selectionStart+e.length,i=s+c.length,n&&(s=i),c=e+c+o}return{text:c,selectionStart:s,selectionEnd:i}}(t):e.multiline&&C(n)?function(t,e){const{prefix:n,suffix:i,surroundWithNewlines:s}=e;let o=t.value.slice(t.selectionStart,t.selectionEnd),a=t.selectionStart,c=t.selectionEnd;const l=o.split("\n");if(l.every(t=>t.startsWith(n)&&t.endsWith(i)))o=l.map(t=>t.slice(n.length,t.length-i.length)).join("\n"),c=a+o.length;else if(o=l.map(t=>n+t+i).join("\n"),s){const{newlinesToAppend:e,newlinesToPrepend:n}=L(t);a+=e.length,c=a+o.length,o=e+o+n}return{text:o,selectionStart:a,selectionEnd:c}}(t,e):function(t,e){let n,i;const{prefix:s,suffix:o,blockPrefix:a,blockSuffix:c,replaceNext:l,prefixSpace:r,scanFor:d,surroundWithNewlines:u}=e,h=t.selectionStart,p=t.selectionEnd;let f=t.value.slice(t.selectionStart,t.selectionEnd),m=C(f)&&a.length>0?"".concat(a,"\n"):s,g=C(f)&&c.length>0?"\n".concat(c):o;if(r){const e=t.value[t.selectionStart-1];0===t.selectionStart||null==e||e.match(/\s/)||(m=" ".concat(m))}f=function(t,e,n){let i=arguments.length>3&&void 0!==arguments[3]&&arguments[3];if(t.selectionStart===t.selectionEnd)t.selectionStart=function(t,e){let n=e;for(;t[n]&&null!=t[n-1]&&!t[n-1].match(/\s/);)n--;return n}(t.value,t.selectionStart),t.selectionEnd=b(t.value,t.selectionEnd,i);else{const i=t.selectionStart-e.length,s=t.selectionEnd+n.length,o=t.value.slice(i,t.selectionStart)===e,a=t.value.slice(t.selectionEnd,s)===n;o&&a&&(t.selectionStart=i,t.selectionEnd=s)}return t.value.slice(t.selectionStart,t.selectionEnd)}(t,m,g,e.multiline);let v=t.selectionStart,w=t.selectionEnd;const x=l.length>0&&g.indexOf(l)>-1&&f.length>0;if(u){const e=L(t);n=e.newlinesToAppend,i=e.newlinesToPrepend,m=n+s,g+=i}if(f.startsWith(m)&&f.endsWith(g)){const t=f.slice(m.length,f.length-g.length);if(h===p){let e=h-m.length;e=Math.max(e,v),e=Math.min(e,v+t.length),v=w=e}else w=v+t.length;return{text:t,selectionStart:v,selectionEnd:w}}if(x){if(d.length>0&&f.match(d)){g=g.replace(l,f);const t=m+g;return v=w=v+m.length,{text:t,selectionStart:v,selectionEnd:w}}{const t=m+f+g;return v=v+m.length+f.length+g.indexOf(l),w=v+l.length,{text:t,selectionStart:v,selectionEnd:w}}}{let t=m+f+g;v=h+m.length,w=p+m.length;const n=f.match(/^\s*|\s*$/g);if(e.trimFirst&&n){const e=n[0]||"",i=n[1]||"";t=e+m+f.trim()+g+i,v+=e.length,w-=i.length}return{text:t,selectionStart:v,selectionEnd:w}}}(t,e),function(t,e){let{text:n,selectionStart:i,selectionEnd:s}=e;const o=t.selectionStart,a=t.value.slice(0,o),c=t.value.slice(t.selectionEnd);if(null===_||!0===_){t.contentEditable="true";try{_=document.execCommand("insertText",!1,n)}catch(t){_=!1}t.contentEditable="false"}if(_&&!t.value.slice(0,t.selectionStart).endsWith(n)&&(_=!1),!_){try{document.execCommand("ms-beginUndoUnit")}catch(t){}t.value=a+n+c;try{document.execCommand("ms-endUndoUnit")}catch(t){}t.dispatchEvent(new CustomEvent("input",{bubbles:!0,cancelable:!0}))}null!=i&&null!=s?t.setSelectionRange(i,s):t.setSelectionRange(o,t.selectionEnd)}(t,i)}function L(t){const e=t.value.slice(0,t.selectionStart),n=t.value.slice(t.selectionEnd),i=e.match(/\n*$/),s=n.match(/^\n*/),o=i?i[0].length:0,a=s?s[0].length:0;let c,l;return e.match(/\S/)&&o<2&&(c=k("\n",2-o)),n.match(/\S/)&&a<2&&(l=k("\n",2-a)),null==c&&(c=""),null==l&&(l=""),{newlinesToAppend:c,newlinesToPrepend:l}}function M(t,e){const n=t.closest("markdown-toolbar");if(!(n instanceof x))return;const s=function(t){for(var e=1;e<arguments.length;e++){var n=null!=arguments[e]?arguments[e]:{},s=Object.keys(n);"function"==typeof Object.getOwnPropertySymbols&&(s=s.concat(Object.getOwnPropertySymbols(n).filter((function(t){return Object.getOwnPropertyDescriptor(n,t).enumerable})))),s.forEach((function(e){i(t,e,n[e])}))}return t}({},{prefix:"",suffix:"",blockPrefix:"",blockSuffix:"",multiline:!1,replaceNext:"",prefixSpace:!1,scanFor:"",surroundWithNewlines:!1,orderedList:!1,trimFirst:!1},e),o=n.field;o&&(o.focus(),S(o,s))}e.default=x},SDcr:function(t,e,n){"use strict";n.r(e);var i=n("Aves"),s=n("uwTP");for(var o in s)"default"!==o&&function(t){n.d(e,t,(function(){return s[t]}))}(o);var a=n("KHd+"),c=Object(a.a)(s.default,i.a,i.b,!1,null,null,null);e.default=c.exports},Vld1:function(t,e,n){"use strict";Object.defineProperty(e,"__esModule",{value:!0});var i=a(n("QbLZ")),s=a(n("/ohI")),o=(n("+1ub"),a(n("SDcr")));function a(t){return t&&t.__esModule?t:{default:t}}n("iUmJ"),n("N960"),e.default=(0,i.default)({name:"post-topic",components:{Expression:o.default}},s.default)},uwTP:function(t,e,n){"use strict";n.r(e);var i=n("yaIx"),s=n.n(i);for(var o in i)"default"!==o&&function(t){n.d(e,t,(function(){return i[t]}))}(o);e.default=s.a},vgwd:function(t,e,n){"use strict";n.r(e);var i=n("Vld1"),s=n.n(i);for(var o in i)"default"!==o&&function(t){n.d(e,t,(function(){return i[t]}))}(o);e.default=s.a},yaIx:function(t,e,n){"use strict";Object.defineProperty(e,"__esModule",{value:!0});var i=o(n("QbLZ")),s=o(n("6GI9"));function o(t){return t&&t.__esModule?t:{default:t}}e.default=(0,i.default)({name:"expressionView"},s.default)}}]);