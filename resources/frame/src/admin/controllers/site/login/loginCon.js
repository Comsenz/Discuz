/**
 * 后台登录页面JS
 */

/**
 * 可能注意的问题，从哪里来点击的登录，可能需要再回到之前的页面
 */

import { mapMutations } from 'vuex';

export default {
  data:function () {
    return {
      checked:false,
      form: {
        user: '',
        password: '',
      }
    }
  },
  methods:{
    ...mapMutations({
      setLoginState:'login/SET_LOGIN_STATE'
    }),

    adminLogin(){
      this.$router.push({path:'/admin/home'})
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
