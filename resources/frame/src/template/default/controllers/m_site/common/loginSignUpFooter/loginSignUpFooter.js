export default {
  data:function () {
    return {
      pageName:'login'  //页面分为login:登录界面、wx：绑定微信、signup：注册、bd：绑定手机号、pd：忘记密码
    }
  },

  methods:{
    retrieveClick(){
      console.log('找回密码');
    },
    signUpClick(){
      console.log('注册');
    }
  },
  created(){
    this.pageName = this.$router.history.current.query.pageName;
    console.log(this.$router.history.current.query.pageName);
  }

}
