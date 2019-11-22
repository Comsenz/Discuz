export default {
  data:function () {
    return {
      showContStatus:false,   //显示内容状态
      showBottomStatus:false,   //显示内容栏是否触底
      mainHeight:0,    //内容主题高度
    }
  },

  methods:{

   /* contClick(){
      let control = this.$refs.contControl;

      let controlHeight = control.getBoundingClientRect().top;  //元素顶部到浏览器顶部距离
      let windowHeight = window.innerHeight;  //获取浏览器可视高度

      console.log(controlHeight);
      console.log(windowHeight);
    },*/

    showCont(){
      this.mainHeight = this.$slots.main[0].elm.offsetHeight;

      this.showContStatus = !this.showContStatus;

      // console.log(this.$slots.main[0].elm.offsetHeight + this.$slots.main[0].elm.getBoundingClientRect().top);
      // console.log(window.innerHeight);

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


      // console.log(this.showBottomStatus);

    },

    conlog(){
      console.log(123);

      console.log(this.$refs.contControl.scrollWidth);

      // console.log(this.$refs.contMain.clientWidth);

    }

  },
  mounted(){
    this.mainHeight = this.$slots.main[0].elm.offsetHeight;

    window.addEventListener('resize', this.conlog);

    // console.log(window.onresize);

  }

}
