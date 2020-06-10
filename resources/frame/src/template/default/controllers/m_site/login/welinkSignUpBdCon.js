
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
      return this.$store.dispatch("appSiteModule/loadForum").then(res => {
        if (res.errors) {
          this.$toast.fail(res.errors[0].code);
        } else {
          this.phoneStatus = res.readdata._data.qcloud.qcloud_sms;
          this.siteMode = res.readdata._data.set_site.site_mode;
          this.signReasonStatus = res.readdata._data.set_reg.register_validate;
        }
      });
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
              register_reason: this.signReason
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

          this.$store.dispatch("appSiteModule/invalidateForum");

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
        url: 'welink',
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
            this.$router.push({ path: '/welink-sign-up-bd' });
          }
        } else if (res.data.attributes.location) {
          //获取地址
          this.wxurl = res.data.attributes.location;
          window.location.href = res.data.attributes.location
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
    }
  },
  created: function () {
    this.getForum();
    this.openid = webDb.getLItem('openid');
    let isWeLink = appCommonH.isWeLink().isWeLink;
    let code = this.$router.history.current.query.code;
    let state = this.$router.history.current.query.state;
    let sessionId = this.$router.history.current.query.sessionId;

    webDb.setLItem('code', code);
    webDb.setLItem('state', state);

    if (isWeLink) {
      // this.getWatchHref();
      this.$welinkH5.getAuthCode().then(data => {
        alert(data.code);
      }).catch(error => {
        console.log('获取异常', error);
      });
      //this.platform = 'mp';
      /*if (!code && !state){
        this.getWatchHref();
      } else {
        this.getWatchHref(code,state,sessionId);
      }*/
    }
  }
}
