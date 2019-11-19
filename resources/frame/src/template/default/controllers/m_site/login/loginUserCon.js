/**
 * 移动端登录控制器
 */

import LoginHeader from '../../../view/m_site/common/loginSignUpHeader/loginSignUpHeader'
import LoginFooter from '../../../view/m_site/common/loginSignUpFooter/loginSignUpFooter'

// import Header from '../../../view/m_site/common/headerView'

import { mapMutations } from 'vuex';

export default {
  data:function () {
    return {
      userName:"",
      password:"",
      userId:'2',
      btnLoading:false
    }
  },

  components:{
    LoginHeader,
    LoginFooter,
    // Header
  },



  mounted:function(){
  },
  methods:{
  


    loginClick(){
      // this.btnLoading = true;
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
          // console.log('登录成功');
          this.paramsObj = {
            userId:this.userId
          };
          let params = this.appCommonH.setGetUrl('/api/login', this.paramsObj);
          console.log(params);
          // this.$router.push({
          //   path:'m_site/bind-phone',

          //   });
          // this.$router.push({path: params});
          // this.$router.push({path: 'bind-phone'});
        } else{
          console.log('400');
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


  },
  created(){
    let isWeixin =this.appCommonH.isWeixin().isWeixin;
    if(isWeixin == true){
      //微信登录时
      alert('微信登录');


    } else {
      //手机浏览器登录时
      console.log('手机浏览器登录');
      // loginClick();



    }





  },

}
