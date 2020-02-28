/*
* 用户角色控制器
* */

import Card from '../../../../view/site/common/card/card';
import CardRow from '../../../../view/site/common/card/cardRow';
import TableContAdd from '../../../../view/site/common/table/tableContAdd';

export default {
  data:function () {
    return {
      tableData: [],
      alternateLength:0,    //数据长度备份
      radio:'',
      alternateRadio:'',    //默认级别选中备份
      radioName:'',         //默认级别名称
      deleteStatus:true,
      multipleSelection:[],
      addStatus:false
    }
  },
  methods:{
    handleSelectionChange(val) {
      this.multipleSelection = val;

      if (this.multipleSelection.length >= 1){
        this.deleteStatus = false
      } else {
        this.deleteStatus = true
      }
    },

    /*checkSelect(val){

    },*/

    radioChange(val){
      this.radioName = val._data.name;
    },

    checkSelectable(row){
      switch (row._data.id){
        case '1':
          return false;
          break;
        case '6':
          return false;
          break;
        case '7':
          return false;
          break;
        case '10':
          return false;
          break;
        default:
          return true;
      }
    },

    addList(){
      if (this.alternateLength >= this.tableData.length){
        this.tableData.push({
          _data:{
            "name": "",
            "type": "",
            "color": "",
            "icon": ""
          }
        });
      }
      this.addStatus = true;
    },

    submitClick(){
      /*if (this.addStatus && this.multipleSelection.length > 0){
        this.$message({
          showClose: true,
          message: '新增用户角色未提交！请先提交，再操作其他角色',
          type: 'warning'
        });
      } else*/
      if (this.addStatus){
        let singleData = {
          "type": "groups",
          "attributes": {
            "name": ""
          }
        };    //单个

        let batchData = [];   //批量

        for (let i = this.alternateLength;i < this.tableData.length;i++){
          /*
          * 批量添加写法，接口暂时不支持
          * */
          /*batchData.push({
            "type": "groups",
            "attributes": {
              "name": this.tableData[i]._data.name,
              "type": "",
              "color": "",
              "icon": ""
            }
          })*/

          /*
          * 单个添加用户组写法
          * */
          singleData.attributes.name = this.tableData[i]._data.name;
        }

        this.postGroups(singleData);

      }else if(this.radio !== this.alternateRadio) {
        this.singlePatchGroup(this.radio,this.radioName);
      } else {
        let data = [];
        this.tableData.forEach((item)=>{
          data.push({
            "attributes": {
              "name": item._data.name,
              'id':item._data.id
            },
          })
        });
        this.batchPatchGroup(data);
      }
    },

    singleDelete(index,id){
      if (index > this.alternateLength-1){
        this.tableData.pop();
      } else {
        this.singleDeleteGroup(id);
      }
    },

    deleteClick(){
      let data = {
        id:[]
      };
      this.multipleSelection.forEach((item)=>{
        data.id.push(item._data.id)
      });
      this.batchDeleteGroup(data)
    },

    /*
    * 接口请求
    * */
    getGroups(){
      this.appFetch({
        url:'groups',
        method:'get',
        data:{}
      }).then(res=>{
        if (res.errors){
          this.$message.error(res.errors[0].code);
        }else {
          this.tableData = res.readdata;
          this.alternateLength = res.readdata.length;
          this.tableData.forEach((item) => {
            if (item._data.default == 1) {
              this.radio = item._data.id;
              this.alternateRadio = item._data.id;
            }
          })
        }
      }).catch(err=>{
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
      })
    },
    singleDeleteGroup(id){
      this.appFetch({
        url:'groups',
        method:'delete',
        splice:'/' + id,
        data:{}
      }).then(res=>{
        if (res.errors){
          this.$message.error(res.errors[0].code);
        }else {
          this.$message({
            message: '删除成功！',
            type: 'success'
          });
          this.getGroups();
        }
      }).catch(err=>{
      })
    },
    batchDeleteGroup(data){
      this.appFetch({
        url:'groups',
        method:'delete',
        data:{
          data
        }
      }).then(res=>{
        if (res.errors){
          this.$message.error(res.errors[0].code);
        }else {
          this.$message({
            message: '删除成功！',
            type: 'success'
          });
          this.getGroups();
        }
      }).catch(err=>{
      })
    },
    singlePatchGroup(id,name){
      this.appFetch({
        url:'groups',
        method:'patch',
        splice:'/' + id,
        data:{
          data:{
            "attributes": {
              'name':name,
              'default':1
            }
          }
        }
      }).then(res=>{
        if (res.errors){
          this.$message.error(res.errors[0].code);
        }else {
          this.$message({
            message: '提交成功！',
            type: 'success'
          });
          this.getGroups();
        }
      }).catch(err=>{
      })
    },
    batchPatchGroup(data){
      this.appFetch({
        url:'groups',
        method:'patch',
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
          this.getGroups();
        }
      }).catch(err=>{
      })
    }

  },
  created(){
    this.getGroups();
  },
  components:{
    Card,
    CardRow,
    TableContAdd
  }
}
