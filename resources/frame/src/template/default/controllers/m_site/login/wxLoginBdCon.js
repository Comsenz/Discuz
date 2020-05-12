/*
* 微信登录绑定控制器
* */

import LoginHeader from '../../../view/m_site/common/loginSignUpHeader/loginSignUpHeader'
import LoginFooter from '../../../view/m_site/common/loginSignUpFooter/loginSignUpFooter'
import webDb from '../../../../../helpers/webDbHelper';
import appCommonH from "../../../../../helpers/commonHelper";

export default {
  data: function () {
    return {
      userName: '',    //用户名
      password: '',    //密码
      siteMode: '',    //站点信息
      wxtoken: '',      //微信wxtoken
      wxurl: '',
      platform: '',
      btnLoading:false //按钮loading状态
    }
  },

  components: {
    LoginHeader,
    LoginFooter
  },

  methods: {
    loginBdClick() {
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

      this.appFetch({
        url: "login",
        method: "post",
        data: {
          "data": {
            "attributes": {
              username: this.userName,
              password: this.password,
              token: this.wxtoken,
              platform: this.platform
            },
          }
        }
      }).then(res => {
        this.btnLoading = false;

        if (res.errors) {
          if (res.errors[0].detail) {
            this.$toast.fail(res.errors[0].code + '\n' + res.errors[0].detail[0])
          } else {
            this.$toast.fail(res.errors[0].code);
          }
        } else {
          this.$toast.success('登录成功');
          let token = res.data.attributes.access_token;
          let tokenId = res.data.id;
          let refreshToken = res.data.attributes.refresh_token;
          webDb.setLItem('Authorization', token);
          webDb.setLItem('tokenId', tokenId);
          webDb.setLItem('refreshToken',refreshToken);
          let beforeVisiting = webDb.getSItem('beforeVisiting');
          this.$router.push({ path: webDb.getSItem('beforeVisiting') });
          this.$store.dispatch("appSiteModule/invalidateUser");
          this.$store.dispatch("appSiteModule/invalidateForum");
          this.getUser().then(res => {
            if (res.readdata._data.paid) {
              if (beforeVisiting) {
                this.$router.replace({ path: beforeVisiting });
                webDb.setSItem('beforeState', 1);
              } else {
                this.$router.push({ path: '/' });
              }
            } else {
              webDb.setLItem('foregroundUser', res.data.attributes.username);
              if (this.siteMode === 'pay') {
                this.$router.push({ path: 'pay-circle-login' });
              } else if (this.siteMode === 'public') {
                this.$router.push({ path: '/' });
              } else {
                //缺少参数，请刷新页面
              }
            }

          })

        }
      }).catch(err => {
        this.btnLoading = false;
      })

    },

    /*
    * 接口请求
    * */

    getForum() {
      this.$store.dispatch("appSiteModule/loadForum").then(res => {
        this.siteMode = res.readdata._data.set_site.site_mode;
        webDb.setLItem('siteInfo', res.readdata);
      });
    },
    getUser() {
      return this.$store.dispatch("appSiteModule/loadUser").then(res => {
        if (res.errors) {
          this.$toast.fail(res.errors[0].code);
        } else {
          return res;
        }
      }).catch(err => {
      })
    },
    /*getWatchHrefPC(code, state, sessionId) {
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
          let wxtoken = res.errors[0].user.wxtoken;

          if (wxStatus == 400) {
            //微信跳转
            this.wxtoken = wxtoken;
            webDb.setLItem('wxtoken', wxtoken);
            this.$router.push({ path: '/wx-login-bd' });
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
          }

        } else {
          //任何情况都不符合
        }
      }).catch(err => {
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
        if (res.errors) {

          let wxStatus = res.errors[0].status;
          let wxtoken = res.errors[0].user.wxtoken;

          if (wxStatus == 400) {
            //微信跳转
            this.wxtoken = wxtoken;
            webDb.setLItem('wxtoken', wxtoken);
            this.$router.push({ path: '/wx-login-bd' });
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
      })
    },*/
  },
  created() {
    let code = this.$router.history.current.query.code;
    let state = this.$router.history.current.query.state;
    let sessionId = this.$router.history.current.query.sessionId;
    let isWeixin = appCommonH.isWeixin().isWeixin;
    this.wxtoken = webDb.getLItem('wxtoken');

    if (isWeixin) {
      this.platform = 'mp';
    } else {
      this.platform = 'dev';
    }

    this.getForum();
  }
}
