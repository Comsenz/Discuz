
import Card from '../../../view/site/common/card/card';
import CardRow from '../../../view/site/common/card/cardRow';

export default {
  data:function () {
    return {
      checked:'',
      register_validate:'',   //注册审核
      pwdLength:'',           //密码长度
      checkList:[],           //密码规则
    }
  },
  created(){
    this.signUpSet()//获取前台信息
  },
  methods:{
    signUpSet(){
      this.appFetch({
        url:'forum',
        method:'get',
        data:{

        }
      }).then(res=>{
        if (res.errors){
          this.$message.error(res.errors[0].code);
        }else {
          console.log(res,'密码密码')
          // this.pwdLength = res.readdata._data.setreg.password_length
          this.checked = res.readdata._data.set_reg.register_close;
          this.register_validate = res.readdata._data.set_reg.register_validate;
          this.pwdLength = res.readdata._data.set_reg.password_length;
          this.checkList = res.readdata._data.set_reg.password_strength;
          console.log(this.checkList)
          // console.log(res)
        }
      })
    },
    submission(){ //提交注册信息接口
      var reg = /^\d+$|^\d+[.]?\d+$/;
      var pwdLength = this.pwdLength;
      var passwordStrength = this.checkList.join(",")
      // if(pwdLength === ''){
      //   return
      // }
      // if (!reg.test(pwdLength)) { //密码只能输入数字
      //   this.$message("密码只能输入数字");
      //   return
      // }
      this.appFetch({
        url:'settings',
        method:'post',
        data:{
          "data":[
            {
             "attributes":{
              "key":'register_close',
              "value":this.checked,
              "tag": 'default'
             }
            },
            {
              "attributes":{
                "key":'register_validate',
                "value":this.register_validate,
                "tag": 'default'
              }
            },
            {
              "attributes":{
                "key":'password_length',
                "value":this.pwdLength,
                "tag": 'default'
               }
            },
            {
              "attributes":{
                "key":'password_strength',
                "value":passwordStrength,
                "tag": 'default'
               }
            }
           ]
          // "register_close": this.checked,
          // "password_length":this.pwdLength,

        }
      }).then(data=>{
        console.log(data)
        if (data.errors){
          if (data.errors[0].detail){
            this.$message.error(data.errors[0].code + '\n' + data.errors[0].detail[0])
          } else {
            this.$message.error(data.errors[0].code);
          }
          // this.$message.error(data.errors[0].code);
        }else {
          this.$message({message: '提交成功', type: 'success'});
        }
      })

    }
  },
  components:{
    Card,
    CardRow
  }
}
