(window.webpackJsonp=window.webpackJsonp||[]).push([[115],{Ibkn:function(t,s,i){"use strict";i.r(s);var e=i("Qlml"),a=i("SyFs");for(var o in a)"default"!==o&&function(t){i.d(s,t,(function(){return a[t]}))}(o);var n=i("KHd+"),r=Object(n.a)(a.default,e.a,e.b,!1,null,null,null);s.default=r.exports},Qlml:function(t,s,i){"use strict";var e=function(){var t=this,s=t.$createElement,i=t._self._c||s;return i("div",{staticClass:"circleCon"},[i("comHeader",{attrs:{title:"站点信息"}}),t._v(" "),i("van-pull-refresh",{on:{refresh:t.onRefresh},model:{value:t.isLoading,callback:function(s){t.isLoading=s},expression:"isLoading"}},[i("div",{staticClass:"content"},[t.siteInfo?i("div",[i("div",{staticClass:"circlePL"},[i("div",{staticClass:"infoItem"},[i("span",{staticClass:"infoItemLeft"},[t._v("站点名称")]),t._v(" "),i("span",{staticClass:"infoItemRight"},[t._v(t._s(t.siteInfo._data.set_site.site_name))])])]),t._v(" "),i("div",{staticClass:"circlePL"},[i("div",{staticClass:"circleLoBox"},[i("span",{staticClass:"circleIcon"},[t._v("站点图标")]),t._v(" "),t.siteInfo._data.set_site.site_logo?i("img",{staticClass:"circleLogo",attrs:{src:t.siteInfo._data.set_site.site_logo}}):i("img",{staticClass:"circleLogo",attrs:{src:t.appConfig.staticBaseUrl+"/images/logo.png"}})])]),t._v(" "),i("div",{staticClass:"circleInfo padB0 lastBorNone"},[i("h1",{staticClass:"cirInfoTit"},[t._v("站点简介")]),t._v(" "),i("p",{staticClass:"cirInfoWord"},[t._v(t._s(t.siteInfo._data.set_site.site_introduction))]),t._v(" "),i("div",{staticClass:"infoItem"},[i("span",{staticClass:"infoItemLeft"},[t._v("创建时间")]),t._v(" "),i("span",{staticClass:"infoItemRight"},[t._v(t._s(t.siteInfo._data.set_site.site_install))])]),t._v(" "),t.siteInfo._data.set_site.site_price||t.siteInfo._data.set_site.site_expire?i("div",{staticClass:"infoItem"},[i("span",{staticClass:"infoItemLeft"},[t._v("加入方式")]),t._v(" "),i("span",{staticClass:"infoItemRight"},[t._v("付费"+t._s(t.siteInfo._data.set_site.site_price)+"元，"+t._s("0"===t.siteInfo._data.set_site.site_expire||""===t.siteInfo._data.set_site.site_expire?"永久加入":"有效期自加入起"+t.siteInfo._data.set_site.site_expire+"天"))])]):t._e(),t._v(" "),i("div",{staticClass:"infoItem"},[i("span",{staticClass:"infoItemLeft"},[t._v("站长")]),t._v(" "),t.siteInfo._data.set_site.site_author?i("span",{staticClass:"infoItemRight"},[t._v(t._s(t.username))]):i("span",{staticClass:"infoItemRight"},[t._v("无")])]),t._v(" "),i("div",{staticClass:"infoItem"},[i("div",{staticClass:"overHide"},[i("span",{staticClass:"infoItemLeft"},[t._v("站点成员")]),t._v(" "),t.moreMemberShow?i("a",{staticClass:"infoItemRight lookMore",on:{click:t.moreCilrcleMembers}},[t._v("查看更多"),i("span",{staticClass:"icon iconfont icon-right-arrow"})]):t._e()]),t._v(" "),i("div",{staticClass:"circleMemberList"},t._l(t.siteInfo.users,(function(s,e){return""!==s._data.avatarUrl&&null!==s._data.avatarUrl?i("img",{key:s._data.avatarUrl,staticClass:"circleMember",attrs:{src:s._data.avatarUrl,alt:s._data.username}}):i("img",{staticClass:"circleMember",attrs:{src:t.appConfig.staticBaseUrl+"/images/noavatar.gif"},on:{click:function(i){return t.membersJump(s._data.id)}}})})),0)])]),t._v(" "),i("div",{staticClass:"gap"}),t._v(" "),i("div",{staticClass:"circleInfo padT0"},[i("div",{staticClass:"infoItem"},[i("span",{staticClass:"infoItemLeft"},[t._v("我的角色")]),t._v(" "),t._l(t.roleList,(function(s,e){return i("span",{staticClass:"infoItemRight"},[t._v(t._s(s._data.name))])}))],2),t._v(" "),i("div",{staticClass:"infoItem"},[i("span",{staticClass:"infoItemLeft"},[t._v("加入时间")]),t._v(" "),i("span",{staticClass:"infoItemRight"},[t._v(t._s(t.$moment(t.joinedAt).format("YYYY-MM-DD")))])]),t._v(" "),t.expiredAt?i("div",{staticClass:"infoItem"},[i("span",{staticClass:"infoItemLeft"},[t._v("有效期至")]),t._v(" "),i("span",{staticClass:"infoItemRight"},[t._v(t._s(t.$moment(t.expiredAt).format("YYYY-MM-DD")))])]):t._e()]),t._v(" "),t.limitList?i("div",{staticClass:"powerListBox"},[i("div",{staticClass:"powerTit"},[t._v("作为"+t._s(t.limitList._data.name)+"，您将获得以下权限")]),t._v(" "),i("div",{staticClass:"powerList"},[i("div",{staticClass:"powerClassify"},[t._v("权限列表")]),t._v(" "),t._l(t.limitList.permission,(function(s,e){return i("div",{},[s._data.permission&&"viewThreads"==s._data.permission?i("p",{staticClass:"powerChi"},[t._v("查看主题列表"),i("i",{staticClass:"iconfont icon-selected"})]):t._e(),t._v(" "),s._data.permission&&"thread.viewPosts"==s._data.permission?i("p",{staticClass:"powerChi"},[t._v("查看主题"),i("i",{staticClass:"iconfont icon-selected"})]):t._e(),t._v(" "),s._data.permission&&"createThread"==s._data.permission?i("p",{staticClass:"powerChi"},[t._v("发表主题"),i("i",{staticClass:"iconfont icon-selected"})]):t._e(),t._v(" "),s._data.permission&&"thread.reply"==s._data.permission?i("p",{staticClass:"powerChi"},[t._v("回复主题"),i("i",{staticClass:"iconfont icon-selected"})]):t._e(),t._v(" "),s._data.permission&&"attachment.create.0"==s._data.permission?i("p",{staticClass:"powerChi"},[t._v("上传附件"),i("i",{staticClass:"iconfont icon-selected"})]):t._e(),t._v(" "),s._data.permission&&"attachment.create.1"==s._data.permission?i("p",{staticClass:"powerChi"},[t._v("上传图片"),i("i",{staticClass:"iconfont icon-selected"})]):t._e(),t._v(" "),s._data.permission&&"attachment.view.0"==s._data.permission?i("p",{staticClass:"powerChi"},[t._v("查看附件"),i("i",{staticClass:"iconfont icon-selected"})]):t._e(),t._v(" "),s._data.permission&&"attachment.view.1"==s._data.permission?i("p",{staticClass:"powerChi"},[t._v("查看图片"),i("i",{staticClass:"iconfont icon-selected"})]):t._e(),t._v(" "),s._data.permission&&"viewUserList"==s._data.permission?i("p",{staticClass:"powerChi"},[t._v("站点会员列表"),i("i",{staticClass:"iconfont icon-selected"})]):t._e(),t._v(" "),s._data.permission&&"attachment.delete"==s._data.permission?i("p",{staticClass:"powerChi"},[t._v("删除附件"),i("i",{staticClass:"iconfont icon-selected"})]):t._e(),t._v(" "),s._data.permission&&"cash.create"==s._data.permission?i("p",{staticClass:"powerChi"},[t._v("申请提现"),i("i",{staticClass:"iconfont icon-selected"})]):t._e(),t._v(" "),s._data.permission&&"order.create"==s._data.permission?i("p",{staticClass:"powerChi"},[t._v("创建订单"),i("i",{staticClass:"iconfont icon-selected"})]):t._e(),t._v(" "),s._data.permission&&"thread.hide"==s._data.permission?i("p",{staticClass:"powerChi"},[t._v("删除主题"),i("i",{staticClass:"iconfont icon-selected"})]):t._e(),t._v(" "),s._data.permission&&"thread.hidePosts"==s._data.permission?i("p",{staticClass:"powerChi"},[t._v("删除回复"),i("i",{staticClass:"iconfont icon-selected"})]):t._e(),t._v(" "),s._data.permission&&"thread.favorite"==s._data.permission?i("p",{staticClass:"powerChi"},[t._v("帖子收藏"),i("i",{staticClass:"iconfont icon-selected"})]):t._e(),t._v(" "),s._data.permission&&"thread.likePosts"==s._data.permission?i("p",{staticClass:"powerChi"},[t._v("帖子点赞"),i("i",{staticClass:"iconfont icon-selected"})]):t._e(),t._v(" "),s._data.permission&&"user.view"==s._data.permission?i("p",{staticClass:"powerChi"},[t._v("查看某个用户信息权限"),i("i",{staticClass:"iconfont icon-selected"})]):t._e(),t._v(" "),s._data.permission&&"viewSiteInfo"==s._data.permission?i("p",{staticClass:"powerChi"},[t._v("站点信息"),i("i",{staticClass:"iconfont icon-selected"})]):t._e(),t._v(" "),s._data.permission&&"user.edit"==s._data.permission?i("p",{staticClass:"powerChi"},[t._v("编辑用户状态"),i("i",{staticClass:"iconfont icon-selected"})]):t._e(),t._v(" "),s._data.permission&&"group.edit"==s._data.permission?i("p",{staticClass:"powerChi"},[t._v("编辑用户组"),i("i",{staticClass:"iconfont icon-selected"})]):t._e(),t._v(" "),s._data.permission&&"createInvite"==s._data.permission?i("p",{staticClass:"powerChi"},[t._v("管理-邀请加入"),i("i",{staticClass:"iconfont icon-selected"})]):t._e(),t._v(" "),s._data.permission&&"thread.batchEdit"==s._data.permission?i("p",{staticClass:"powerChi"},[t._v("批量管理主题"),i("i",{staticClass:"iconfont icon-selected"})]):t._e(),t._v(" "),s._data.permission&&"thread.editPosts"==s._data.permission?i("p",{staticClass:"powerChi"},[t._v("编辑"),i("i",{staticClass:"iconfont icon-selected"})]):t._e(),t._v(" "),s._data.permission&&"thread.essence"==s._data.permission?i("p",{staticClass:"powerChi"},[t._v("加精"),i("i",{staticClass:"iconfont icon-selected"})]):t._e(),t._v(" "),s._data.permission&&"thread.sticky"==s._data.permission?i("p",{staticClass:"powerChi"},[t._v("置顶"),i("i",{staticClass:"iconfont icon-selected"})]):t._e()])}))],2)]):t._e()]):t._e()])])],1)},a=[];i.d(s,"a",(function(){return e})),i.d(s,"b",(function(){return a}))},SyFs:function(t,s,i){"use strict";i.r(s);var e=i("lytT"),a=i.n(e);for(var o in e)"default"!==o&&function(t){i.d(s,t,(function(){return e[t]}))}(o);s.default=a.a},VGvU:function(t,s,i){"use strict";Object.defineProperty(s,"__esModule",{value:!0});var e,a=i("VVfg"),o=(e=a)&&e.__esModule?e:{default:e};s.default={data:function(){return{siteInfo:!1,username:"",joinedAt:"",expiredAt:"",isLoading:!1,roleList:[],groupId:"",limitList:"",moreMemberShow:""}},beforeCreate:function(){},created:function(){this.loadSite();o.default.getLItem("tokenId")},beforeMount:function(){},methods:{loadSite:function(){var t=this,s=o.default.getLItem("tokenId"),i=this.appFetch({url:"users",method:"get",splice:"/"+s,data:{include:"groups"}}).then((function(s){s.errors?t.$toast.fail(s.errors[0].code):(t.roleList=s.readdata.groups,t.groupId=s.readdata.groups[0]._data.id,""==s.readdata._data.joinedAt||null==s.readdata._data.joinedAt?t.joinedAt=s.readdata._data.createdAt:t.joinedAt=s.readdata._data.joinedAt,t.expiredAt=s.readdata._data.expiredAt),t.appFetch({url:"groups",method:"get",splice:"/"+t.groupId,data:{include:["permission"]}}).then((function(s){s.errors?t.$toast.fail(s.errors[0].code):t.limitList=s.readdata}))}));return this.appFetch({url:"forum",method:"get",data:{include:["users"]}}).then((function(s){s.errors?t.$toast.fail(s.errors[0].code):(t.siteInfo=s.readdata,t.moreMemberShow=s.readdata._data.other.can_view_user_list,s.readdata._data.set_site.site_author&&(t.username=s.readdata._data.set_site.site_author.username))})),i},moreCilrcleMembers:function(){this.$router.push({path:"circle-members"})},membersJump:function(t){this.$router.push({path:"/home-page/"+t})},onRefresh:function(){var t=this;this.loadSite().then((function(s){t.$toast("刷新成功"),t.isLoading=!1,t.finished=!1})).catch((function(s){t.$toast("刷新失败"),t.isLoading=!1}))}},mounted:function(){},beforeRouteLeave:function(t,s,i){i()}}},lytT:function(t,s,i){"use strict";Object.defineProperty(s,"__esModule",{value:!0});var e=n(i("QbLZ")),a=n(i("JZuw")),o=n(i("VGvU"));function n(t){return t&&t.__esModule?t:{default:t}}i("iUmJ"),i("N960"),s.default=(0,e.default)({name:"circleInfoView",components:{comHeader:a.default}},o.default)}}]);