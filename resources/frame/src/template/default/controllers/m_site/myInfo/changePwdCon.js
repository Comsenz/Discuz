/**
 * 修改密码
 */


import ChangePWDHeader from '../../../view/m_site/common/loginSignUpHeader/loginSignUpHeader';
import browserDb from '../../../../../helpers/webDbHelper';


export default {
  data:function () {
    return {
      pwd:'',
      newpwd:"",
      confirmpwd:''
    }
  },

  components:{
    ChangePWDHeader
  },
  mounted(){
    // this.ChangePwd()
  },
  methods:{
    subm(){
      const userId = browserDb.getLItem('tokenId');
      this.appFetch({
        url:'users',
        method:'patch',
        splice:'/'+userId,
        data:{
          "data": {
            "attributes": {
              "password": this.pwd,
              "newPassword":this.newpwd,
              "password_confirmation":this.confirmpwd,
              "mobile": "186xxxx0384",
              "status": 1
            }
        }
        }
      }).then((res)=>{
        
      })
    },
  }

}
