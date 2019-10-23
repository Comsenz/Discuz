
import PayHeader from '../../../view/m_site/common/loginSignUpHeader/loginSignUpHeader'

export default {
  data:function () {
    return {

    }
  },

  components:{
    PayHeader
  },

  methods:{
    leapFrogClick(){
      this.$router.push({path:'/'})
    }
  }
}
