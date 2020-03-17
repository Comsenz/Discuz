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
      openid: '',
      platform: '',
      signReason: '',        //注册原因
      signReasonStatus: false,
      signUpBdClickShow: true, // 是否触发验证码按钮
      appID: '',               // 腾讯云验证码场景 id
      captcha: null,           // 腾讯云验证码实例
      captcha_ticket: '',      // 腾讯云验证码返回票据
      captcha_rand_str: '',    // 腾讯云验证码返回随机字符串
    }
  },

  components: {
    LoginHeader,
    LoginFooter
  },

  methods: {
    signUpBdClick() {

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
          this.$toast.fail(res.errors[0].code);
        } else {
          this.phoneStatus = res.readdata._data.qcloud.qcloud_sms;
          this.siteMode = res.readdata._data.set_site.site_mode;
          this.signReasonStatus = res.readdata._data.set_reg.register_validate;
        }

      }).catch(err => {
        console.log(err);
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
              username: this.userName,
              password: this.password,
              openid: this.openid,
              platform: this.platform,
              register_reason: this.signReason,
              captcha_ticket: this.captcha_ticket,
              captcha_rand_str: this.captcha_rand_str
            },
          }
        }
      }).then(res => {
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

          webDb.setLItem('Authorization', token);
          webDb.setLItem('tokenId', tokenId);
          webDb.setLItem('refreshToken', refreshToken);

          this.getForum().then(() => {
            if (this.phoneStatus) {
              this.$router.push({ path: 'bind-phone' });
            } else if (this.siteMode === 'pay') {
              this.$router.push({ path: 'pay-the-fee' });
            } else if (this.siteMode === 'public') {
              this.$router.push({ path: '/' });
            } else {
              //缺少参数，请刷新页面
            }
          })

        }

      }).catch(err => {
        console.log(err);
      })
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
        // console.log(res);

        if (res.errors) {

          let wxStatus = res.errors[0].status;
          let openid = res.errors[0].user.openid;

          if (wxStatus == 400) {
            //微信跳转
            this.openid = openid;
            webDb.setLItem('openid', openid);
            this.$router.push({ path: '/wx-sign-up-bd' });
          }
        } else if (res.data.attributes.location) {
          //获取地址
          // console.log('获取地址');
          this.wxurl = res.data.attributes.location;
          window.location.href = res.data.attributes.location
        } else if (res.data.attributes.access_token) {

          this.$toast.success('登录成功');
          let token = res.data.attributes.access_token;
          let tokenId = res.data.id;
          let refreshToken = res.data.attributes.refresh_token;
          webDb.setLItem('Authorization', token);
          webDb.setLItem('tokenId', tokenId);
          webDb.setLItem('refreshToken', refreshToken);
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
    getWatchHrefPC(code, state, sessionId) {
      this.appFetch({
        url: 'wxLogin',
        method: 'get',
        data: {
          code: code,
          state: state,
          sessionId: sessionId,
        }
      }).then(res => {
        if (res.errors) {

          let wxStatus = res.errors[0].status;
          let openid = res.errors[0].user.openid;

          if (wxStatus == 400) {
            //微信跳转
            this.openid = openid;
            webDb.setLItem('openid', openid);
            this.$router.push({ path: '/wx-sign-up-bd' });
          }
        } else if (res.data.attributes.location) {
          //获取地址
          this.wxurl = res.data.attributes.location;
          window.location.href = res.data.attributes.location;
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
            this.$router.go(0);
          }

        } else {
          //任何情况都不符合
        }
      }).catch(err => {
        console.log(err);
      })
    },
    //验证码
    initCaptcha() {
      if (this.username === '') {
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

  },
  created() {
    this.getForum();
    this.openid = webDb.getLItem('openid');
    this.appID = webDb.getLItem('siteInfo')._data.qcloud.qcloud_captcha_app_id;
    let isWeixin = appCommonH.isWeixin().isWeixin;
    let code = this.$router.history.current.query.code;
    let state = this.$router.history.current.query.state;
    let sessionId = this.$router.history.current.query.sessionId;
    let qcloud_captcha = webDb.getLItem('siteInfo')._data.qcloud.qcloud_captcha;
    let register_captcha = webDb.getLItem('siteInfo')._data.set_reg.register_captcha;
    if (qcloud_captcha && register_captcha) {
      this.signUpBdClickShow = false
    }
    // console.log('进入注册页面');

    webDb.setLItem('code', code);
    webDb.setLItem('state', state);
    webDb.setLItem('sessionId',sessionId);

    if (isWeixin) {
      this.platform = 'mp';
      if (!code && !state && !sessionId) {
        this.getWatchHref();
        // console.log('第一次请求' + code);
        // console.log('第一次请求' + state);
      } else {
        this.getWatchHref(code, state, sessionId);
        // console.log('第二次请求' + code);
        // console.log('第二次请求' + state);
      }
    } else {
      this.platform = 'dev';
      if (this.openid === '') {
        //PC端：没有openid
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
