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
    getCodeApi(state){//获取code
         let urlNow=encodeURIComponent(window.location.href);
         let scope='snsapi_userinfo';    //snsapi_userinfo   //静默授权 用户无感知
         let appid='wx2aa96b3508831102';
         let url=`https://open.weixin.qq.com/connect/oauth2/authorize?appid=${appid}&redirect_uri=${urlNow}&response_type=code&scope=${scope}&state=${state}#wechat_redirect`;
         window.location.replace(url);
    },
    getUrlKey(name){//获取url 参数
     return decodeURIComponent((new RegExp('[?|&]'+name+'='+'([^&;]+?)(&|#|;|$)').exec(location.href)||[,""])[1].replace(/\+/g,'%20'))||null;
    },

    // ...mapMutations({
    //   setStatus:'site/SET_STATUS'
    // }),

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
      alert(this.getUrlKey("code"));
      let code=this.getUrlKey("code");
      // alert(code);
      if(code){
          this.$axios.get("/Wxopenid/getUserInfo?code="+code)
          .then((res)=>{
              console.log(res);
              //跳转到手机绑定页
              // this.$router.push({ path:'m_site/bind-phone'});
          })
      }else{
            // alert('执行else');
            this.getCodeApi("123");
      }



    } else {
      //手机浏览器登录时
      console.log('手机浏览器登录');
      // loginClick();



    }





  },

}
