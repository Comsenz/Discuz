(window.webpackJsonp=window.webpackJsonp||[]).push([[60],{Aa10:function(e,t,i){"use strict";i.r(t);var s=i("ggnj"),n=i.n(s);for(var a in s)"default"!==a&&function(e){i.d(t,e,(function(){return s[e]}))}(a);t.default=n.a},acyA:function(e,t,i){"use strict";var s=function(){var e=this,t=e.$createElement,i=e._self._c||t;return i("div",{staticClass:"cont-class-box"},[i("div",{staticClass:"cont-class-table"},[i("el-table",{ref:"multipleTable",staticStyle:{width:"100%"},attrs:{data:e.categoriesList,"tooltip-effect":"dark"},on:{"selection-change":e.handleSelectionChange}},[i("el-table-column",{attrs:{type:"selection",width:"50"}}),e._v(" "),i("el-table-column",{attrs:{label:"分类名称","min-width":"200"},scopedSlots:e._u([{key:"default",fn:function(t){return[i("el-input",{attrs:{clearable:""},model:{value:t.row.name,callback:function(i){e.$set(t.row,"name",i)},expression:"scope.row.name"}})]}}])}),e._v(" "),i("el-table-column",{attrs:{label:"排序",width:"120"},scopedSlots:e._u([{key:"default",fn:function(t){return[i("el-input",{attrs:{clearable:""},model:{value:t.row.sort,callback:function(i){e.$set(t.row,"sort",i)},expression:"scope.row.sort"}})]}}])}),e._v(" "),i("el-table-column",{attrs:{label:"分类介绍","min-width":"250"},scopedSlots:e._u([{key:"default",fn:function(t){return[i("el-input",{attrs:{clearable:""},model:{value:t.row.description,callback:function(i){e.$set(t.row,"description",i)},expression:"scope.row.description"}})]}}])}),e._v(" "),i("el-table-column",{attrs:{label:"操作",width:"100"},scopedSlots:e._u([{key:"default",fn:function(t){return[i("el-popover",{ref:"popover-"+t.$index,attrs:{width:"100",placement:"top"}},[i("p",[e._v("确定删除该项吗？")]),e._v(" "),i("div",{staticStyle:{"text-align":"right",margin:"10PX 0 0 0"}},[i("el-button",{attrs:{type:"text",size:"mini"},on:{click:function(e){t._self.$refs["popover-"+t.$index].doClose()}}},[e._v("\n                取消\n              ")]),e._v(" "),i("el-button",{attrs:{type:"danger",size:"mini"},on:{click:function(i){e.deleteClick(t.row.id,t.$index),t._self.$refs["popover-"+t.$index].doClose()}}},[e._v("确定")])],1),e._v(" "),i("el-button",{attrs:{slot:"reference",type:"text"},slot:"reference"},[e._v("删除")])],1)]}}])})],1),e._v(" "),i("TableContAdd",{attrs:{cont:"添加内容分类"},on:{tableContAddClick:e.tableContAdd}}),e._v(" "),i("Card",{staticClass:"footer-btn"},[i("el-button",{attrs:{type:"primary",size:"medium"},on:{click:e.submitClick}},[e._v("提交")]),e._v(" "),i("el-popover",{attrs:{width:"100",placement:"top"},model:{value:e.visible,callback:function(t){e.visible=t},expression:"visible"}},[i("p",[e._v("确定删除该项吗？")]),e._v(" "),i("div",{staticStyle:{"text-align":"right",margin:"10PX 0 0 0"}},[i("el-button",{attrs:{type:"text",size:"mini"},on:{click:function(t){e.visible=!1}}},[e._v("取消")]),e._v(" "),i("el-button",{attrs:{type:"danger",size:"mini"},on:{click:e.deleteAllClick}},[e._v("确定")])],1),e._v(" "),i("el-button",{staticStyle:{"margin-left":"10PX"},attrs:{slot:"reference",size:"medium",disabled:e.deleteStatus},slot:"reference"},[e._v("删除")])],1)],1)],1)])},n=[];i.d(t,"a",(function(){return s})),i.d(t,"b",(function(){return n}))},c53j:function(e,t,i){"use strict";Object.defineProperty(t,"__esModule",{value:!0});var s=a(i("4gYi")),n=a(i("kAKY"));function a(e){return e&&e.__esModule?e:{default:e}}t.default={data:function(){return{categoriesList:[],categoriesListLength:"",createCategoriesStatus:!1,deleteStatus:!0,multipleSelection:[],visible:!1}},methods:{handleSelectionChange:function(e){this.multipleSelection=e,this.multipleSelection.length>=1?this.deleteStatus=!1:this.deleteStatus=!0},tableContAdd:function(){this.createCategoriesStatus=!0,this.categoriesList.push({name:"",id:"",description:"",sort:""})},submitClick:function(){var e=this;if(this.createCategoriesStatus&&this.multipleSelection.length>0)this.$message({showClose:!0,message:"新增内容分类未提交！请先提交，再勾选其他分类",type:"warning"});else if(this.createCategoriesStatus)console.log(this.categoriesList.slice(this.categoriesListLength,this.categoriesList.length)),this.createCategories(this.categoriesList.slice(this.categoriesListLength,this.categoriesList.length)).then((function(){e.getCategories(),e.createCategoriesStatus=!1}));else if(this.multipleSelection.length>0){var t=[];this.multipleSelection.forEach((function(e){t.push({type:"categories",id:e.id,attributes:{name:e.name,description:e.description,sort:e.sort}})})),this.batchUpdateCategories(t).then((function(){e.getCategories()}))}else this.$message({showClose:!0,message:"操作选项错误，请重新选择 或 刷新页面(F5)",type:"warning"})},deleteClick:function(e,t){var i=this;console.log(t),console.log(this.categoriesListLength),this.createCategoriesStatus&&t>this.categoriesListLength-1?(this.categoriesList.splice(t,1),console.log(this.categoriesList)):this.deleteCategories(e).then((function(){i.getCategories()}))},deleteAllClick:function(){var e=this,t=[];this.multipleSelection.forEach((function(i,s){s<e.multipleSelection.length&&t.push(i.id)})),this.batchDeleteCategories(t.join(",")).then((function(){e.getCategories()})),this.visible=!1},getCategories:function(){var e=this;this.appFetch({url:"categories",method:"get",data:{}}).then((function(t){e.categoriesListLength=t.data.length,e.categoriesList=[],t.data.forEach((function(t,i){e.categoriesList.push({name:t.attributes.name,id:t.id,description:t.attributes.description,sort:t.attributes.sort})}))})).catch((function(e){console.log(e)}))},deleteCategories:function(e){var t=this;return this.appFetch({url:"categoriesDelete",method:"delete",splice:"/"+e}).then((function(e){e.meta?t.$message.error("操作失败！"):t.$message({message:"操作成功",type:"success"})})).catch((function(e){console.log(e)}))},batchDeleteCategories:function(e){var t=this;return this.appFetch({url:"categoriesBatchDelete",method:"delete",splice:"/"+e}).then((function(e){e.meta?t.$message.error("操作失败！"):t.$message({message:"操作成功",type:"success"})})).catch((function(e){console.log(e)}))},createCategories:function(e){var t=this,i=[];return e.forEach((function(e){i.push({type:"categories",attributes:{name:e.name,description:e.description,sort:e.sort}})})),this.appFetch({url:"createBatchCategories",method:"post",data:{data:i}}).then((function(e){e.meta?t.$message.error("操作失败！"):t.$message({message:"操作成功",type:"success"})})).catch((function(e){console.log(e)}))},batchUpdateCategories:function(e){var t=this;return this.appFetch({url:"categoriesBatchUpdate",method:"patch",data:{data:e}}).then((function(e){e.meta?t.$message.error("操作失败！"):t.$message({message:"操作成功",type:"success"})})).catch((function(e){console.log(e)}))}},created:function(){this.getCategories()},components:{Card:s.default,TableContAdd:n.default}}},"f+jx":function(e,t,i){"use strict";i.r(t);var s=i("acyA"),n=i("Aa10");for(var a in n)"default"!==a&&function(e){i.d(t,e,(function(){return n[e]}))}(a);var o=i("KHd+"),c=Object(o.a)(n.default,s.a,s.b,!1,null,null,null);t.default=c.exports},ggnj:function(e,t,i){"use strict";Object.defineProperty(t,"__esModule",{value:!0});var s=a(i("QbLZ"));i("cajz");var n=a(i("c53j"));function a(e){return e&&e.__esModule?e:{default:e}}t.default=(0,s.default)({name:"cont-class"},n.default)}}]);