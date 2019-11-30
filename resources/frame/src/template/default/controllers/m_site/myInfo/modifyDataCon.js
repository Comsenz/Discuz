/**
 * 修改资料
 */


import ModifyHeader from '../../../view/m_site/common/loginSignUpHeader/loginSignUpHeader';
import browserDb from '../../../../../helpers/webDbHelper';


export default {
  data:function () {
    return {
      headPortrait:'',//头像
      modifyPhone:'', //修改手机号
      changePwd:'',//修改密码
      bindType:'',//绑定类型
      
    }
  },

  components:{
    ModifyHeader
  },
  created(){
    this.modifyData() //修改资料
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
    },

    modifyData(){
      let userId = browserDb.getLItem('tokenId');
      this.apiStore.find('users',userId).then(res=>{
        this.modifyPhone = res.data.attributes.mobile;
        this.headPortrait = res.data.attributes.avatarUr;
        
      })
    }
  }
}
