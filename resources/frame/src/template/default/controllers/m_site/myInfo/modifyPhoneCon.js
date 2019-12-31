/**
 * 修改手机号
 */


import ModifyHeader from '../../../view/m_site/common/loginSignUpHeader/loginSignUpHeader';
import browserDb from '../../../../../helpers/webDbHelper';


export default {
  data: function () {
    return {
      phoneNum: '',
      password: '',
      sms: '',
      newphone: '',
      modifyState: true,
      bind: 'bind',
      time: 1, //发送验证码间隔时间
      insterVal: '',
      isGray: false,
      btnContent:'发送验证码',
      mobileConfirmed:''
    }
  },

  components: {
    ModifyHeader
  },

  mounted() {
    this.userInformation() //用户信息
  },
  methods: {
    userInformation() {
      var userId = browserDb.getLItem('tokenId');
      this.appFetch({
        url:'users',
        method:'get',
        splice:'/'+userId,
        data:{

        }
      }).then(res=>{
        this.phoneNum = res.data.attributes.mobile
      })
    },
    sendSmsCodePhone() { //发送验证码
      console.log('11111111111111')
      // var reg = 11 && /^((13|14|15|17|18)[0-9]{1}\d{8})$/; //手机号正则验证
      // var newphone = this.newphone;
      // if (!newphone) { //未输入手机号
      //   this.$toast("请输入手机号码");
      //   return;
      // }
      // if (!reg.test(newphone)) { //手机号不合法
      //   this.$toast("您输入的手机号码不合法，请重新输入");
      // }
      var modifyState = this.modifyState
      if (modifyState) {
        this.appFetch({
          url: 'sendSms',
          method: 'post',
          data: {
            "data": {
              "attributes": {
                // 'mobile': this.phoneNum,
                'type': 'verify'
              }
            }
          }
        }).then((res) => {
          console.log(res);
          this.insterVal = res.data.attributes.interval;
          this.time = this.insterVal;
          this.timer();
        })
      } else {
        this.appFetch({
          url: 'sendSms',
          method: 'post',
          data: {
            "data": {
              "attributes": {
                'mobile': this.newphone,
                'type': this.bind,
                'code': this.sms
              }
            }
          }
        }).then((res) => {
          console.log(res);
          this.insterVal = res.data.attributes.interval;
          this.time = this.insterVal;
          this.timerNext();
        })
      }

    },
    nextStep() { //点击下一步验证短信验证码
      if (this.phoneNum === '') {
        this.$toast("手机号码不能为空，请重新输入");
        return;
      }

      if (this.sms === '') {
        this.$toast("验证码不能为空");
        return;
      }

      this.appFetch({
        url: "smsVerify",
        method: "post",
        data: {
          "data": {
            "attributes": {
              "mobile": this.phoneNum,
              "code": this.sms,
              "type": 'verify'
            }
          }
        }
      }).then(res => {
        this.sms = '';
        this.modifyState = !this.modifyState;
        this.mobileConfirmed = res.readdata._data.mobileConfirmed
        if(this.mobileConfirmed ==true){
          this.$toast("手机号验证成功");
          this.$router.push({path:'/bind-new-phone'});
        }
      }).catch((err) => {
        this.$toast("手机号验证失败，请重试");
      })
    },

    timer() {
      // alert('执行');
      if (this.time > 1) {
        // alert('2222');
        this.time--;
        this.btnContent = this.time + "s后重新获取";
        this.disabled = true;
        var timer = setTimeout(this.timer, 1000);
        this.isGray = true;
      } else if (this.time == 1) {
        this.btnContent = "获取验证码";
        clearTimeout(timer);
        this.disabled = false;
        this.isGray = false;
      }
    },

    //   // alert('执行');
    //   if (this.time > 1) {
    //     // alert('2222');
    //     this.time--;
    //     this.btnContent = this.time + "s后重新获取";
    //     this.disabled = true;
    //     var timer = setTimeout(this.timer, 1000);
    //     this.isGray = true;
    //   } else if (this.time == 1) {
    //     this.btnContent = "获取验证码";
    //     clearTimeout(timer);
    //     this.disabled = false;
    //     this.isGray = false;
    //   }
    // },

    bindNewPhone() { //修改新的手机号后提交验证码

      if (this.phoneNum === '') {
        this.$toast("手机号码不能为空，请重新输入");
        return;
      }

      if (this.sms === '') {
        this.$toast("验证码不能为空");
        return;
      }

      this.appFetch({
        url: "smsVerify",
        method: "post",
        data: {
          "data": {
            "attributes": {
              "mobile": this.newphone,
              "code": this.sms,
              'type': this.bind
            }
          }
        }
      }).then(res => {
        this.mobileConfirmed =res.readdata._data.mobileConfirmed;
        if(this.mobileConfirmed == true){
          this.$toast("手机号修改成功");
          this.$router.push({path:'../view/m_site/home/circleView'});
        }
      }).catch((err)=>{
        this.$toast("手机号修改失败，请重试");
      });
    }
  },

}
