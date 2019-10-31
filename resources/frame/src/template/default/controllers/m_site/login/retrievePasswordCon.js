
import retrievePWDHeader from '../../../view/m_site/common/loginSignUpHeader/loginSignUpHeader'
import retrievePWDFooter from '../../../view/m_site/common/loginSignUpFooter/loginSignUpFooter'


export default {
  data:function () {
    return {
      newpwd:"",
      sms:"",
      phone:""
    }
  },

  components:{
    retrievePWDHeader,
    retrievePWDFooter
  },

  methods:{

  }
}
