
import SignUpHeader from '../../../view/m_site/common/loginSignUpHeader/loginSignUpHeader'
import SignUpFooter from '../../../view/m_site/common/loginSignUpFooter/loginSignUpFooter'

export default {
  data:function () {
    return {
      username:'',
      password:'',
      adminid:'',

      btnLoading:false, //注册按钮状态

      error:false,    //错误状态
      errorMessage:"" //错误信息
    }
  },

  components:{
    SignUpHeader,
    SignUpFooter
  },

  methods:{

    signUpClick(){

      this.btnLoading = true;

      this.appFetch({
        url:'signUp',
        method:'post',
        data:{
          username:this.username,
          password:this.password,
          adminid:1
        }
      }, (res) => {
        this.btnLoading = false;

        if (res.errors[0].status !== "200"){
          this.error = true;
          this.errorMessage = res.errors[0].detail[0];
          console.log("报错")
        } else {
          this.error = false;
          this.errorMessage = "";
          Toast('注册成功，正在跳转首页');
          this.$router.push({path:'/m_site/bind_phone'})
        }

      }, function(error) {
        // console.log(error, 'eror')
      });

    },

    //错误提示
    clearError(str){
      switch (str){
        case 'clear' :
          this.error = false;
          this.errorMessage = "";
          break;
        case 'blur':
          if (this.password !== ''){
            this.error = true;
          }
          break;
        default:
          this.error = false;
      };
    }

  }

}
