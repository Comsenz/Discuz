
/*
* 提示信息页管理器
* 这里可以统一接口提示信息，按照data里setInfo写法就可以了。跳转页面的时候需要在url里添加query参数
* */

export default {
  data: function () {
    return {
      setInfo:{
        registrationReview:{
          title:"注册成功，等待审核",
          comment:'感谢您的注册，站点开启了人工验证注册用户，请等待审核',
          icon:'checked',
          iconColor:'#07c160',
          btnText:'完成'
        },
        banUser:{
          title:'帐号被禁用',
          comment:'该帐号被禁用，请联系管理员或重新注册。',
          icon:'clear',
          iconColor:'#F43429',
          btnText:'登录 / 注册'
        }
      }
    }
  },
  methods:{
    btnClick() {
      switch (this.$route.query.setInfo) {
        case 'registrationReview':
          this.$router.push({path: '/'});
          break;
        case 'banUser':
          this.$router.push({path: 'wx-sign-up-bd'});
          break;
        default:
          console.log('参数错误');
      }
    }
  },
  created(){
  }
}
