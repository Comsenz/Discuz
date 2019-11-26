
import retrievePWDHeader from '../../../view/m_site/common/loginSignUpHeader/loginSignUpHeader'
import retrievePWDFooter from '../../../view/m_site/common/loginSignUpFooter/loginSignUpFooter'


export default {
  data:function () {
    return {
      newpwd:"",
      verifyNum:"",
      phoneNum:"18237597790",
      logpwd:' logpwd',
      btnContent:"获取验证码", //获取验证码按钮内文字
      time:1, //发送验证码间隔时间
      disabled:false, //按钮状态
      insterVal:'',
      isGray: false
    }
  },

  components:{
    retrievePWDHeader,
    retrievePWDFooter
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
      // 获取验证码请求
      this.appFetch({
        url:"sendSms",
        method:"post",
        data:{
          "data": {
            "attributes": {
              mobile:this.phoneNum,
              type:this.logpwd
            }
          }
        }
      }).then(res => {
          // console.log(res);
          var time = this.time;
          this.insterVal = res.data.attributes.interval;
          // console.log(this.insterVal+'555555');
          this.time = this.insterVal;
          this.timer();
       });
    },
    timer(){
      // alert('执行');
      if(this.time>1){
        // alert('2222');
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
      this.appFetch({
        url:"smsVerify",
        method:"post",
        data:{
          "data": {
            "attributes": {
              "mobile": this.phoneNum,
              "code": this.verifyNum,
              "type": this.logpwd
            }
          }
        }
      }).then(res => {
          // console.log(res);
          this.$router.push({
            path:'circle',
          });

       });
    }
  }
}
