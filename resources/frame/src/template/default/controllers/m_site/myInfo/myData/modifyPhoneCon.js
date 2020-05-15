/**
 * 修改手机号
 */


import ModifyHeader from '../../../../view/m_site/common/loginSignUpHeader/loginSignUpHeader';
import browserDb from '../../../../../../helpers/webDbHelper';


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
      btnContent: '发送验证码',
      mobileConfirmed: '',
      loading: false, //loading状态
      disabled: false, //按钮状态
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
        url: 'users',
        method: 'get',
        splice: '/' + userId,
        data: {

        }
      }).then(res => {
        if (res.errors) {
          this.$toast.fail(res.errors[0].code);
        } else {
          this.phoneNum = res.data.attributes.mobile
        }
      })
    },
    sendSmsCodePhone() { //发送验证码
      var modifyState = this.modifyState
      if (modifyState) {
        // this.loading = true;
        this.appFetch({
          url: 'sendSms',
          method: 'post',
          data: {
            "data": {
              "attributes": {
                'type': 'verify'
              }
            }
          }
        }).then((res) => {
          if (res.errors) {
            if (res.errors[0].detail) {
              this.$toast.fail(res.errors[0].code + '\n' + res.errors[0].detail[0])
            } else {
              this.$toast.fail(res.errors[0].code);
            }
          } else {
            this.insterVal = res.data.attributes.interval;
            this.time = this.insterVal;
            this.timer();
          }
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
          if (res.errors) {
            if (res.errors[0].detail) {
              this.$toast.fail(res.errors[0].code + '\n' + res.errors[0].detail[0])
            } else {
              this.$toast.fail(res.errors[0].code);
            }
          } else {
            this.insterVal = res.data.attributes.interval;
            this.time = this.insterVal;
            this.timer();
          }
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
      this.loading = true;
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
        this.loading = false;
        if (res.errors) {
          this.$toast.fail(res.errors[0].code);
        } else {
          this.sms = '';
          this.modifyState = !this.modifyState;
          this.time = 1;
          this.bind = "rebind";
        }
      })
      // .catch((err) => {
      //   this.$toast("手机号验证失败，请重试");
      // })
    },

    timer() {
      if (this.time > 1) {
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
      this.loading = true;

      if (this.phoneNum === '') {
        this.$toast("手机号码不能为空，请重新输入");
        return;
      }

      if (this.sms === '') {
        this.$toast("验证码不能为空");
        return;
      }
      this.loading = true;
      this.appFetch({
        url: "smsVerify",
        method: "post",
        data: {
          "data": {
            "attributes": {
              "mobile": this.newphone,
              "code": this.sms,
              'type': 'rebind'
            }
          }
        }
      }).then(res => {
        this.loading = false;
        if (res.errors) {
          if (res.errors[0].detail) {
            this.$toast.fail(res.errors[0].code + '\n' + res.errors[0].detail[0])
          } else {
            this.$toast.fail(res.errors[0].code);
          }
        } else {
          if (this.newphone === res.readdata._data.originalMobile) {
            this.$store.dispatch("appSiteModule/invalidateUser");
            this.$toast("手机号修改成功");
            this.$router.push({ path: '/modify-data' });
          }
        }
      })
      // .catch((err)=>{
      //   this.$toast("手机号修改失败，请重试");
      // });
    }
  },

}
