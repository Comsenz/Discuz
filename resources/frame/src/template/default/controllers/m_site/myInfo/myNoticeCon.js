/**
 * 我的通知
 */

import MyNoticeHeader from '../../../view/m_site/common/loginSignUpHeader/loginSignUpHeader'

export default {
  data:function () {
    return {

    }
  },

  methods:{
    myJump(str){
      switch (str) {
        case 'reply':
          this.$router.push('/reply');
          break;
        case 'reward':
          this.$router.push('/reward');
          break;
        case 'like':
          this.$router.push('/like');
          break;
        default:
          this.$router.push('/');
      }


    }
  },

  components:{
    MyNoticeHeader
  },

}
