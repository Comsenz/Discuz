
import Card from '../../../view/site/common/card/card';
import CardRow from '../../../view/site/common/card/cardRow';

export default {
  data:function () {
    return {
      checked:'',
      register_validate:'',   //注册审核
      pwdLength:'',           //密码长度
      checkList:[],           //密码规则
      register_captcha:'',    //验证码开始
      disabled:true,            //是否可以开启验证码
      register_type: 0,      // 注册模式
      qcloud_sms: true,
      qcloud_wx: true,
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
          // this.pwdLength = res.readdata._data.setreg.password_length
          this.checked = res.readdata._data.set_reg.register_close;
          this.register_validate = res.readdata._data.set_reg.register_validate;
          this.pwdLength = res.readdata._data.set_reg.password_length;
          this.checkList = res.readdata._data.set_reg.password_strength;
          this.register_captcha = res.readdata._data.set_reg.register_captcha;
          this.register_type = res.readdata._data.set_reg.register_type;
          if(res.readdata._data.qcloud.qcloud_sms == true) {
            this.qcloud_sms = false
          }
          if(res.readdata._data.passport.offiaccount_close == true) {
            this.qcloud_wx = false
          }
          if(res.readdata._data.qcloud.qcloud_captcha == true){
            this.disabled = false
          }
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
                "key":'register_captcha',
                "value":this.register_captcha,
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
            },
            {
              "attributes":{
                "key":'register_type',
                "value":this.register_type,
                "tag": 'default'
               }
            },
           ]
        }
      }).then(data=>{
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
