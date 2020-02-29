/*
* 用户审核管理器
* */

import moment from "moment/moment";
import Card from '../../../view/site/common/card/card';
import Page from '../../../view/site/common/page/page';
import tableNoList from '../../../view/site/common/table/tableNoList';
import webDb from 'webDbHelper';


export default {
  data:function () {
    return {
      tableData: [],
      multipleSelection: [],
      visible:false,
      currentPaga: 1,             //当前页数
      total:0,                    //主题列表总条数
      pageCount:1,                //总页数
    }
  },

  methods: {
    /*toggleSelection(rows) {
      if (rows) {
        rows.forEach(row => {
          this.$refs.multipleTable.toggleRowSelection(row)
        });
      } else {
        this.$refs.multipleTable.clearSelection();
      }
    },*/
    handleSelectionChange(val) {
      this.multipleSelection = val;
    },

    singleOperation(val,id){
      if (val === 'pass'){
        this.editUser(id,0)
      }else if (val === 'no'){
        this.$MessageBox.prompt('', '提示', {
          confirmButtonText: '提交',
          cancelButtonText: '取消',
          inputPlaceholder:'请输入否决原因'
        }).then((value)=>{
          this.editUser(id,1,value.value)
        }).catch((err) => {
        });
      }else if (val === 'del'){
        this.deleteUser(id)
      }
    },

    allOperation(val){
      let userList = [];

      if (val === 'pass'){
        this.multipleSelection.forEach((item)=>{
          userList.push({
            "attributes": {
              "id":item._data.id,
              "status": '0',
            }
          })
        });
        this.patchEditUser(userList);
      } else if (val === 'no'){
        this.$MessageBox.prompt('', '提示', {
          confirmButtonText: '提交',
          cancelButtonText: '取消',
          inputPlaceholder:'请输入否决原因'
        }).then((value)=>{
          this.multipleSelection.forEach((item)=>{
            userList.push({
              "attributes": {
                "id":item._data.id,
                "status": '1',
                "refuse_message": value.value
              }
            })
          });
          this.patchEditUser(userList);
        }).catch((err) => {
        });
      } else if (val === 'del'){
        this.multipleSelection.forEach((item)=>{
          userList.push(item._data.id)
        });
        this.patchDeleteUser(userList);
        this.visible = false;
      }


    },

    handleCurrentChange(val) {
      document.getElementsByClassName('index-main-con__main')[0].scrollTop = 0;
      // this.isIndeterminate = false;
      // this.checkAll = false;
      this.currentPaga = val;
      this.getUserList(val);
    },

    /*
    * 格式化日期
    * */
    formatDate(data){
      return moment(data).format('YYYY-MM-DD HH:mm')
    },

    getUserList(pageNumber){
      this.appFetch({
        url:'users',
        method:'get',
        data:{
          'filter[status]':'mod',
          'page[number]':pageNumber,
          'page[size]':10,
        }
      }).then(res=>{
        console.log(res);
        this.total = res.meta.total;
        this.pageCount = res.meta.pageCount;
        this.tableData = res.readdata;
      })
    },
    editUser(id,status,message){
      this.appFetch({
        url:'users',
        method:'PATCH',
        splice:'/'+id,
        data:{
          data:{
            "attributes": {
              'status':status,
              'refuse_message':message
            }
          }
        }
      }).then(res=>{
        if (res.errors){
          this.$message.error(res.errors[0].code);
        }else {
          this.$message({
            message: '操作成功',
            type: 'success'
          });
          this.getUserList(Number(webDb.getLItem('currentPag')) || 1);
        }
      }).catch(err=>{
      })
    },
    patchEditUser(dataList){
      this.appFetch({
        method: 'PATCH',
        url: 'users',
        data: {
          "data": dataList
        }
      }).then(res=>{
        if (res.errors){
          this.$message.error(res.errors[0].code);
        }else {
          this.$message({
            message: '操作成功',
            type: 'success'
          });
          this.getUserList(Number(webDb.getLItem('currentPag')) || 1);
        }
      }).catch(err=>{
      })
    },
    patchDeleteUser(dataList){
      this.appFetch({
        url:'users',
        method:'delete',
        data:{
          data:{
            "attributes": {
              "id": dataList
            }
          }
        }
      }).then(res=>{
        if (res.errors){
          this.$message.error(res.errors[0].code);
        }else {
          this.$message({
            message: '操作成功',
            type: 'success'
          });
          this.getUserList(Number(webDb.getLItem('currentPag')) || 1);
        }
      }).catch(err=>{
      })
    },
    deleteUser(id){
      this.appFetch({
        url:'users',
        method:'delete',
        splice:'/' + id,
        data:{}
      }).then(res=>{
        if (res.errors){
          this.$message.error(res.errors[0].code);
        }else {
          this.$message({
            message: '操作成功',
            type: 'success'
          });
          this.getUserList(Number(webDb.getLItem('currentPag')) || 1);
        }
      }).catch(err=>{
      })
    },

    getCreated(state){
      if(state){
        this.getUserList(1);
      } else {
        this.getUserList(Number(webDb.getLItem('currentPag'))||1);
      }
    }

  },
  created(){
    // this.getUserList();
  },
  beforeRouteEnter(to,from,next){
    next(vm => {
      if (to.name !== from.name && from.name !== null){
        vm.getCreated(true)
      }else {
        vm.getCreated(false)
      }
    })
  },
  components:{
    Card,
    Page,
    tableNoList
  }
}
