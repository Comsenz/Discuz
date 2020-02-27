/**
 * 移动端主题详情控制器
 */
export default {
  data: function() {
      return {
        code:''
    }
  },

	created(){

    // this.getUrlCode();
  },
  methods:{

    //点击“微信授权”
    getUrlCode(){
      this.code = this.$utils.getUrlKey('code');
      alert(code);
      this.appFetch({
        url:"weixin",
        method:"get",
        data:{
          code:this.code,
        }
      }).then(res =>{
        alert(65756765);
        // window.location.href = res.data.attributes.location;
      }, error => {
        if(error.errors[0].status == 100004){
          // this.$router.push({
          //   path:'circle',
          // });
          this.$router.go(-1);
        }
      })
    },
    loginWxClick(){
      this.$router.push({path:'/wx-login-bd'})
    },
    loginPhoneClick(){
      this.$router.push({path:'/login-phone'})
    },
  }

}
