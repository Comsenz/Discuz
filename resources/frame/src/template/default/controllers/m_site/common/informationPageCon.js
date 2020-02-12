
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
        }
      }
    }
  },
  methods:{
    btnClick(){
      this.$router.push({path:'/'});
    }
  },
  created(){
    console.log(this.$route.query.setInfo);
  }
}
