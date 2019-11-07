/**
 * 移动端登录控制器
 */

import LoginHeader from '../../../view/m_site/common/loginSignUpHeader/loginSignUpHeader'
import LoginFooter from '../../../view/m_site/common/loginSignUpFooter/loginSignUpFooter'

// import Header from '../../../view/m_site/common/headerView'

export default {
  data:function () {
    return {
      userName:"",
      password:"",
      btnLoading:false
    }
  },

  components:{
    LoginHeader,
    LoginFooter,
    // Header
  },

  methods:{

    loginClick(){
      this.btnLoading = true;





      this.appFetch({
        url:"login",
        method:"post",
        data:{
          username:this.userName,
          password:this.password
        }
      },(res)=>{
        console.log(res);

        if (res.status === 200){
          this.$toast.success('登录成功');
        } else {

        }

        this.btnLoading = false;

      },(err)=>{
        console.log(err);
        this.btnLoading = false;
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
