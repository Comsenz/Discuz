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
      this.$router.push({path:'/m_site/login_user',query:{pageName:'login'}})
    }
  }

}
