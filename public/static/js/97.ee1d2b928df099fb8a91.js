(window.webpackJsonp=window.webpackJsonp||[]).push([[97],{"+EMZ":function(e,t,a){"use strict";a.r(t);var o=a("GmPK"),r=a.n(o);for(var n in o)"default"!==n&&function(e){a.d(t,e,(function(){return o[e]}))}(n);t.default=r.a},"9VeX":function(e,t,a){"use strict";a.d(t,"a",(function(){return o})),a.d(t,"b",(function(){return r}));var o=function(){var e=this,t=e.$createElement,a=e._self._c||t;return a("div",[a("Card",{attrs:{header:"对象存储配置"}}),e._v(" "),a("Card",{attrs:{header:"名称："}},[a("CardRow",{attrs:{description:"填写存储桶基本配置中的空间名称"}},[a("el-input",{attrs:{clearable:""},model:{value:e.cosName,callback:function(t){e.cosName=t},expression:"cosName"}})],1)],1),e._v(" "),a("Card",{attrs:{header:"地域："}},[a("CardRow",{attrs:{description:"填写存储桶基本配置中的所属地域，例如：ap-beijing"}},[a("el-input",{attrs:{clearable:""},model:{value:e.cosArea,callback:function(t){e.cosArea=t},expression:"cosArea"}})],1)],1),e._v(" "),a("Card",{attrs:{header:"数据万象处理域名："}},[a("CardRow",{attrs:{description:"填写存储桶基本配置中的访问域名"}},[a("el-input",{attrs:{clearable:""},model:{value:e.cosDomainName,callback:function(t){e.cosDomainName=t},expression:"cosDomainName"}})],1)],1),e._v(" "),a("Card",{staticClass:"footer-btn"},[a("el-button",{attrs:{type:"primary",size:"medium"},on:{click:e.submission}},[e._v("提交")])],1)],1)},r=[]},CtzB:function(e,t,a){"use strict";a.r(t);var o=a("9VeX"),r=a("+EMZ");for(var n in r)"default"!==n&&function(e){a.d(t,e,(function(){return r[e]}))}(n);var s=a("Yj/X"),c=Object(s.a)(r.default,o.a,o.b,!1,null,null,null);t.default=c.exports},GmPK:function(e,t,a){"use strict";Object.defineProperty(t,"__esModule",{value:!0});var o=n(a("bS4n")),r=n(a("YCy3"));function n(e){return e&&e.__esModule?e:{default:e}}t.default=(0,o.default)({name:"tencentCloudConfigCosView"},r.default)},YCy3:function(e,t,a){"use strict";Object.defineProperty(t,"__esModule",{value:!0});var o=n(a("4gYi")),r=n(a("pNQN"));function n(e){return e&&e.__esModule?e:{default:e}}t.default={data:function(){return{cosName:"",cosArea:"",cosDomainName:""}},methods:{submission:function(){var e=this;this.appFetch({url:"settings",method:"post",data:{data:[{attributes:{key:"qcloud_cos_bucket_name",value:this.cosName,tag:"qcloud"}},{attributes:{key:"qcloud_cos_bucket_area",value:this.cosArea,tag:"qcloud"}},{attributes:{key:"qcloud_ci_url",value:this.cosDomainName,tag:"qcloud"}}]}}).then((function(t){t.errors?e.$message.error(t.errors[0].code):e.$message({message:"提交成功",type:"success"})}))},getTencentCloudCon:function(){var e=this;this.appFetch({url:"forum",method:"get",data:{}}).then((function(t){t.errors?e.$message.error(t.errors[0].code):(e.cosName=t.readdata._data.qcloud.qcloud_cos_bucket_name,e.cosArea=t.readdata._data.qcloud.qcloud_cos_bucket_area,e.cosDomainName=t.readdata._data.qcloud.qcloud_ci_url)}))}},created:function(){this.getTencentCloudCon()},components:{Card:o.default,CardRow:r.default}}}}]);