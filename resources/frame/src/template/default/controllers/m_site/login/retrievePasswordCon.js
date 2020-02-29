/*
* 忘记密码控制器
* */

import retrievePWDHeader from '../../../view/m_site/common/loginSignUpHeader/loginSignUpHeader'
import retrievePWDFooter from '../../../view/m_site/common/loginSignUpFooter/loginSignUpFooter'

export default {
  data:function () {
    return {
      newpwd:"",
      verifyNum:"",
      phoneNum:"",
      type:'',
      btnContent:"获取验证码", //获取验证码按钮内文字
      time:1, //发送验证码间隔时间
      disabled:false, //按钮状态
      insterVal:'',
      isGray: false,
      btnLoading:false,
      data:{},
      payPassword:'',
      payPasswordConfirmation:'',
    }
  },

  components:{
    retrievePWDHeader,
    retrievePWDFooter
  },

  created(){
    if (this.$route.query.type && this.$route.query.type === 'forget') {
      this.type = 'reset_pay_pwd';
    }else {
      this.type = 'reset_pwd';
    }
  },

  methods:{
    //获取验证码
    forgetSendSmsCode(){
      var reg=11&& /^((13|14|15|17|18)[0-9]{1}\d{8})$/;//手机号正则验证
      var phoneNum = this.phoneNum;
      if(!phoneNum){//未输入手机号
       this.$toast("请输入手机号码");
       return;
      }
      if(!reg.test(phoneNum)){//手机号不合法
       this.$toast("您输入的手机号码不合法，请重新输入");
      }
      //获取验证码请求
      this.appFetch({
        url:"sendSms",
        method:"post",
        data:{
          "data": {
            "attributes": {
              mobile:this.phoneNum,
              type:this.type
            }
          }
        }
      }).then(res => {
        if (res.errors){
          this.$toast.fail(res.errors[0].code);
        } else {
          this.insterVal = res.data.attributes.interval;
          this.time = this.insterVal;
          this.timer();
        }
       });
    },
    timer(){
      if(this.time>1){
       this.time--;
       this.btnContent = this.time+"s后重新获取";
       this.disabled = true;
       var timer = setTimeout(this.timer,1000);
       this.isGray = true;
      }else if(this.time == 1){
       this.btnContent = "获取验证码";
       clearTimeout(timer);
       this.disabled = false;
       this.isGray = false;
      }
    },
    //提交新密码
    submissionPassword(){
      this.btnLoading = true;

      let data = {
        "attributes": {
          "mobile": this.phoneNum,
          "code": this.verifyNum,
          "type": this.type,
        }
      };

      if (this.type === 'reset_pay_pwd') {
        data.attributes.pay_password = this.payPassword;
        data.attributes.pay_password_confirmation = this.payPasswordConfirmation;
      } else if (this.type === 'reset_pwd') {
        data.attributes.password=this.newpwd;
      }


      this.appFetch({
        url:"smsVerify",
        method:"post",
        data:{
          "data":{
            data:data
          }
        }
      }).then(res => {
        this.btnLoading = false;
        if (res.errors){
          this.$toast.fail(res.errors[0].code);
        } else {
          this.$router.push({
            path: 'login-user',
          });
          this.$toast.success('提交成功');
        }
       });
    }
  }
}
