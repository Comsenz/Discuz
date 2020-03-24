import SignUpHeader from '../../../view/m_site/common/loginSignUpHeader/loginSignUpHeader'
import SignUpFooter from '../../../view/m_site/common/loginSignUpFooter/loginSignUpFooter'
import browserDb from "../../../../../helpers/webDbHelper";

export default {
  data: function () {
    return {
      username: '',
      password: '',
      signReason: '',        //注册原因
      signReasonStatus: false,
      btnLoading: false,     //注册按钮状态
      error: false,          //错误状态
      errorMessage: "",      //错误信息

      phoneStatus: '',       //绑定手机号状态
      siteMode: '',          //站点是否付费
      signUpShow: true,      //触发弹出验证码的
      appID: '',             //腾讯云验证码场景 id
      captcha: null,         //腾讯云验证码实例
      captcha_ticket: '',    //腾讯云验证码返回票据
      captcha_rand_str: '',   //腾讯云验证码返回随机字符串
      code: '',               //从邀请码链接进来时存的code
      password_length: '',    //密码长度
      password_strength: '',   //密码强度
      passwordStrengthRegex: [
        {
          'name': '数字',
          'pattern': '\\d+',
        },
        {
          'name': '小写字母',
          'pattern': '[a-z]+',
        },
        {
          'name': '符号',
          'pattern': '[^a-zA-z0-9]+',
        },
        {
          'name': '大写字母',
          'pattern': '[A-Z]+',
        },
      ]
    }
  },

  components: {
    SignUpHeader,
    SignUpFooter
  },
  methods: {
    signUpClick() {
      this.btnLoading = true;

      if (this.signReasonStatus) {
        if (this.signReason.length < 1) {
          this.$toast.fail('请填写注册原因！');
          this.btnLoading = false;
        } else {
          this.setSignData();
        }
      } else {
        this.setSignData();
      }
    },
    //错误提示
    clearError(str) {
      switch (str) {
        case 'clear':
          this.error = false;
          this.errorMessage = "";
          break;
        case 'blur':
          if (this.password !== '') {
            this.error = true;
          }
          break;
        default:
          this.error = false;
      }
    },

    /*
    * 接口请求
    * */
    getForum() {
      return this.appFetch({
        url: 'forum',
        method: 'get',
        data: {}
      }).then(res => {
        if (res.errors) {
          if (res.errors[0].detail) {
            this.$toast.fail(res.errors[0].code + '\n' + res.errors[0].detail[0])
          } else {
            this.$toast.fail(res.errors[0].code);
          }
        } else {
          this.phoneStatus = res.readdata._data.qcloud.qcloud_sms;
          this.siteMode = res.readdata._data.set_site.site_mode;
          this.signReasonStatus = res.readdata._data.set_reg.register_validate;
          this.appID = res.readdata._data.qcloud.qcloud_captcha_app_id;
          this.password_length = res.readdata._data.set_reg.password_length;
          this.password_strength = res.readdata._data.set_reg.password_strength;
          // console.log(this.password_strength)
          browserDb.setLItem('siteInfo', res.readdata);
          if (res.readdata._data.qcloud.qcloud_captcha && res.readdata._data.set_reg.register_captcha) {
            this.signUpShow = false
          }
        }
      }).catch(err => {
      })
    },
    setSignData() {
      this.appFetch({
        url: 'register',
        method: 'post',
        data: {
          "data": {
            "type": "users",
            "attributes": {
              username: this.username,
              password: this.password,
              register_reason: this.signReason,
              captcha_ticket: this.captcha_ticket,
              captcha_rand_str: this.captcha_rand_str,
              code: this.code
            },
          }
        }
      }).then(res => {
        this.btnLoading = false;

        this.getForum().then(() => {
          if (res.errors) {
            if (res.errors[0].detail) {
              this.$toast.fail(res.errors[0].code + '\n' + res.errors[0].detail[0])
            } else {
              if (res.rawData[0].code === 'register_validate') {
                this.$router.push({ path: "information-page", query: { setInfo: 'registrationReview' } })
              } else {
                this.$toast.fail(res.errors[0].code);
              }
            }
          } else {
            this.$toast.success('注册成功');
            let token = res.data.attributes.access_token;
            let tokenId = res.data.id;
            let refreshToken = res.data.attributes.refresh_token;
            browserDb.setLItem('Authorization', token);
            browserDb.setLItem('tokenId', tokenId);
            browserDb.setLItem('refreshToken', refreshToken);

            if (this.phoneStatus) {
              this.$router.push({ path: 'bind-phone' });
            } else if (this.siteMode === 'pay') {
              this.$router.push({ path: 'pay-the-fee' });
            } else if (this.siteMode === 'public') {
              this.$router.push({ path: '/' });
            } else {
              //缺少参数，请刷新页面
            }

          }
        })
      }).catch(err => {
      })
    },
    //验证码
    initCaptcha() {
      this.btnLoading = true;

      if (this.username === '') {
        this.$toast("用户名不能为空");
        this.btnLoading = false;
        return;
      }
      if (this.password === '') {
        this.$toast("密码不能为空");
        this.btnLoading = false;
        return;
      }
      if (this.password.length < this.password_length) {
        this.$toast(`密码至少为${this.password_length}个字符`);
        this.btnLoading = false;
        return;
      }

      let regFlag = true;
      this.password_strength.forEach(v => {
        if (!this.verification(this.passwordStrengthRegex[v])) {
          regFlag = false;
        }
      });

      this.captcha = new TencentCaptcha(this.appID, res => {
        if (res.ret === 0) {
          this.captcha_ticket = res.ticket;
          this.captcha_rand_str = res.randstr;
          //验证通过后注册
          this.setSignData();
        }
        if (res.ret === 2){
          this.btnLoading = false;
        }
      });
      // 显示验证码
      this.captcha.show();
    },

    //验证密码规则
    verification(regxInfo) {
      const reg = new RegExp(regxInfo.pattern, 'g');
      if (!reg.test(this.password)) {
        this.$toast(`密码中必须包含${regxInfo.name}`);
        return false;
      }
    },
  },


  created() {
    this.getForum();
    this.code = browserDb.getSItem('code');
  },
  beforeRouteLeave(to, from, next) {
    // 隐藏验证码
    if (this.captcha) {
      this.captcha.destroy();
    }
    //清空code
    if (this.code) {
      browserDb.removeSItem('code');
    }
    next();
  }
}
