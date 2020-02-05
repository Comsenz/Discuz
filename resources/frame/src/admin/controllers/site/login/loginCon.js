/**
 * 后台登录页面JS
 */

import { mapMutations } from 'vuex';
import browserDb from "../../../../helpers/webDbHelper";

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
          { required: true, message: '请输入用户名', trigger: 'blur' }
        ],
        password:[
          { required: true, message: '请输入密码', trigger: 'blur' }
        ]
      },
      tokenId:'',
      loginLoading:false
    }
  },
  methods:{
    ...mapMutations({
      setLoginState:'login/SET_LOGIN_STATE'
    }),

    adminLogin(formName){
      this.loginLoading = true;
      this.$refs[formName].validate((valid) => {
        if (valid) {
          this.postLogin().then(res=>{
            // this.tokenId = res.data.id;
            let token = res.data.attributes.access_token;
            let tokenId = res.data.id;
            let refreshToken = res.data.attributes.refresh_token;
            browserDb.setLItem('Authorization', token);
            browserDb.setLItem('tokenId', tokenId);
            browserDb.setLItem('refreshToken',refreshToken);

            if (token && tokenId) {
              this.getUserInfo(tokenId).then(res => {
                if (res.errors){
                  if (res.errors[0].detail){
                    this.$message.error(res.errors[0].code + '\n' + res.errors[0].detail[0])
                  } else {
                    this.$message.error(res.errors[0].code);
                  }
                  this.loginLoading = false;
                } else {
                  let groupId = res.readdata.groups[0]._data.id;
                  browserDb.setLItem('username', res.data.attributes.username);
                if (groupId === "1") {
                    this.$router.push({path: '/admin'});
                    this.$message({
                      message: '登录成功！',
                      type: 'success'
                    });
                    this.loginLoading = false;
                  } else {
                    this.$message.error('权限不足！');
                    this.loginLoading = false;
                  }
                }
              })
            } else {
              this.$message.error('登录失败');
              this.loginLoading = false;
            }
          }).catch(()=>{
            this.$message.error('登录失败');
            this.loginLoading = false;
          })

        } else {
          console.log('error submit!!');
          this.loginLoading = false;
          return false;
        }
      });
    },

   /* user(){
      this.$router.push('/user/userview')
    },*/


    /*
    * 接口请求
    * */
    postLogin(){
      return this.appFetch({
        url:'login',
        method:'post',
        data:{
          "data": {
            "attributes": {
              "username": this.form.user,
              "password": this.form.password
            }
          }
        }
      }).then(res=>{
          return res
      }).catch(err=>{
        console.log(err);
      })
    },
    getUserInfo(id){
      return this.appFetch({
        url:'users',
        method:'get',
        splice:'/' + id,
        data:{
          include:['groups']
        }
      }).then(res=>{
        return res
      }).catch(err=>{
        console.log(err);
      })
    }
  },
  created(){
    localStorage.clear();
  }
}
