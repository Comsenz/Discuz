
import SignUpHeader from '../../../view/m_site/common/loginSignUpHeader/loginSignUpHeader'
import SignUpFooter from '../../../view/m_site/common/loginSignUpFooter/loginSignUpFooter'

export default {
  data:function () {
    return {

    }
  },

  components:{
    SignUpHeader,
    SignUpFooter
  },

  methods:{
    signUpClick(){
      this.$router.push({path:'/m_site/bind_phone'})
    }
  }

}
