(window.webpackJsonp=window.webpackJsonp||[]).push([[33,78],{"+1ub":function(t,e,a){"use strict";Object.defineProperty(e,"__esModule",{value:!0});e.autoTextarea=function(t,e,a,o){e=e||0;var n=!!document.getBoxObjectFor||"mozInnerScreenX"in window,i=!!window.opera&&!!window.opera.toString().indexOf("Opera"),s=function(e,a){t.addEventListener?t.addEventListener(e,a,!1):t.attachEvent("on"+e,a)},c=t.currentStyle?function(e){var a=t.currentStyle[e];if("height"===e&&1!==a.search(/px/i)){var o=t.getBoundingClientRect();return o.bottom-o.top-parseFloat(c("paddingTop"))-parseFloat(c("paddingBottom"))+"px"}return a}:function(e){return getComputedStyle(t,null)[e]},r=parseFloat(c("height"));t.style.resize="none";var l=function(){var s,l,u=0,d=t.style;t._length!==t.value.length&&(t._length=t.value.length,n||i||(u=parseInt(c("paddingTop"))+parseInt(c("paddingBottom"))),s=document.body.scrollTop||document.documentElement.scrollTop,t.style.height=r+"px",t.scrollHeight>r&&(a&&t.scrollHeight>a?(l=a-u,d.overflowY="auto"):(l=t.scrollHeight-u,d.overflowY="hidden"),d.height=l+e+"px",s+=parseInt(d.height)-t.currHeight,document.body.scrollTop=s,document.documentElement.scrollTop=s,t.currHeight=parseInt(d.height),o(parseInt(d.height))))};s("propertychange",l),s("input",l),s("focus",l),l()},e.debounce=function(t,e){var a=void 0;return function(){for(var o=this,n=arguments.length,i=Array(n),s=0;s<n;s++)i[s]=arguments[s];a&&clearTimeout(a),a=setTimeout((function(){t.apply(o,i)}),e||500)}}},"1m/t":function(t,e,a){"use strict";a.r(e);var o=a("9jAL"),n=a.n(o);for(var i in o)"default"!==i&&function(t){a.d(e,t,(function(){return o[t]}))}(i);e.default=n.a},"1nxv":function(t,e,a){"use strict";Object.defineProperty(e,"__esModule",{value:!0});var o=c(a("14Xm")),n=c(a("D3Ub")),i=a("+1ub"),s=(c(a("edxw")),c(a("UgcE")),c(a("6NK7")));function c(t){return t&&t.__esModule?t:{default:t}}var r=parseFloat(document.documentElement.style.fontSize);e.default={data:function(){return{headerTitle:"发布主题",selectSort:"选择分类",showPopup:!1,categories:[],categoriesId:[],cateId:"",content:"",showFacePanel:!1,keyboard:!1,keywordsMax:1e3,list:[],footMove:!1,faceData:[],fileList:[],uploadShow:!1,enclosureList:[],avatar:"",themeId:"",postsId:"",files:{name:"",type:""},headerImage:null,picValue:null,upImgUrl:"",enclosureShow:!1,isWeixin:!1,isPhone:!1,themeCon:!1,attriAttachment:!1}},mounted:function(){var t=this;this.$nextTick((function(){var e=t.$refs.textarea;e.focus();var a=300;e&&(0,i.autoTextarea)(e,5,0,(function(t){if((t+=20)!==a){a=t}}))})),1!=this.isWeixin&&1!=this.isPhone&&this.limitWidth()},created:function(){if(this.isWeixin=s.default.isWeixin().isWeixin,this.isPhone=s.default.isWeixin().isPhone,this.$route.params.themeId){var t=this.$route.params.themeId,e=this.$route.params.postsId,a=this.$route.params.themeContent;this.themeId=t,this.postsId=e,this.content=a}this.loadCategories(),this.detailsLoad()},watch:{},methods:{detailsLoad:function(){var t=this;if(this.postsId&&this.content){var e="threads/"+this.themeId;this.appFetch({url:e,method:"get",data:{include:["firstPost","firstPost.images","firstPost.attachments","category"]}}).then((function(e){console.log(e),console.log("1234"),console.log(t.cateId);var a=e.readdata.category._data.id;t.selectSort=e.readdata.category._data.description,console.log(t.selectSort),t.cateId!=a&&(t.cateId=a)}))}},publish:function(){var t=this;if(this.postsId&&this.content){console.log("回复");var e="posts/"+this.postsId;this.appFetch({url:e,method:"patch",data:{data:{type:"posts",attributes:{content:this.content}}}}).then((function(e){t.apiStore.pushPayload(e);t.$router.push({path:"details/"+t.themeId})}))}else console.log("发帖"),this.appFetch({url:"threads",method:"post",data:{data:{type:"threads",attributes:{content:this.content},relationships:{category:{data:{type:"categories",id:this.cateId}},attachments:{data:this.attriAttachment}}}}}).then((function(e){var a=e.readdata._data.id;t.$router.push({path:"details/"+a})}))},limitWidth:function(){document.getElementById("post-topic-footer").style.width="640px";var t=window.innerWidth;document.getElementById("post-topic-footer").style.marginLeft=(t-640)/2+"px"},handleFile:function(t){var e=new FormData;e.append("file",t.file),e.append("isGallery",1),this.uploaderEnclosure(e)},handleFileUp:function(t){var e=t.target.files[0],a=new FormData;a.append("file",e),a.append("isGallery",1),this.uploaderEnclosure(a,!0),this.uploadShow=!0},deleteEnclosure:function(t,e){var a=this;this.fileList.length<=1&&(this.uploadShow=!1),this.appFetch({url:"attachment",method:"delete",splice:"/"+t}).then((function(o){if("img"==e){var n=a.fileList.filter((function(e){return e.uuid!==t}));a.fileList=n}else{n=a.enclosureList.filter((function(e){return e.uuid!==t}));a.enclosureList=n;for(var i=new Array,s=0;s<a.enclosureList.length;s++){(o={type:"attachments"}).id=a.enclosureList[s].id,i.push(o)}a.attriAttachment=i}a.$message("删除成功")}))},handleEnclosure:function(t){var e=t.target.files[0],a=new FormData;a.append("file",e),a.append("isGallery",0),this.uploaderEnclosure(a,!1,!0)},onRead:function(t){var e=this;return(0,n.default)(o.default.mark((function a(){return o.default.wrap((function(a){for(;;)switch(a.prev=a.next){case 0:e.files.name=t.file.name,e.files.type=t.file.type,e.picValue=t.file,e.imgPreview(e.picValue);case 4:case"end":return a.stop()}}),a,e)})))()},imgPreview:function(t){var e=this;if(t&&window.FileReader&&/^image/.test(t.type)){var a=new FileReader;a.readAsDataURL(t),a.onloadend=function(){var t=this.result,a=new Image;a.src=t,this.result.length<=512e3?(e.headerImage=this.result,e.uploaderEnclosure()):a.onload=function(){var t=e.compress(a,void 0);e.headerImage=t,e.uploaderEnclosure()}}}},compress:function(t,e){var a=document.createElement("canvas"),o=a.getContext("2d"),n=document.createElement("canvas"),i=n.getContext("2d"),s=t.width,c=t.height,r=void 0;(r=s*c/4e6)>1?(s/=r=Math.sqrt(r),c/=r):r=1,a.width=s,a.height=c,o.fillStyle="#fff",o.fillRect(0,0,a.width,a.height);var l=void 0;if((l=s*c/1e6)>1){var u=~~(s/(l=~~(Math.sqrt(l)+1))),d=~~(c/l);n.width=u,n.height=d;for(var h=0;h<l;h++)for(var p=0;p<l;p++)i.drawImage(t,h*u*r,p*d*r,u*r,d*r,0,0,u,d),o.drawImage(n,h*u,p*d,u,d)}else o.drawImage(t,0,0,s,c);if(""!=e&&1!=e)switch(e){case 6:this.rotateImg(t,"left",a);break;case 8:this.rotateImg(t,"right",a);break;case 3:this.rotateImg(t,"right",a),this.rotateImg(t,"right",a)}var f=a.toDataURL("image/jpeg",.1);return n.width=n.height=a.width=a.height=0,f},rotateImg:function(t,e,a){if(null!=t){var o=t.height,n=t.width,i=2;null==i&&(i=0),"right"==e?++i>3&&(i=0):--i<0&&(i=3);var s=90*i*Math.PI/180,c=a.getContext("2d");switch(i){case 0:a.width=n,a.height=o,c.drawImage(t,0,0);break;case 1:a.width=o,a.height=n,c.rotate(s),c.drawImage(t,0,-o);break;case 2:a.width=n,a.height=o,c.rotate(s),c.drawImage(t,-n,-o);break;case 3:a.width=o,a.height=n,c.rotate(s),c.drawImage(t,-n,0)}}},dataURLtoFile:function(t){for(var e=t.split(","),a=atob(e[1]),o=a.length,n=new Uint8Array(o);o--;)n[o]=a.charCodeAt(o);return new File([n],this.files.name,{type:this.files.type})},uploaderEnclosure:function(t,e,a){var o=this;console.log(t,e,a),this.appFetch({url:"attachment",method:"post",data:t}).then((function(t){if(console.log(t),e&&(console.log("图片"),o.fileList.push({url:"https://2020.comsenz-service.com/"+t.readdata._data.fileName,uuid:t.readdata._data.uuid,id:t.readdata._data.id}),console.log(o.fileList),console.log("333")),a){console.log("fujian"),o.enclosureShow=!0,o.enclosureList.push({type:t.readdata._data.extension,name:t.readdata._data.fileName,uuid:t.readdata._data.uuid,id:t.readdata._data.id});var n=new Array;console.log(o.enclosureList);for(var i=0;i<o.enclosureList.length;i++){(t={type:"attachments"}).id=o.enclosureList[i].id,console.log(t),console.log("1111"),n.push(t)}o.attriAttachment=n}o.$message("提交成功")}))},clearKeywords:function(){this.keywords="",this.list=[];var t=this.$refs.textarea,e=40/r;t.style.height=e+"rem",e=60/r,t.focus()},searchChange:(0,i.debounce)((function(){if(this.keywords&&this.keywords.trim())this.keywords;else this.list=[]})),handleFaceChoose:function(t){var e=this.content,a=this.$refs.textarea,o=a.selectionStart,n=a.selectionEnd,i=e.substring(0,o)+t+e.substring(n,e.length);this.content=i,a.setSelectionRange&&setTimeout((function(){var e=o+t.length;a.setSelectionRange(e,e)}),0)},addExpression:function(){var t=this;this.keyboard=!this.keyboard,this.appFetch({url:"emojis",method:"get",data:{include:""}}).then((function(e){t.faceData=e.readdata})),this.showFacePanel=!this.showFacePanel,this.footMove=!this.footMove},backClick:function(){this.$router.go(-1)},dClick:function(){this.showPopup=!0},onConfirm:function(t,e){console.log(t);var a=t.id;this.cateId=a,console.log(this.cateId);t.text;this.showPopup=!1,this.selectSort=t.text},loadCategories:function(){var t=this;this.appFetch({url:"categories",method:"get",data:{include:""}}).then((function(e){console.log(e,"res1111");var a;a=e.readdata,console.log(e.readdata);for(var o=0,n=a.length;o<n;o++)t.categories.push({text:a[o]._data.name,id:a[o]._data.id}),t.categoriesId.push(a[o]._data.id)}))},onCancel:function(){this.showPopup=!1}}}},"6GI9":function(t,e,a){"use strict";Object.defineProperty(e,"__esModule",{value:!0});var o,n,i=a("YEIV"),s=(o=i)&&o.__esModule?o:{default:o};e.default=(n={data:function(){return{active:0,faceIndex:0}},props:{faceData:{type:Array}},created:function(){},computed:{faces:function(){for(var t=this.faceData,e=(this.faceIndex,t),a=0,o=[];28*a<e.length;)o.push(e.slice(28*a,28*(a+1))),a+=1;return o},scrollWidth:function(){return this.faces.length*document.body.clientWidth},scrollPosition:function(){return this.active*document.body.clientWidth}},mounted:function(){var t=this,e=this.$refs.faceContent,a=0,o=0;e.ontouchstart=function(t){a=t.targetTouches[0].pageX},e.ontouchend=function(e){(o=e.changedTouches[0].pageX)-a>50?0!==t.active&&t.active--:o-a<-50&&t.active!==t.faces.length-1&&t.active++}}},(0,s.default)(n,"created",(function(){})),(0,s.default)(n,"methods",{getUrlCode:function(){var t=this;this.code=this.$utils.getUrlKey("code"),alert(code),this.appFetch({url:"weixin",method:"get",data:{code:this.code}}).then((function(t){alert(65756765)}),(function(e){100004==e.errors[0].status&&t.$router.go(-1)}))},loginWxClick:function(){this.$router.push({path:"/wx-login-bd"})},loginPhoneClick:function(){this.$router.push({path:"/login-phone"})},onFaceClick:function(t){this.$emit("onFaceChoose",t)}}),n)},"9jAL":function(t,e,a){"use strict";Object.defineProperty(e,"__esModule",{value:!0});var o=s(a("QbLZ"));a("E2jd");var n=s(a("1nxv")),i=(a("+1ub"),s(a("SDcr")));function s(t){return t&&t.__esModule?t:{default:t}}e.default=(0,o.default)({name:"post-topic",components:{Expression:i.default}},n.default)},FI70:function(t,e,a){"use strict";a.r(e);var o=a("b7yE"),n=a("1m/t");for(var i in n)"default"!==i&&function(t){a.d(e,t,(function(){return n[t]}))}(i);var s=a("KHd+"),c=Object(s.a)(n.default,o.a,o.b,!1,null,null,null);e.default=c.exports},SDcr:function(t,e,a){"use strict";a.r(e);var o=a("j2JY"),n=a("uwTP");for(var i in n)"default"!==i&&function(t){a.d(e,t,(function(){return n[t]}))}(i);var s=a("KHd+"),c=Object(s.a)(n.default,o.a,o.b,!1,null,null,null);e.default=c.exports},b7yE:function(t,e,a){"use strict";var o=function(){var t=this,e=t.$createElement,a=t._self._c||e;return a("div",{staticClass:"post-topic-box"},[a("header",{staticClass:"post-topic-header"},[a("span",{staticClass:"icon iconfont icon-back post-topic-header-icon",on:{click:t.backClick}}),t._v(" "),a("h2",{staticClass:"postHeadTit"},[t._v(t._s(t.headerTitle))]),t._v(" "),a("van-button",{attrs:{type:"primary",size:"mini"},on:{click:t.publish}},[t._v("发布")])],1),t._v(" "),a("div",{staticClass:"post-topic-form"},[a("textarea",{directives:[{name:"model",rawName:"v-model",value:t.content,expression:"content"}],ref:"textarea",staticClass:"reply-box",attrs:{id:"post-topic-form-text",name:"post-topic",placeholder:"请输入内容",maxlength:t.keywordsMax},domProps:{value:t.content},on:{change:t.searchChange,focus:function(e){t.showFacePanel=!1,t.footMove=!1,t.keyboard=!1},input:function(e){e.target.composing||(t.content=e.target.value)}}}),t._v(" "),t._l(t.fileList,(function(e,o){return t.uploadShow?a("div",{key:o,staticClass:"uploadBox"},[a("van-uploader",{attrs:{"max-count":12,"after-read":t.handleFile,accept:"image/*"},model:{value:t.fileList,callback:function(e){t.fileList=e},expression:"fileList"}})],1):t._e()})),t._v(" "),t.enclosureShow?a("div",{staticClass:"enclosure"},t._l(t.enclosureList,(function(e,o){return a("div",{key:o,staticClass:"enclosureChi",model:{value:t.enclosureList,callback:function(e){t.enclosureList=e},expression:"enclosureList"}},["rar"===e.type?a("span",{staticClass:"icon iconfont icon-rar"}):"word"===e.type?a("span",{staticClass:"icon iconfont icon-word"}):"pdf"===e.type?a("span",{staticClass:"icon iconfont icon-pdf"}):"jpg"===e.type?a("span",{staticClass:"icon iconfont icon-jpg"}):"mp"===e.type?a("span",{staticClass:"icon iconfont icon-mp3"}):"mp1"===e.type?a("span",{staticClass:"icon iconfont icon-mp4"}):"png"===e.type?a("span",{staticClass:"icon iconfont icon-PNG"}):"ppt"===e.type?a("span",{staticClass:"icon iconfont icon-ppt"}):"swf"===e.type?a("span",{staticClass:"icon iconfont icon-swf"}):"TIFF"===e.type?a("span",{staticClass:"icon iconfont icon-TIFF"}):"txt"===e.type?a("span",{staticClass:"icon iconfont icon-txt"}):"xls"===e.type?a("span",{staticClass:"icon iconfont icon-xls"}):a("span",{staticClass:"icon iconfont icon-doubt"}),t._v(" "),a("span",{staticClass:"encName"},[t._v(t._s(e.name))]),t._v(" "),a("span",{staticClass:"encDelete",on:{click:function(a){return t.deleteEnclosure(e.uuid,"enclosure")}}},[t._v("X")])])})),0):t._e()],2),t._v(" "),a("footer",{staticClass:"post-topic-footer",class:{footMove:t.footMove},attrs:{id:"post-topic-footer"}},[a("div",{staticClass:"post-topic-footer-left"},[a("span",{staticClass:"icon iconfont icon-label post-topic-header-icon",class:{"icon-keyboard":t.keyboard},on:{click:t.addExpression}}),t._v(" "),a("span",{staticClass:"icon iconfont icon-picture post-topic-header-icon uploadIcon"},[a("input",{staticClass:"hiddenInput",attrs:{type:"file",accept:"image/*"},on:{change:t.handleFileUp}})]),t._v(" "),a("span",{staticClass:"icon iconfont icon-enclosure post-topic-header-icon uploadIcon"},[a("input",{staticClass:"hiddenInput",attrs:{type:"file",accept:"image/*"},on:{change:t.handleEnclosure}})])]),t._v(" "),a("div",{staticClass:"post-topic-footer-right",on:{click:t.dClick}},[a("span",{staticClass:"post-topic-footer-right-sort"},[t._v(t._s(t.selectSort))]),t._v(" "),a("span",{staticClass:"icon iconfont icon-down-menu post-topic-header-icon",staticStyle:{color:"#888888"}})])]),t._v(" "),t.showFacePanel?a("Expression",{staticClass:"expressionBox",attrs:{faceData:t.faceData},on:{onFaceChoose:t.handleFaceChoose}}):t._e(),t._v(" "),a("div",{staticClass:"popup"},[a("van-popup",{style:{height:"50%"},attrs:{position:"bottom",round:""},model:{value:t.showPopup,callback:function(e){t.showPopup=e},expression:"showPopup"}},[a("van-picker",{attrs:{columns:t.categories,"show-toolbar":"",title:"选择分类"},on:{cancel:t.onCancel,confirm:t.onConfirm}})],1)],1)],1)},n=[];a.d(e,"a",(function(){return o})),a.d(e,"b",(function(){return n}))},j2JY:function(t,e,a){"use strict";var o=function(){var t=this,e=t.$createElement,a=t._self._c||e;return a("div",{staticClass:"face-container"},[a("div",{staticClass:"scroll-wrapper"},[a("div",{ref:"faceContent",staticClass:"face-content",style:{width:t.scrollWidth+"px",marginLeft:-t.scrollPosition+"px"},on:{touchmove:function(t){t.preventDefault()}}},t._l(t.faces,(function(e,o){return a("div",{key:o,staticClass:"face-page"},t._l(e,(function(e,o){return a("a",{key:o},[a("img",{staticClass:"emoji",attrs:{src:e._data.url},on:{click:function(a){return t.onFaceClick(" "+e._data.code+" ")}}})])})),0)})),0),t._v(" "),a("div",{staticClass:"page-dot"},t._l(t.faces.length,(function(e){return a("div",{key:e,staticClass:"dot-item",class:e===t.active+1?"active":"",on:{click:function(a){t.active=e-1}}})})),0)])])},n=[];a.d(e,"a",(function(){return o})),a.d(e,"b",(function(){return n}))},uwTP:function(t,e,a){"use strict";a.r(e);var o=a("yaIx"),n=a.n(o);for(var i in o)"default"!==i&&function(t){a.d(e,t,(function(){return o[t]}))}(i);e.default=n.a},yaIx:function(t,e,a){"use strict";Object.defineProperty(e,"__esModule",{value:!0});var o=i(a("QbLZ")),n=i(a("6GI9"));function i(t){return t&&t.__esModule?t:{default:t}}a("E2jd"),e.default=(0,o.default)({name:"expressionView"},n.default)}}]);