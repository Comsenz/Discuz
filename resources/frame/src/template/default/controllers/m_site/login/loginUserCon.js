/**
 * 移动端登录控制器
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
    loginWxClick(){
      this.$router.push({path:'/m_site/wx_login_bd'})
    },
    loginPhoneClick(){
      this.$router.push({path:'/m_site/login_phone'})
    },


  }

}
