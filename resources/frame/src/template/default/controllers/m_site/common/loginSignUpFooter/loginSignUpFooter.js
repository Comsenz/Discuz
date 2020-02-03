/*
* 页脚控制器
* */

import webDb from '../../../../../../helpers/webDbHelper';

export default {
  data:function () {
    return {
      pageName:'login',   //页面分为login:登录界面、wx：绑定微信、signup：注册、bd：绑定手机号、pd：忘记密码
      siteMode:'',        //站点模式
      registerClose:true, //注册是否打开
      qcloudSms:true,    //短信配置是否打开
    }
  },

  methods:{
    //忘记密码？找回按钮
    retrieveClick(){
      this.$router.push('retrieve-pwd')
    },
    //注册按钮
    signUpClick(){
      this.$router.push('sign-up')
    },
    //注册，绑定微信新账号
    wxSignUpBdClick(){
      this.$router.push('/wx-sign-up-bd');
    },

    //登录微信账号
    wxLoginBdClick(){
      this.$router.push('/wx-login-bd')
    },

    //已有账号立即登录
    loginClick(){
      this.$router.push('/login-user')
    },
    //进入首页
    homeClick(){
      switch (this.siteMode){
        case 'pay':
          this.$router.push({path:'pay-the-fee'});
          // this.$router.push({path:'pay-circle-login'});
          break;
        case 'public':
          this.$router.push({path:'/'});
          break;
        default:
          console.log("参数错误，请重新刷新页面");
      }
    },

    getForum(){
      this.appFetch({
        url:'forum',
        method:'get',
        data:{}
      }).then(res=>{
        console.log(res);
        this.siteMode = res.readdata._data.set_site.site_mode;
        this.registerClose = res.readdata._data.set_reg.register_close;
        this.qcloudSms = res.readdata._data.qcloud.qcloud_sms;
        webDb.setLItem('siteInfo',res.readdata);
      }).catch(err=>{
        console.log(err);
      })
    }

  },
  created(){
    this.pageName = this.$router.history.current.name;
    this.getForum();
  }

}
