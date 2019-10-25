/**
 * 登录-手机号登录
 */

import LoginHeader from '../../../view/m_site/common/loginSignUpHeader/loginSignUpHeader'
import LoginFooter from '../../../view/m_site/common/loginSignUpFooter/loginSignUpFooter'


export default {
  data:function () {
    return {

    }
  },

  components:{
    LoginHeader,
    LoginFooter
  },

  methods:{
    loginUserClick(){
      this.$router.push({path:'/login-user'})
    },
    wxLoginClick(){
      this.$router.push({path:'/wx-login-bd'})
    },

    getYZ(){
      console.log(123);
    }

  }

}
