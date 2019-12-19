/*
* 角色权限编辑
* */

import Card from '../../../../view/site/common/card/card';
import CardRow from '../../../../view/site/common/card/cardRow';

export default {
  data:function () {
    return {
      checked:[]
    }
  },
  methods:{
    /*
    * 权限列表中英文对应拿到后，在页面的label中对应填写
    * */


    submitClick(){
      console.log(this.checked);
      this.patchGroupPermission();
    },

    /*
    * 接口请求
    * */
    getGroupResource(){
      this.appFetch({
        url:"groups",
        method:'get',
        splice: '/' + this.$route.query.id,
        data:{}
      }).then(res=>{
        console.log(res);
        let data = res.readdata.permission;
        this.checked = [];
        data.forEach((item)=>{
          this.checked.push(item._data.permission)
        })

      }).catch(err=>{
        console.log(err);
      })
    },
    patchGroupPermission(){
      this.appFetch({
        url:'groupPermission',
        method:'post',
        data:{
          data: {
            "attributes": {
              "groupId": this.$route.query.id,
              "permissions": this.checked
            }
          }
        }
      }).then(res=>{
        console.log(res);
        if (res.errors){
          this.$message.error(res.errors[0].code);
        } else {
          this.$message({
            showClose: true,
            message: '提交成功',
            type: 'success'
          });
        }
      }).catch(err=>{
        console.log('错误');
        console.log(err);
      })
    }

  },
  created(){
    this.getGroupResource();
  },
  components:{
    Card,
    CardRow
  }
}
