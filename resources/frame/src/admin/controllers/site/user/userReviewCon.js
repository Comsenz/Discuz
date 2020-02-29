/*
* 用户审核管理器
* */

import moment from "moment/moment";
import Card from '../../../view/site/common/card/card';


export default {
  data:function () {
    return {
      tableData: [],
      multipleSelection: []
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
          console.log(value);
          this.editUser(id,3,value.value)
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
        this.patchDeleteUser(userList)
      }


    },

    /*
    * 格式化日期
    * */
    formatDate(data){
      return moment(data).format('YYYY-MM-DD HH:mm')
    },

    getUserList(){
      this.appFetch({
        url:'users',
        method:'get',
        data:{
          'filter[status]':'mod'
        }
      }).then(res=>{
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
          this.getUserList();
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
          this.getUserList();
        }
      }).catch(err=>{
      })
    },
    patchDeleteUser(dataList){       //批量忽略接口
      this.appFetch({
        url:'users',
        method:'PATCH',
        splice:'/'+dataList,
        data:{
          data:{
            "attributes": {
              "id": dataList,
              'status':'4',
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
          this.getUserList();
        }
      }).catch(err=>{
      })
    },
    deleteUser(id){              //单个忽略接口
      this.appFetch({
        url:'users',
        method:'PATCH',
        splice:'/'+id,
        data:{
          data:{
            "attributes": {
              'status':'4',
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
          this.getUserList();
        }
      }).catch(err=>{
      })
    }


  },
  created(){
    this.getUserList();
  },
  components:{
    Card
  }
}
