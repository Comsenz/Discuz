
import SignUpHeader from '../../../view/m_site/common/loginSignUpHeader/loginSignUpHeader'
import SignUpFooter from '../../../view/m_site/common/loginSignUpFooter/loginSignUpFooter'
import User from '../../../../../common/models/User.js'
export default {
  data:function () {
    return {
      username:'',
      password:'',
      mobile:'13434900053',
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
      // var user = new User();
      this.apiStore.createRecord('users').save({
        username:this.username,
        password:this.password
      }).then(data => {
        //注册成功跳转到绑定手机号
        this.$router.push({path:'bind-phone'});
      }, error => {
        this.btnLoading = false;
      });

      // this.appFetch({
      //   url:'signUp',
      //   method:'post',
      //   data:{
      //     "data": {
      //     "type": "users",
      //     "attributes": {
      //         username:this.username,
      //         password:this.password,
      //         mobile: this.mobile
      //     },
      //     }
      //   }
      // }, (res) => {
      //   this.btnLoading = false;
      //   console.log(res);
      //   if (res.status !== "201"){
      //     //注册账号成功时
      //     this.$toast({
      //       type:'success',
      //       message: "注册成功，正在跳转",
      //     });
      //     this.$router.push({path:'bind-phone'});
      //     // this.error = false;
      //     // this.errorMessage = '';
      //   } else {
      //     //注册失败时
      //     this.$toast({
      //       type:'fail',
      //       message: "注册失败",
      //     });
      //     this.error = true;
      //     this.errorMessage = res.errors[0].detail[0];
      //   }

      // }, function(error) {
      //   // console.log(error, 'eror')
      // });

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
