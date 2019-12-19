/**
 * 移动端登录控制器
 */

import LoginHeader from '../../../view/m_site/common/loginSignUpHeader/loginSignUpHeader';
import LoginFooter from '../../../view/m_site/common/loginSignUpFooter/loginSignUpFooter';
import browserDb from '../../../../../helpers/webDbHelper';

// import Header from '../../../view/m_site/common/headerView'

import {mapMutations,mapState} from 'vuex';

export default {
  data: function () {
    return {
      userName: "",
      password: "",
      userId: '2',
      btnLoading: false,
      wxLoginShow: true,
      isOne: false
      // attributes:'/login-user'
    }
  },
  /*
  * 映射appSiteModule/state内定义的属性
  * */
  computed:mapState({
    status:state => state.appSiteModule.status,
  }),

  mounted(){

  },

  methods: {
    /*
    * 映射mutation内方法
    * */
    ...mapMutations({
      setStatus:'appSiteModule/SET_STATUS'
    }),



    loginClick() {
      // this.btnLoading = true;

      // console.log(this.status);

      this.setStatus('啊啦啦啦');

      console.log(this.status);

      /*this.appFetch({
        url: "login",
        method: "post",
        data: {
          "data": {
            "attributes": {
              username: this.userName,
              password: this.password,
            },
          }
        }
      }).then(res => {
        console.log(res);

        if (res.errors){
          this.$toast.fail(res.errors[0].code);
        } else {
          this.$toast.success('登录成功');
          let token = res.data.attributes.access_token;
          let tokenId = res.data.id;
          browserDb.setLItem('Authorization', token);
          browserDb.setLItem('tokenId', tokenId);
          // let params = this.appCommonH.setGetUrl('/api/login', this.paramsObj);
          this.$router.push({
            path: 'bind-phone',
          });
        }

      }).catch(err => {
        console.log(err);
      })*/

    },

    loginWxClick() {
      this.$router.push({path: '/wx-login-bd'})
    },
    loginPhoneClick() {
      this.$router.push({path: '/login-phone'})
    },

  },
  created() {
    let isWeixin = this.appCommonH.isWeixin().isWeixin;
    let isPhone = this.appCommonH.isWeixin().isPhone;
    console.log()
    if (isWeixin == true) {
      // const APPID = 'wx2aa96b3508831102';
      // const REDIRECT_URI = window.location.host;
      // const SCOPE = 'snsapi_base';
      // const STATE = '123';
      //微信登录时
      alert('微信登录');
      // let redirect_uris ="";
      // let redirect_uri ="";
      // let href = 'https://open.weixin.qq.com/connect/oauth2/authorize?appid=' + APPID + '&redirect_uri=' + REDIRECT_URI + '&response_type=code&scope=' + SCOPE + '&state=' + STATE + '#wechat_redirect';

      this.appFetch({
        url: "weixin",
        method: "get",
        data: {
          // attributes:this.attributes,
        }
      }).then(res => {
        console.log(res.data.attributes.location)
        window.location.href = res.data.attributes.location;
      });
    } else if (isPhone == true) {
      //手机浏览器登录时
      console.log('手机浏览器登录');
      this.wxLoginShow = false;
      this.isOne = true;
      // console.log(this.appFetch({
      //   url:"weixin",
      //   method:"get",
      //   data:{
      //     // attributes:this.attributes,
      //   }
      // }))

    } else {

      console.log('pc登录');
    }


  },
  components: {
    LoginHeader,
    LoginFooter,
    // Header
  },
}
