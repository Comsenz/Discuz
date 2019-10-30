export default {
  data:function () {
    return {
      titles: this.title,
      nums:this.num
    }
  },
  props:{
    title:{
      default:"标题",
      type:String
    },
    num:{
      default:"0.00",
      type:String
    }
  },

  methods:{

  },
  mounted(){
    // this.title = this.props.title;
  }
}
