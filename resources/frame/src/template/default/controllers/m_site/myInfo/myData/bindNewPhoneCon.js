/**
 * 修改页面里的绑定新手机号
 */


import ModifyHeader from '../../../../view/m_site/common/loginSignUpHeader/loginSignUpHeader';
import browserDb from '../../../../../../helpers/webDbHelper';


export default {
  data: function () {
    return {
      sms: '',
      newphone: '',
      //   modifyState: true,
      bind: 'bind',
      time: 1, //发送验证码间隔时间
      insterVal: '',
      isGray: false,
      btnContent: '发送验证码',
      mobileConfirmed: '',//验证验证码是否正确
      backGo: 1,
      disabled: false,
      modifyPhone: '',       //用户手机号
      titlePhone: '',     //标题
      headerShow: false,
      btnLoading: false   //按钮loading状态
    }
  },

  components: {
    ModifyHeader
  },

  mounted() {

  },
  created() {
    this.userPhone()
  },
  methods: {
    userPhone() {
      this.$store.dispatch("appSiteModule/loadUser").then(res => {
        if (res.errors) {
          this.$toast.fail(res.errors[0].code);
        } else {
          this.modifyPhone = res.readdata._data.originalMobile;         //用户手机号
          if (this.modifyPhone) {
            this.titlePhone = '修改手机号'
          } else {
            this.titlePhone = '绑定新手机号'
          }
          this.headerShow = true
        }
      }).catch(() => {
      });
    },

    //获取验证码
    sendSmsCodePhone() {
      var reg = 11 && /^((13|14|15|16|17|18|19)[0-9]{1}\d{8})$/;//手机号正则验证
      var newphone = this.newphone;
      if (!newphone) {//未输入手机号
        this.$toast("请输入手机号码");
        return;
      }
      if (!reg.test(newphone)) {//手机号不合法
        this.$toast("您输入的手机号码不合法，请重新输入");
      } else {
        // 获取验证码请求
        var bind = 'bind';
        var rebind = 'rebind';
        var typeBind;
        if (this.modifyPhone == '') {
          typeBind = bind
        } else {
          typeBind = rebind
        }
        this.appFetch({
          url: "sendSms",
          method: "post",
          data: {
            "data": {
              "attributes": {
                mobile: this.newphone,
                type: typeBind
              }
            }
          }
        }).then(res => {
          if (res.errors) {
            if (res.errors[0].detail) {
              this.$toast.fail(res.errors[0].code + '\n' + res.errors[0].detail[0])
            } else {
              this.$toast.fail(res.errors[0].code);
            }
            // this.$toast.fail(res.errors[0].code + '\n' + res.errors[0].detail);
            // throw new Error(res.error)
          } else {
            this.insterVal = res.data.attributes.interval;
            this.time = this.insterVal;
            this.timer();
          }
        });
      }


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

    bindNewPhone() { //修改新的手机号后提交验证码
      this.btnLoading = true;

      if (this.newphone === '') {
        this.$toast("手机号码不能为空，请重新输入");
        this.btnLoading = false;
        return;
      }

      if (this.sms === '') {
        this.$toast("验证码不能为空");
        this.btnLoading = false;
        return;
      }

      var bind = 'bind';
      var rebind = 'rebind';
      var typeBind;
      if (this.modifyPhone == '') {
        typeBind = bind
      } else {
        typeBind = rebind
      }

      this.appFetch({
        url: "smsVerify",
        method: "post",
        data: {
          "data": {
            "attributes": {
              "mobile": this.newphone,
              "code": this.sms,
              'type': typeBind
            }
          }
        }
      }).then(res => {
        this.btnLoading = false;
        if (res.errors) {
          this.$toast.fail(res.errors[0].code);
          throw new Error(res.error)
        } else {
          if (this.newphone === res.readdata._data.originalMobile) {
            this.$store.dispatch("appSiteModule/invalidateUser");
            this.$toast("手机号绑定成功");
            this.$router.push({ path: '/modify-data', query: { backGo: this.backGo } });
          }
        }

      }).catch((err) => {
        this.btnLoading = false;
        this.$toast("手机号绑定失败，请重试");
      });
    }

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

  beforeRouteEnter(to, from, next) {

    next(vm => {
      if (from.name === 'modify-phone') {
        vm.backGo = -4
      } else {
        vm.backGo = -3
      }
    })
  }

}
