/**
 * 后台登录页面JS
 */

import { mapMutations } from 'vuex';

export default {
  data:function () {
    return {
      checked:false,
      form: {
        user: '',
        password: '',
      },
      rules:{
        user:[
          { required: true, message: '请输入用户名', trigger: 'blur' },
          { min: 3, max: 5, message: '长度在 3 到 5 个字符', trigger: 'blur' }
        ]
      }
    }
  },
  methods:{
    ...mapMutations({
      setLoginState:'login/SET_LOGIN_STATE'
    }),

    adminLogin(formName){

      // console.log(this.$refs[formName].validate);

      this.$refs[formName].validate((valid) => {
        if (valid) {
          alert('submit!');
        } else {
          console.log('error submit!!');
          return false;
        }
      });

      // this.$router.push({path:'/admin/home'})
    },

    black(){
      this.setLoginState();
      this.$router.go(-1)
    },
    user(){
      this.$router.push('/user/userview')
    }
  }
}
