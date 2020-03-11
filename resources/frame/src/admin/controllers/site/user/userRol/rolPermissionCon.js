/*
* 角色权限编辑
* */

import Card from '../../../../view/site/common/card/card';
import CardRow from '../../../../view/site/common/card/cardRow';

export default {
  data:function () {
    return {
      checked:[],
      disabled:false,  //是否可以开启验证码
    }
  },
  methods:{
    signUpSet(){
      this.appFetch({
        url:'forum',
        method:'get',
      }).then(res=>{
        if (res.errors){
          this.$message.error(res.errors[0].code);
        }else {
          if(res.readdata._data.qcloud.qcloud_captcha == false){
            this.disabled = true
          }
        }
      })
    },
    /*
    * 权限列表中英文对应拿到后，在页面的label中对应填写
    * */


    submitClick(){
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
        data:{
          include:['permission']
        }
      }).then(res=>{
        if (res.errors){
          this.$message.error(res.errors[0].code);
        }else {
          let data = res.readdata.permission;
          this.checked = [];
          data.forEach((item) => {
            this.checked.push(item._data.permission)
          })
        }

      }).catch(err=>{
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
      })
    }

  },
  created(){
    this.getGroupResource();
    this.signUpSet()
  },
  components:{
    Card,
    CardRow
  }
}
