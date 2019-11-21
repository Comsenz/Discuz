

export default {
  data:function () {
    return {
      headerTitle:"回复主题"
    }
  },
  methods: {
    //回复主题
    publish(){
      this.appFetch({
        url:"threads",
        method:"post",
        data:{
          content:this.content,
        },
      },(res)=>{
        alert('234');
        console.log(res);
        if (res.status === 200){
          console.log(res);
        } else{
          console.log('400');
        }

      },(err)=>{
        alert('45656');
        // console.log(err);
      })
    },

    backClick() {
      this.$router.go(-1);
    },
  }
}
