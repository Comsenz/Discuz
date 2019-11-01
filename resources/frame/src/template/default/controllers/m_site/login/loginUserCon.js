/**
 * 移动端登录控制器
 */

import LoginHeader from '../../../view/m_site/common/loginSignUpHeader/loginSignUpHeader'
import LoginFooter from '../../../view/m_site/common/loginSignUpFooter/loginSignUpFooter'

export default {
  data:function () {
    return {
      userName:"",
      password:""
    }
  },

  components:{
    LoginHeader,
    LoginFooter
  },

  methods:{

    loginClick(){

      this.appFetch({
        url:"login",
        method:"post",
        data:{
          username:this.userName,
          password:this.password
        }
      },(res)=>{
        // console.log(res.errors[0].status);
        // console.log(status);
        console.log(res);
      },(err)=>{
        console.log(err);
      })

    },

    loginWxClick(){
      this.$router.push({path:'/wx-login-bd'})
    },
    loginPhoneClick(){
      this.$router.push({path:'/login-phone'})
    },


  }

}
