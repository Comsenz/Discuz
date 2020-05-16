/*
* 微信注册绑定管理器
* */

import LoginHeader from '../../../view/m_site/common/loginSignUpHeader/loginSignUpHeader'
import LoginFooter from '../../../view/m_site/common/loginSignUpFooter/loginSignUpFooter'
import webDb from '../../../../../helpers/webDbHelper';
import appCommonH from "../../../../../helpers/commonHelper";

export default {
  data: function () {
    return {
      userName: "",
      password: "",
      phoneStatus: "",
      siteMode: '',
      wxtoken: '',
      platform: '',
      signReason: '',        //注册原因
      signReasonStatus: false,
      signUpBdClickShow: true, // 是否触发验证码按钮, true的时候不显示验证码
      appID: '',               // 腾讯云验证码场景 id
      captcha: null,           // 腾讯云验证码实例
      captcha_ticket: '',      // 腾讯云验证码返回票据
      captcha_rand_str: '',    // 腾讯云验证码返回随机字符串
      btnLoading: false,        //注册按钮状态
      registerClose: true,      // true的时候能注册
      showSignupInput: false,   // true时显示用户名/密码输入框
      redirectMessage: "正在跳转...",
    }
  },

  components: {
    LoginHeader,
    LoginFooter
  },

  methods: {
    signUpBdClick() {
      this.btnLoading = true;

      if (this.userName === '') {
        this.$toast("用户名不能为空");
        this.btnLoading = false;
        return;
      }
      if (this.password === '') {
        this.$toast("密码不能为空");
        this.btnLoading = false;
        return;
      }

      if (this.signReasonStatus) {
        if (this.signReason.length < 1) {
          this.$toast.fail('请填写注册原因！');
        } else {
          this.setSignData();
        }
      } else {
        this.setSignData();
      }


    },

    //验证码
    initCaptcha() {
      if (this.userName === '') {
        this.$toast("用户名不能为空");
        return;
      }
      if (this.password === '') {
        this.$toast("密码不能为空");
        return;
      }
      this.captcha = new TencentCaptcha(this.appID, res => {
        if (res.ret === 0) {
          this.captcha_ticket = res.ticket;
          this.captcha_rand_str = res.randstr;
          //验证通过后注册
          this.setSignData();
        }
      });
      // 显示验证码
      this.captcha.show();
    },

    /*
    * 接口请求
    * */
    getForum() {
      return this.$store.dispatch("appSiteModule/loadForum").then(res => {
        if (res.errors) {
          this.$toast.fail(res.errors[0].code);
        } else {
          this.appID = webDb.getLItem('siteInfo')._data.qcloud.qcloud_captcha_app_id;
          let qcloud_captcha = webDb.getLItem('siteInfo')._data.qcloud.qcloud_captcha;
          let register_captcha = webDb.getLItem('siteInfo')._data.set_reg.register_captcha;
          if (qcloud_captcha && register_captcha) {
            this.signUpBdClickShow = false
          }
          this.phoneStatus = res.readdata._data.qcloud.qcloud_sms;
          this.siteMode = res.readdata._data.set_site.site_mode;
          this.signReasonStatus = res.readdata._data.set_reg.register_validate;
          this.registerClose = res.readdata._data.set_reg.register_close;
        }
      }).catch(err => {
        console.log(err);
      })
    },
    setSignData(error_callback) {
      this.appFetch({
        url: 'register',
        method: 'post',
        data: {
          "data": {
            "type": "users",
            "attributes": {
              username: this.userName,
              password: this.password,
              token: this.wxtoken,
              platform: this.platform,
              register_reason: this.signReason,
              captcha_ticket: this.captcha_ticket,
              captcha_rand_str: this.captcha_rand_str
            },
          }
        }
      }).then(res => {
        this.btnLoading = false;
        if (res.errors) {
          if (res.errors[0].detail) {
            if (error_callback) {
              error_callback(res);
              return;
            }
            this.showSignupInput = true;
            this.$toast.fail(res.errors[0].code + '\n' + res.errors[0].detail[0])
          } else {
            if (res.rawData[0].code === 'register_validate') {
              this.$router.push({ path: "information-page", query: { setInfo: 'registrationReview' } })
            } else {
              if (error_callback) {
                error_callback(res);
                return;
              }
              this.showSignupInput = true;
              this.$toast.fail(res.errors[0].code);
            }
          }
        } else {
          this.$toast.success('注册成功');
          let token = res.data.attributes.access_token;
          let tokenId = res.data.id;
          let refreshToken = res.data.attributes.refresh_token;

          webDb.setLItem('Authorization', token);
          webDb.setLItem('tokenId', tokenId);
          webDb.setLItem('refreshToken', refreshToken);

          this.$store.dispatch("appSiteModule/invalidateForum");

          this.getForum().then(() => {
            if (this.siteMode === 'pay') {
              this.$router.push({ path: 'pay-the-fee' });
            }
            this.$router.push({ path: '/' });
          })

        }

      }).catch(err => {
        console.log(err);
        this.btnLoading = false;
        this.showSignupInput = true;
      })
    },
    getRandomChars(len) {
      var s = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
      return Array(len).join().split(',').map(function() { return s.charAt(Math.floor(Math.random() * s.length)); }).join('');
    },
    autoRegister(nickname) {
      if (nickname) {
        nickname = nickname.replace(".", "");
        this.userName = nickname;
      } else {
        this.userName = "网友" + this.getRandomChars(6);
      }
      this.setSignData((res) => {
        if (this.signUpBdClickShow && res.errors[0].detail[0].includes("已经存在")) {
          if (nickname) {
            this.userName = nickname + this.getRandomChars(6);
          } else {
            this.userName = "网友" + this.getRandomChars(6);
          }
          this.setSignData();
        } else {
          this.showSignupInput = true;
          this.$toast.fail(res.errors[0].code + '\n' + res.errors[0].detail[0])
        }
      });
    },
    getWatchHref(code, state, sessionId) {
      this.appFetch({
        url: 'wechat',
        method: 'get',
        data: {
          code: code,
          state: state,
          sessionId: sessionId,
        }
      }).then(res => {
        if (res.errors) {
          let wxtoken = res.errors[0].token;

          if (res.rawData[0].code === 'no_bind_user') {
            this.wxtoken = wxtoken;
            webDb.setLItem('wxtoken', wxtoken);
            let gotologin = webDb.getLItem('wx-goto-login');
            if (gotologin) {
              webDb.removeLItem('wx-goto-login');
              this.$router.push({ path: '/wx-login-bd' })
              return;
            }
            if (this.registerClose) { //可以注册
              this.redirectMessage = "正在自动注册并登录..."
              this.password = "";
              let nickname = res.errors[0].user.nickname;
              if (this.signUpBdClickShow) {
                this.autoRegister(nickname);
              } else {
                this.captcha = new TencentCaptcha(this.appID, res => {
                  if (res.ret === 0) {
                    this.captcha_ticket = res.ticket;
                    this.captcha_rand_str = res.randstr;
                    this.autoRegister(nickname);
                  } else {
                    this.showSignupInput = true;
                  }
                });
                // 显示验证码
                this.captcha.show();
              }
            } else {
              this.$toast.fail("站点已关闭注册");
            }
            return;
          }

          if (res.errors[0].detail) {
            this.$toast.fail(res.errors[0].code + '\n' + res.errors[0].detail[0])
          } else {
            if (res.rawData[0].code === 'register_validate') {
              this.$router.push({ path: "information-page", query: { setInfo: 'registrationReview' } })
            } else if (res.rawData[0].code === 'ban_user') {
              this.$router.push({ path: "information-page", query: { setInfo: 'banUser' } })
            } else {
              window.location.href = '/api/oauth/wechat';
            }
          }
          // } else if (res.data.attributes.location) {
          //   //获取地址
          //   this.wxurl = res.data.attributes.location;
          //   window.location.href = res.data.attributes.location
        } else if (res.data.attributes.access_token) {

          this.$toast.success('登录成功');
          let token = res.data.attributes.access_token;
          let tokenId = res.data.id;
          let refreshToken = res.data.attributes.refresh_token;
          webDb.setLItem('Authorization', token);
          webDb.setLItem('tokenId', tokenId);
          webDb.setLItem('refreshToken', refreshToken);
          let beforeVisiting = webDb.getSItem('beforeVisiting');

          this.getUsers(tokenId).then((data) => {
            webDb.setLItem('foregroundUser', data.data.attributes.username);
            if (beforeVisiting) {
              this.$router.replace({ path: beforeVisiting });
              webDb.setSItem('beforeState', 1);
            } else {
              this.$router.push({ path: '/' });
            }
          });

        } else {
          //任何情况都不符合
        }
      }).catch(err => {
        console.log(err);
      })
    },
    getWatchHrefPC(code, state, sessionId) {
      this.appFetch({
        url: 'wxPcLogin',
        method: 'get',
        data: {
          code: code,
          state: state,
          sessionId: sessionId,
        }
      }).then(res => {
        if (res.errors) {

          let wxtoken = res.errors[0].user.wxtoken;
          if (res.rawData[0].code === 'no_bind_user') {
            //微信跳转
            this.wxtoken = wxtoken;
            webDb.setLItem('wxtoken', wxtoken);
          }
          // } else if (res.data.attributes.location) {
          //   //获取地址
          //   this.wxurl = res.data.attributes.location;
          //   window.location.href = res.data.attributes.location;
        } else if (res.data.attributes.access_token) {

          this.$toast.success('登录成功');
          let token = res.data.attributes.access_token;
          let tokenId = res.data.id;
          webDb.setLItem('Authorization', token);
          webDb.setLItem('tokenId', tokenId);
          let beforeVisiting = webDb.getSItem('beforeVisiting');

          if (beforeVisiting) {
            this.$router.replace({ path: beforeVisiting });
            webDb.setSItem('beforeState', 1);
          } else {
            this.$router.push({ path: '/' });
          }

        } else {
          //任何情况都不符合
        }
      }).catch(err => {
        console.log(err);
      })
    },
    getUsers(id) {
      return this.appFetch({
        url: 'users',
        method: 'get',
        splice: '/' + id,
        headers: { 'Authorization': 'Bearer ' + webDb.getLItem('Authorization') },
        data: {
          include: ['groups']
        }
      }).then(res => {
        if (res.errors) {
          if (res.errors[0].detail) {
            this.$toast.fail(res.errors[0].code + '\n' + res.errors[0].detail[0])
          } else {
            this.$toast.fail(res.errors[0].code);
          }
        } else {
          return res;
        }
      }).catch(err => {
      })
    },
  },
  created() {
    this.getForum();
    this.wxtoken = webDb.getLItem('wxtoken');
    let isWeixin = appCommonH.isWeixin().isWeixin;
    let code = this.$router.history.current.query.code;
    let state = this.$router.history.current.query.state;
    let sessionId = this.$router.history.current.query.sessionId;

    webDb.setLItem('code', code);
    webDb.setLItem('state', state);
    webDb.setLItem('sessionId', sessionId);

    if (isWeixin) {
      this.platform = 'mp';
      if (!code && !state && !sessionId) {
        // this.getWatchHref();
        window.location.href = '/api/oauth/wechat';
      } else {
        this.getWatchHref(code, state, sessionId);
      }
    } else {
      this.platform = 'dev';
      if (this.wxtoken === '' || this.wxtoken === null || this.wxtoken === undefined) {
        //PC端：没有wxtoken
        this.getWatchHrefPC(code, state, sessionId);
      }
    }
  },
  beforeRouteLeave(to, from, next) {
    // 隐藏验证码
    if (this.captcha) {
      this.captcha.destroy();
    }
    next();
  }
}
