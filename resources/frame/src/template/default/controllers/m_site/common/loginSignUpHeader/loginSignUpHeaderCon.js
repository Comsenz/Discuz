export default {
  data:function () {
    return {
      headerTitle:""
    }
  },

  methods:{
    headerBack(){
      console.log("回退");
      this.$router.go(-1)
    }
  }

}
