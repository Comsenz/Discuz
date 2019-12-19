/**
 * 登录-手机号登录
 */

import LoginHeader from '../../../view/m_site/common/loginSignUpHeader/loginSignUpHeader'
import LoginFooter from '../../../view/m_site/common/loginSignUpFooter/loginSignUpFooter'
import {mapState} from 'vuex';

export default {
  data:function () {
    return {
      phone:"",
      sms:""
    }
  },

  components:{
    LoginHeader,
    LoginFooter
  },

  computed:mapState({
    status:state => state.appSiteModule.status,
  }),

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

  },

  mounted(){
    console.log(this.status);
  },

  created(){

  }

}
