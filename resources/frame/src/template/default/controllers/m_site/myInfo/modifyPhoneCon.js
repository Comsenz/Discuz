/**
 * 修改手机号
 */


import ModifyHeader from '../../../view/m_site/common/loginSignUpHeader/loginSignUpHeader'


export default {
  data:function () {
    return {
      phoneNum:'15611727257',
      password:'',
      sms:'',
      newphone:'',
      modifyState:true,
      bind:'修改手机号',
      time:1, //发送验证码间隔时间
      insterVal:'',
      isGray: false
    }
  },

  components:{
    ModifyHeader
  },

  mounted(){
  
  },
  methods:{
    nextStep(){     //点击下一步验证短信验证码
      this.modifyState=!this.modifyState;
      this.appFetch({
        url:"smsVerify",
        method:"post",
        data:{
          "data": {
            "attributes": {
              "mobile": "this.phoneNum",
              "code": "this.verifyNum",
               "type":"this.bind"
            }
          }
        }
      }).then(res => {
          // console.log(res)
       });
    },
    sendSmsCodePhone(){      //修改手机号
      var reg=11&& /^((13|14|15|17|18)[0-9]{1}\d{8})$/;//手机号正则验证
      var phoneNum = this.phoneNum;
      if(!phoneNum){//未输入手机号
       this.$toast("请输入手机号码");
       return;
      }
      if(!reg.test(phoneNum)){//手机号不合法
       this.$toast("您输入的手机号码不合法，请重新输入");
      }

      this.appFetch({
        url:'sendSms',
        method:'post',
        data:{
          "data": {
            "attributes": {
              'mobile':'this.phoneNum',
              'type':'this.bind'
            }
          }
        }
      }).then((res)=>{
        // console.log(res);
        this.insterVal = res.data.attributes.interval;
        // console.log(this.insterVal+'555555');
        this.time = this.insterVal;
        this.timer();
      })
    }
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
   // 验证验证码
    fillContent(){
      // console.log("fillContent");
    },
    // bindNewPhone(){
    //   this.appFetch({
    //     url:"smsVerify",
    //     method:"post",
    //     data:{
    //       "data": {
    //         "attributes": {
    //           "mobile": "this.phoneNum",
    //           "code": "this.verifyNum",
    //            "type":"绑定手机号"
    //         }
    //       }
    //     }
    //   }).then(res => {
    //       // console.log(res)
    //    });
    // }
}
