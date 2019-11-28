

export default {
  data:function () {
    return {
      showContStatus:false,   //显示内容状态
      showBottomStatus:false,   //显示内容栏是否触底
      mainHeight:0,    //内容主题高度
    }
  },

  methods:{
    showCont(){
      this.mainHeight = this.$slots.main[0].elm.offsetHeight;
      this.showContStatus = !this.showContStatus;

      /*
      *  获取内容高度+内容高度距离顶部距离，总值大于可视范围就会吸底
      * */
      if (this.$slots.main[0].elm.offsetHeight + this.$slots.main[0].elm.getBoundingClientRect().top > window.innerHeight) {
        this.showBottomStatus = true
      }

      /*
      *  不管视图怎么样，点击隐藏内容后，直接取消吸底
      * */
      if (!this.showContStatus){
        this.showBottomStatus = false
      }

    },

    /*
    *   监听滚动条方法
    * */
    handleScroll(){
      if (this.$slots.main[0].elm.offsetHeight + this.$slots.main[0].elm.getBoundingClientRect().top < window.innerHeight) {
        this.showBottomStatus = false;
      }else if (this.showContStatus){
        this.showBottomStatus = true;
      }
    }

  },
  mounted(){
    this.mainHeight = this.$slots.main[0].elm.offsetHeight;

    /*添加滚动条监听事件*/
    window.addEventListener('scroll', this.handleScroll,true)

  }

}
