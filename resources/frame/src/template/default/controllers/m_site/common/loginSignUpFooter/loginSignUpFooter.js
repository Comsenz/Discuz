export default {
  data:function () {
    return {
      pageName:'login'  //页面分为login:登录界面、wx：绑定微信、signup：注册、bd：绑定手机号、pd：忘记密码
    }
  },

  methods:{
    //忘记密码？找回按钮
    retrieveClick(){
      this.$router.push('/m_site/retrieve_pwd')
    },
    //注册按钮
    signUpClick(){
      this.$router.push('/m_site/sign_up')
    },
    //注册，绑定微信新账号
    wxSignUpBdClick(){
      this.$router.push('/m_site/wx_sign_up_bd');
    },

    //已有账号立即登录
    loginClick(){
      this.$router.push('/m_site/login_user')
    },
    //进入首页
    homeClick(){
      this.$router.push('/')
    },



  },
  created(){
    this.pageName = this.$router.history.current.name;
    console.log(this.$router.history.current.name);
  }

}
