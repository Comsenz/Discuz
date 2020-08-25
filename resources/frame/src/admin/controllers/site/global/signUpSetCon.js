
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
      privacy: "0", //隐私协议
      register: "0", //用户协议
      register_content:'',
      privacy_content:'',
      registerFull: false,
      privacyFull: false,
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
          'filter[tag]': 'agreement'
        }
      }).then(res=>{
        if (res.errors){
          this.$message.error(res.errors[0].code);
        }else {
          const agreement = res.readdata._data.agreement;
          // this.pwdLength = res.readdata._data.setreg.password_length
          this.checked = res.readdata._data.set_reg.register_close;
          this.register_validate = res.readdata._data.set_reg.register_validate;
          this.pwdLength = res.readdata._data.set_reg.password_length;
          this.checkList = res.readdata._data.set_reg.password_strength;
          this.register_captcha = res.readdata._data.set_reg.register_captcha;
          this.register_type = res.readdata._data.set_reg.register_type;
          this.privacy = agreement.privacy ? "1" : "0";
          this.register = agreement.register ? "1" : "0";
          this.register_content = agreement.register_content;
          this.privacy_content = agreement.privacy_content;
          if(res.readdata._data.qcloud.qcloud_sms == true) {

            this.qcloud_sms = false
          }
          if(res.readdata._data.passport.offiaccount_close == true || res.readdata._data.passport.miniprogram_close == true) {
            this.qcloud_wx = false
          }
          if(res.readdata._data.qcloud.qcloud_captcha == true){
            this.disabled = false
          }
        }
      })
    },
    changeRegister(register) {
      this.register = register;
      if(register==='0') {
        this.register_content = '';
      }
    },
    changePrivacy(privacy) {
      this.privacy = privacy;
      if(privacy==='0') {
        this.privacy_content = '';
      }
    },
    changeSize(obj){
       this[obj]= !this[obj];
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
              attributes: {
                key: "privacy",
                value: this.privacy,
                tag: "agreement"
              }
            },
            {
              attributes: {
                key: "register",
                value: this.register,
                tag: "agreement"
              }
            },
            {
              attributes: {
                key: "register_content",
                value: this.register_content ? this.register_content : "",
                tag: "agreement"
              }
            },
            {
              attributes: {
                key: "privacy_content",
                value: this.privacy_content ? this.privacy_content : "",
                tag: "agreement"
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
