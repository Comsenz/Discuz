
import BindPhoneHeader from '../../../view/m_site/common/loginSignUpHeader/loginSignUpHeader'
import BindPhoneFooter from '../../../view/m_site/common/loginSignUpFooter/loginSignUpFooter'


export default {
  data:function () {
    return {
      phoneNum:"13524405426", //手机号
      verifyNum:"", //验证码
      btnContent:"获取验证码", //获取验证码按钮内文字
      time:1, //发送验证码间隔时间
      disabled:false, //按钮状态
      bind:'bind'
    }
  },

  components:{
    BindPhoneHeader,
    BindPhoneFooter
  },
  created:function(){
    console.log(this.time);
  },
  methods:{
    //获取验证码
    sendSmsCode(){
      let time = this.time;
      var reg=11&& /^((13|14|15|17|18)[0-9]{1}\d{8})$/;//手机号正则验证
      var phoneNum = this.phoneNum;
      if(!phoneNum){//未输入手机号
       this.$toast("请输入手机号码");
       return;
      }
      if(!reg.test(phoneNum)){//手机号不合法
       this.$toast("您输入的手机号码不合法，请重新输入");
      }
      this.time = 6;
      console.log(time);
      this.timer();
      // 获取验证码请求
      this.appFetch({
        url:"sendSms",
        method:"post",
        data:{
          "data": {
            "attributes": {
              mobile:this.phoneNum,
              type:this.bind
            }
          }
        }
      },(res)=>{
        console.log(res);
        if (res.status === 200){
          this.aaa = res.interval
        } else{
          console.log('400');
        }

      },(err)=>{
        alert('45656');
        // console.log(err);
      })

      // var url = 'http:2020.comsenz.com/api/sms/send';
      // this.$http.post(url,{mobile:phoneNum},{type:bind}).then((response)=>{
      //  console.log(response.body);
      // });
    },
    timer(){
      if(this.time>1){
       this.time--;
       this.btnContent = this.time+"s后重新获取";
       this.disabled = true;
       var timer = setTimeout(this.timer,1000);
      }else if(this.time == 1){
       this.btnContent = "获取验证码";
       clearTimeout(timer);
       this.disabled = false;
      }
    },
    // 验证验证码
    verificationCode(){
      var phoneNum = this.phoneNum;//手机号
      var verifyNum = this.verifyNum;//验证码
      var url = 'http://bosstan.asuscomm.com/api/common/verificationCode';
      this.$http.post(url,{
       username:phoneNum,
       code:verifyNum
      },{
       emulateJSON:true
      }).then((response)=>{
       console.log(response.body);
      });
    },
     fillContent(){
      // console.log("fillContent");
    }














  }

}
