
import SignUpHeader from '../../../view/m_site/common/loginSignUpHeader/loginSignUpHeader'
import SignUpFooter from '../../../view/m_site/common/loginSignUpFooter/loginSignUpFooter'

export default {
  data:function () {
    return {
      username:'',
      password:'',
      password2:'',
      adminid:''

    }
  },

  components:{
    SignUpHeader,
    SignUpFooter
  },

  methods:{
    signUpClick(){

      /*this.axios.post('http://local.test.discuz.com:8883/api/users',{
        username:this.username,
        password:this.password,
        adminid:1
      }).then((res)=>{
        console.log(res);
      }).catch((err)=>{
        console.log(err);
      })*/

      this.appFetch({
        url:'signUp',
        method:'post',
        data:{
          username:this.username,
          password:this.password,
          adminid:1
        }
      }, function(res) {
        console.log(res, 'success')
      }, function(error) {
        console.log(error, 'eror')
      })

      // this.$router.push({path:'/m_site/bind_phone'})

      console.log(this.username + 'u');
      console.log(this.password + 'p1');


    }
  }

}
