/*
* 用户角色控制器
* */

import Card from '../../../../view/site/common/card/card';
import CardRow from '../../../../view/site/common/card/cardRow';
import TableContAdd from '../../../../view/site/common/table/tableContAdd';
import Page from '../../../../view/site/common/page/page';

export default {
  data:function () {
    return {
      tableData: [],
      pageNum: 1,
      pageLimit: 20,
      total: 0,
    }
  },
  methods:{
    handleSelectionChange(val) {
      this.multipleSelection = val;

      // if (this.multipleSelection.length >= 1){
      //   this.deleteStatus = false
      // } else {
      //   this.deleteStatus = true
      // }
    },


    /*
    * 接口请求
    * */
    getNoticeList(){
      // alert('执行');
      this.appFetch({
        url:'notice',
        method:'get',
        data:{}
      }).then(res=>{
        console.log(res);
        if (res.errors){
          this.$message.error(res.errors[0].code);
        }else {
          this.tableData = res.readdata;
          this.total = res.meta.total;
          // this.pageNum = res.meta.pageCount;
          // this.total = res.meta ? res.meta.total : 0;
          console.log(this.tableData,'????????????');
          this.tableData.forEach((item)=>{
            // item.index = (currentPage-1)*pageSize+index+1
          })
          this.alternateLength = res.readdata.length;
          // this.tableData.forEach((item) => {
          //   if (item._data.default == 1) {
          //     this.radio = item._data.id;
          //     this.alternateRadio = item._data.id;
          //   }
          // })
        }
      }).catch(err=>{
        console.log(err);
      })
    },
    postGroups(data){
      this.appFetch({
        url:"groups",
        method:"post",
        data:{
          data
        }
      }).then(res=>{
        if (res.errors){
          this.$message.error(res.errors[0].code);
        }else {
          this.$message({
            message: '提交成功！',
            type: 'success'
          });
          this.addStatus = false;
          this.getGroups();
        }
      }).catch(err=>{
        console.log(err);
      })
    },
    // singleDeleteGroup(id){
    //   this.appFetch({
    //     url:'groups',
    //     method:'delete',
    //     splice:'/' + id,
    //     data:{}
    //   }).then(res=>{
    //     if (res.errors){
    //       this.$message.error(res.errors[0].code);
    //     }else {
    //       this.$message({
    //         message: '删除成功！',
    //         type: 'success'
    //       });
    //       this.getGroups();
    //     }
    //   }).catch(err=>{
    //     console.log(err);
    //   })
    // },
    // batchDeleteGroup(data){
    //   this.appFetch({
    //     url:'groups',
    //     method:'delete',
    //     data:{
    //       data
    //     }
    //   }).then(res=>{
    //     if (res.errors){
    //       this.$message.error(res.errors[0].code);
    //     }else {
    //       this.$message({
    //         message: '删除成功！',
    //         type: 'success'
    //       });
    //       this.getGroups();
    //     }
    //   }).catch(err=>{
    //     console.log(err);
    //   })
    // },
    // singlePatchGroup(id,name){
    //   this.appFetch({
    //     url:'groups',
    //     method:'patch',
    //     splice:'/' + id,
    //     data:{
    //       data:{
    //         "attributes": {
    //           'name':name,
    //           'default':1
    //         }
    //       }
    //     }
    //   }).then(res=>{
    //     if (res.errors){
    //       this.$message.error(res.errors[0].code);
    //     }else {
    //       this.$message({
    //         message: '提交成功！',
    //         type: 'success'
    //       });
    //       this.getGroups();
    //     }
    //   }).catch(err=>{
    //     console.log(err);
    //   })
    // },
    // batchPatchGroup(data){
    //   this.appFetch({
    //     url:'groups',
    //     method:'patch',
    //     data:{
    //       data
    //     }
    //   }).then(res=>{
    //     console.log(res);
    //     if (res.errors){
    //       this.$message.error(res.errors[0].code);
    //     }else {
    //       this.$message({
    //         message: '提交成功！',
    //         type: 'success'
    //       });
    //       this.getGroups();
    //     }
    //   }).catch(err=>{
    //     console.log(err);
    //   })
    // }
    //获取表格序号
    getIndex($index) {
      //表格序号
      return (this.pageNum - 1) * this.pageLimit + $index + 1
    },
    handleCurrentChange(val){
      this.pageNum = val;
      this.getNoticeList();
    }
  },
  created(){
    this.getNoticeList();
  },
  components:{
    Card,
    CardRow,
    Page
  }
}
