(window.webpackJsonp=window.webpackJsonp||[]).push([[108],{"/V7O":function(e,r,t){"use strict";t.r(r);var c=t("GDf/"),a=t("yDFb");for(var d in a)"default"!==d&&function(e){t.d(r,e,(function(){return a[e]}))}(d);var i=t("KHd+"),o=Object(i.a)(a.default,c.a,c.b,!1,null,null,null);r.default=o.exports},"GDf/":function(e,r,t){"use strict";var c=function(){var e=this,r=e.$createElement,t=e._self._c||r;return t("div",{staticClass:"rol-permission-box"},[t("Card",{attrs:{header:"设置权限——"+e.$router.history.current.query.name}}),e._v(" "),t("Card",{attrs:{header:"前台操作权限："}}),e._v(" "),t("Card",[t("CardRow",{attrs:{description:"查看主题列表页的权限"}},[t("el-checkbox",{attrs:{label:"viewThreads",disabled:"1"===e.$router.history.current.query.id},model:{value:e.checked,callback:function(r){e.checked=r},expression:"checked"}},[e._v("查看主题列表")])],1)],1),e._v(" "),t("Card",[t("CardRow",{attrs:{description:"查看主题的详情页的权限"}},[t("el-checkbox",{attrs:{label:"thread.viewPosts",disabled:"1"===e.$router.history.current.query.id},model:{value:e.checked,callback:function(r){e.checked=r},expression:"checked"}},[e._v("查看主题详情")])],1)],1),e._v(" "),t("Card",[t("CardRow",{attrs:{description:"发布主题的权限"}},[t("el-checkbox",{attrs:{label:"createThread",disabled:"1"===e.$router.history.current.query.id||"7"===e.$router.history.current.query.id},model:{value:e.checked,callback:function(r){e.checked=r},expression:"checked"}},[e._v("发表帖子")])],1)],1),e._v(" "),t("Card",[t("CardRow",{attrs:{description:"回复主题的权限"}},[t("el-checkbox",{attrs:{label:"thread.reply",disabled:"1"===e.$router.history.current.query.id||"7"===e.$router.history.current.query.id},model:{value:e.checked,callback:function(r){e.checked=r},expression:"checked"}},[e._v("回复主题")])],1)],1),e._v(" "),t("Card",[t("CardRow",{attrs:{description:"发布主题时上传附件的权限"}},[t("el-checkbox",{attrs:{label:"attachment.create.0",disabled:"1"===e.$router.history.current.query.id||"7"===e.$router.history.current.query.id},model:{value:e.checked,callback:function(r){e.checked=r},expression:"checked"}},[e._v("上传附件")])],1)],1),e._v(" "),t("Card",[t("CardRow",{attrs:{description:"发布主题时上传图片的权限"}},[t("el-checkbox",{attrs:{label:"attachment.create.1",disabled:"1"===e.$router.history.current.query.id||"7"===e.$router.history.current.query.id},model:{value:e.checked,callback:function(r){e.checked=r},expression:"checked"}},[e._v("上传图片")])],1)],1),e._v(" "),t("Card",[t("CardRow",{attrs:{description:"查看站点成员列表、搜索成员的权限"}},[t("el-checkbox",{attrs:{label:"viewUserList",disabled:"1"===e.$router.history.current.query.id||"7"===e.$router.history.current.query.id},model:{value:e.checked,callback:function(r){e.checked=r},expression:"checked"}},[e._v("站点会员列表")])],1)],1),e._v(" "),t("Card",{attrs:{header:"前台管理权限："}}),e._v(" "),t("Card",[t("CardRow",{attrs:{description:"前台删除单个主题的权限"}},[t("el-checkbox",{attrs:{label:"thread.hide",disabled:"1"===e.$router.history.current.query.id||"7"===e.$router.history.current.query.id},model:{value:e.checked,callback:function(r){e.checked=r},expression:"checked"}},[e._v("删主题")])],1)],1),e._v(" "),t("Card",[t("CardRow",{attrs:{description:"前台删除单个回复的权限"}},[t("el-checkbox",{attrs:{label:"thread.hidePosts",disabled:"1"===e.$router.history.current.query.id||"7"===e.$router.history.current.query.id},model:{value:e.checked,callback:function(r){e.checked=r},expression:"checked"}},[e._v("删回复")])],1)],1),e._v(" "),t("Card",[t("CardRow",{attrs:{description:"前台置顶、取消置顶主题的权限"}},[t("el-checkbox",{attrs:{label:"thread.sticky",disabled:"1"===e.$router.history.current.query.id||"7"===e.$router.history.current.query.id},model:{value:e.checked,callback:function(r){e.checked=r},expression:"checked"}},[e._v("置顶")])],1)],1),e._v(" "),t("Card",[t("CardRow",{attrs:{description:"前台精华、取消精华主题的权限"}},[t("el-checkbox",{attrs:{label:"thread.essence",disabled:"1"===e.$router.history.current.query.id||"7"===e.$router.history.current.query.id},model:{value:e.checked,callback:function(r){e.checked=r},expression:"checked"}},[e._v("加精")])],1)],1),e._v(" "),t("Card",[t("CardRow",{attrs:{description:"前台单个主题的编辑权限"}},[t("el-checkbox",{attrs:{label:"thread.editPosts",disabled:"1"===e.$router.history.current.query.id||"7"===e.$router.history.current.query.id},model:{value:e.checked,callback:function(r){e.checked=r},expression:"checked"}},[e._v("编辑")])],1)],1),e._v(" "),t("Card",[t("CardRow",{attrs:{description:"前台批量管理主题的权限"}},[t("el-checkbox",{attrs:{label:"thread.batchEdit",disabled:"1"===e.$router.history.current.query.id||"7"===e.$router.history.current.query.id},model:{value:e.checked,callback:function(r){e.checked=r},expression:"checked"}},[e._v("批量管理主题")])],1)],1),e._v(" "),t("Card",[t("CardRow",{attrs:{description:"前台按用户组邀请成员的权限"}},[t("el-checkbox",{attrs:{label:"createInvite",disabled:"1"===e.$router.history.current.query.id||"7"===e.$router.history.current.query.id},model:{value:e.checked,callback:function(r){e.checked=r},expression:"checked"}},[e._v("管理-邀请加入")])],1)],1),e._v(" "),t("Card",[t("CardRow",{attrs:{description:"前台更改成员所属用户组的权限"}},[t("el-checkbox",{attrs:{label:"group.edit",disabled:"1"===e.$router.history.current.query.id||"7"===e.$router.history.current.query.id},model:{value:e.checked,callback:function(r){e.checked=r},expression:"checked"}},[e._v("编辑用户组")])],1)],1),e._v(" "),t("Card",[t("CardRow",{attrs:{description:"前台更改成员禁用状态的权限"}},[t("el-checkbox",{attrs:{label:"user.edit",disabled:"1"===e.$router.history.current.query.id||"7"===e.$router.history.current.query.id},model:{value:e.checked,callback:function(r){e.checked=r},expression:"checked"}},[e._v("编辑用户状态")])],1)],1),e._v(" "),t("Card",{attrs:{header:"默认权限："}}),e._v(" "),t("Card",[t("CardRow",{attrs:{description:""}},[t("p",{staticStyle:{"margin-left":"24PX"}},[e._v("站点信息")])])],1),e._v(" "),t("Card",[t("CardRow",{attrs:{description:""}},[t("p",{staticStyle:{"margin-left":"24PX"}},[e._v("主题点赞")])])],1),e._v(" "),t("Card",[t("CardRow",{attrs:{description:""}},[t("p",{staticStyle:{"margin-left":"24PX"}},[e._v("主题收藏")])])],1),e._v(" "),t("Card",[t("CardRow",{attrs:{description:""}},[t("p",{staticStyle:{"margin-left":"24PX"}},[e._v("主题打赏")])])],1),e._v(" "),t("Card",{staticClass:"footer-btn"},[t("el-button",{attrs:{type:"primary",size:"medium"},on:{click:e.submitClick}},[e._v("提交")])],1)],1)},a=[];t.d(r,"a",(function(){return c})),t.d(r,"b",(function(){return a}))},fsy8:function(e,r,t){"use strict";Object.defineProperty(r,"__esModule",{value:!0});var c=d(t("QbLZ"));t("I1+7");var a=d(t("zxK7"));function d(e){return e&&e.__esModule?e:{default:e}}r.default=(0,c.default)({name:"user-permission-view"},a.default)},yDFb:function(e,r,t){"use strict";t.r(r);var c=t("fsy8"),a=t.n(c);for(var d in c)"default"!==d&&function(e){t.d(r,e,(function(){return c[e]}))}(d);r.default=a.a},zxK7:function(e,r,t){"use strict";Object.defineProperty(r,"__esModule",{value:!0});var c=d(t("4gYi")),a=d(t("pNQN"));function d(e){return e&&e.__esModule?e:{default:e}}r.default={data:function(){return{checked:[]}},methods:{submitClick:function(){this.patchGroupPermission()},getGroupResource:function(){var e=this;this.appFetch({url:"groups",method:"get",splice:"/"+this.$route.query.id,data:{include:["permission"]}}).then((function(r){if(r.errors)e.$message.error(r.errors[0].code);else{var t=r.readdata.permission;e.checked=[],t.forEach((function(r){e.checked.push(r._data.permission)}))}})).catch((function(e){}))},patchGroupPermission:function(){var e=this;this.appFetch({url:"groupPermission",method:"post",data:{data:{attributes:{groupId:this.$route.query.id,permissions:this.checked}}}}).then((function(r){r.errors?e.$message.error(r.errors[0].code):e.$message({showClose:!0,message:"提交成功",type:"success"})})).catch((function(e){}))}},created:function(){this.getGroupResource()},components:{Card:c.default,CardRow:a.default}}}}]);