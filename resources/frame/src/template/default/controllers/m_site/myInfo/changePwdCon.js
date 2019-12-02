/**
 * 修改密码
 */


import ChangePWDHeader from '../../../view/m_site/common/loginSignUpHeader/loginSignUpHeader'


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
      this.appFetch({
        url:'changePassword',
        method:'patch',
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
