/**
 * 修改资料
 */


import ModifyHeader from '../../../view/m_site/common/loginSignUpHeader/loginSignUpHeader'


export default {
  data:function () {
    return {

    }
  },

  components:{
    ModifyHeader
  },
  methods:{
    myModify(str){
      switch (str) {
        case 'modify-phone':
          this.$router.push('/modify-phone'); //修改手机号
          break;
        case 'change-pwd':
          this.$router.push('/change-pwd'); //修改密码
          break;
        default:
          this.$router.push('/');
      }
    }
  }
}
