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
      console.log(val);
      console.log(id);
      if (val === 'pass'){
        this.editUser(id,0)
      }else if (val === 'no'){
        this.$MessageBox.prompt('', '提示', {
          confirmButtonText: '提交',
          cancelButtonText: '取消',
          inputPlaceholder:'请输入否决原因'
        }).then((value)=>{
          console.log(value);
          this.editUser(id,1,value.value)
        }).catch((err) => {
          console.log(err);
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
          console.log(err);
        });
      } else if (val === 'del'){
        this.multipleSelection.forEach((item)=>{
          userList.push(item._data.id)
        });
        this.patchDeleteUser(userList)
      }

      console.log(userList);

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
        console.log(res);
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
        console.log(err);
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
        console.log(res);
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
        console.log(err);
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
          this.getUserList();
        }
      }).catch(err=>{
        console.log(err);
      })
    },
    deleteUser(id){
      this.appFetch({
        url:'users',
        method:'delete',
        splice:'/' + id,
        data:{}
      }).then(res=>{
        console.log(res);
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
        console.log(err);
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
