
export default {
    data: function() {
        return {
            closeReason:'',
            imgLogo:''
      }
    },
    created(){
        this.getCloseReason()
    },
    methods:{
        getCloseReason(){
            this.appFetch({
              url:'forum',
              method:'get',
              data:{
      
              }
            }).then(res=>{
                console.log(res,'wuwuwuuwuw')
                this.closeReason = res.readdata._data.siteCloseMsg
                this.imgLogo = res.readdata._data.logo
            })
        },
        loginClick(){
            this.$router.push({path:'login-user'});  //跳到登录页
        }
    }
}
    
