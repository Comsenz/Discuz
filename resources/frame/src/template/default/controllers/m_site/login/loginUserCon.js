/**
 * 移动端登录控制器
 */

import LoginHeader from '../../../view/m_site/common/loginSignUpHeader/loginSignUpHeader';
import LoginFooter from '../../../view/m_site/common/loginSignUpFooter/loginSignUpFooter';
import browserDb from '../../../../../helpers/webDbHelper';

// import Header from '../../../view/m_site/common/headerView'

import { mapMutations } from 'vuex';

export default {
  data:function () {
    return {
      userName:"",
      password:"",
      userId:'2',
      btnLoading:false
      // attributes:'/login-user'
    }
  },

  components:{
    LoginHeader,
    LoginFooter,
    // Header
  },

  created:function(){
    console.log(webDbHepler);
  },

  mounted:function(){
  },
  methods:{
    loginClick(){
      // this.btnLoading = true;
      this.appFetch({
        url:"login",
        method:"post",
        data:{
          "data": {
            "attributes": {
              username:this.userName,
              password:this.password,
            },
          }
        }
      }).then(res => {
          this.$toast.success('登录成功');
          console.log(res);
          let token = res.data.attributes.access_token;
          console.log(token)
          browserDb.setLItem('Authorization',token);
          // console.log(browserDb.getLItem('Authorization'));
          // this.paramsObj = {
          //   userId:this.userId
          // };
          // let params = this.appCommonH.setGetUrl('/api/login', this.paramsObj);
          this.$router.push({
            path:'bind-phone',
          });
       });

    },

    loginWxClick(){
      this.$router.push({path:'/wx-login-bd'})
    },
    loginPhoneClick(){
      this.$router.push({path:'/login-phone'})
    },


  },
  created(){
    let isWeixin =this.appCommonH.isWeixin().isWeixin;
    if(isWeixin == true){
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
        url:"weixin",
        method:"get",
        data:{
          // attributes:this.attributes,
        }
      }).then(res=>{
        console.log(res.data.attributes.location)
        window.location.href = res.data.attributes.location;
      });



    } else {
      //手机浏览器登录时
      console.log('手机浏览器登录');
      // loginClick();

      console.log(this.appFetch({
        url:"weixin",
        method:"get",
        data:{
          // attributes:this.attributes,
        }
      }))

    }





  },

}
