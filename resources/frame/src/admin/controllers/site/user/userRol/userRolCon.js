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
      radio:'',             //设为加入站点的默认级别
      alternateRadio:'',    //默认级别选中备份
      radioName:'',         //默认级别名称
      radioIndex:'',        //默认级别序号
      deleteStatus:true,
      multipleSelection:[],
      addStatus:false,
      btnLoading:false,     //提交按钮状态
      delLoading:false,     //删除按钮状态
      groupName:'',      //是否显示用户组名称
      imageUrl: "",
      imgWidth: 0,
      imgHeight: 0,
      uploadUserId: '', // 点击当前用户时id
      uploadUserIndex: '',  //点击当前用户时index
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

    radioChange(val,index){
      this.radioName = val._data.name;
      this.radioIndex = index;
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
    uploadClick(id,index){
      console.log('点击id',id);
      this.uploadUserId = id;
      this.uploadUserIndex = index;
    },
    //上传时，判断文件的类型及大小是否符合规则
    beforeAvatarUpload(id) {
      console.log('测试');
      // const isJPG = file.type === "image/png";
      // const isLt2M = file.size / 1024 / 1024 < 2;
      // if (!isJPG) {
      //   this.$message.warning("上传水印图片只能是 PNG 格式!");
      //   return isJPG;
      // }
      // if (!isLt2M) {
      //   this.$message.warning("上传头像图片大小不能超过 2MB!");
      //   return isLt2M;
      // }
      // return isJPG && isLt2M;
    },
    // 上传用户组图标
    uploaderLogo(e) {
      let logoFormData = new FormData();
      logoFormData.append("icon", e.file);
      // logoFormData.append("type", "icon");
      this.appFetch({
        url: "groupsIcon",
        splice:'/'+this.uploadUserId+'/icon',
        method: "post",
        data: logoFormData
      })
        .then(data => {
          if (data.errors) {
            this.$message.error(data.errors[0].code);
          } else {
            console.log(data,'这是上传得到的');
            this.tableData[this.uploadUserIndex]._data.icon = data.readdata._data.icon;
            // this.imageUrl = data.readdata._data.default.logo;
            this.$message({ message: "上传成功", type: "success" });
            // this.deleteBtn = true;
          }
        })
        .catch(error => {});
    },
    handleAvatarSuccess(res, file) {
      // this.imageUrl = URL.createObjectURL(file.raw);
    },
    handleFile() {
      console.log(id,'~~~~~~~~~');
    },


    // 删除用户组图标，物理删除
    deleteGroupsIcon(index,id){
      console.log(index,'~~~~~~~~',id,'删除');
      this.tableData[index]._data.icon = '';
      this.tableData[index]._data.isDisplay = false;
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
      this.btnLoading = true;
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
            "name": "",
            'default':''
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

        if (this.radioIndex + 1 === this.tableData.length){
          singleData.attributes.default = 1;
        }

        this.postGroups(singleData);
      } else {
        let data = [];
        this.tableData.forEach((item)=>{
          data.push({
            "attributes": {
              "name": item._data.name,
              'id': item._data.id,
              'isDisplay': item._data.isDisplay,
              'default': item._data.id == this.radio,
            },
          })
        });
        this.batchPatchGroup(data);
      }
    },

    singleDelete(index,id){
      if (index > this.alternateLength-1){
        this.tableData.pop();
        this.addStatus = false;
      } else {
        this.singleDeleteGroup(id);
      }
    },

    deleteClick(){
      this.delLoading = true;
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
          console.log(res,'getGroups的数据');
          this.tableData = res.readdata;
          this.alternateLength = res.readdata.length;
          this.tableData.forEach((item) => {
            this.groupName = item._data.isDisplay;
            console.log(this.groupName)
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
        this.btnLoading = false;
        if (res.errors){
          if (res.errors[0].detail){
            this.$message.error(res.errors[0].code + '\n' + res.errors[0].detail[0])
          } else {
            this.$message.error(res.errors[0].code);
          }
        } else {
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
        this.delLoading = false;
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
        this.btnLoading = false;
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
        this.btnLoading = false;
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
