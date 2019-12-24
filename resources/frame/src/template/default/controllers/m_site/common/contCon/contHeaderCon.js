
export default {
  data:function () {
    return {
      imgUrl:''
    }
  },

  methods:{

  },

  created(){
    // this.url = require('../../../../../../../static/images/mytx.png') 
    //判断头像是否传过来，不然使用默认头像
    if (this.$attrs.imgUrl === undefined || this.$attrs.imgUrl === null){
      this.imgUrl = "appConfig.staticBaseUrl+'/images/noavatar.gif'";
    } else {
      if (this.$attrs.imgUrl.length < 1) {
        this.imgUrl = "appConfig.staticBaseUrl+'/images/noavatar.gif'"
      } else {
        this.imgUrl = this.$attrs.imgUrl;
      }
    }


    // console.log(this.$attrs,this.$listeners);
  }
}
