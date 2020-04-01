

export default {
  data:function () {
    return {
      showContStatus:false,     //显示内容状态
      showBottomStatus:false,   //显示内容栏是否触底
      mainHeight:0,             //内容主题高度
      windowWidth:0,            //浏览器宽度
      themeNameLeft:70,         //回复主题距离左侧距离
      themeNameStyle:{
        left:'70',
        width:'calc(100% - '    //calc(100% - 140+)
      },
    }
  },
  props:{
    // author:Boolean,
    // listData:Array
  },
  methods:{
    showCont(){
      this.mainHeight = this.$slots.main[0].elm.offsetHeight;
      this.showContStatus = !this.showContStatus;

      let mainWidth = this.$slots.main[0].elm.getBoundingClientRect().width;

      /*
      *  获取内容高度+内容高度距离顶部距离，总值大于可视范围就会吸底
      * */
      if (this.$slots.main[0].elm.offsetHeight + this.$slots.main[0].elm.getBoundingClientRect().top > window.innerHeight) {
        this.showBottomStatus = true;
        this.$refs.contControl.style.width = `${mainWidth + 40}PX`;
      }

      /*
      *  不管视图怎么样，点击隐藏内容后，直接取消吸底
      * */
      if (!this.showContStatus){
        this.showBottomStatus = false;
        this.$refs.contControl.style.width = '100%';
      }

    },

    /*
    *   监听滚动条方法
    * */
    handleScroll(){
      if (this.$refs.contControl){
        //浏览器大小改变时，重新获取内容宽度，并赋值。
        this.$refs.contControl.style.width = `${this.$slots.main[0].elm.getBoundingClientRect().width + 40}PX`;
      }

      if (this.$slots.main[0].elm.offsetHeight + this.$slots.main[0].elm.getBoundingClientRect().top < window.innerHeight) {
        this.showBottomStatus = false;
      }else if (this.showContStatus){
        this.showBottomStatus = true;
      }

    },

    /*
    * 监听浏览器窗口大小
    * */
    browserSize(){
      /*
      *   判断显示内容是否展开，如果展开状态、触底的状态下，获取内容宽度，赋值给显示内容组件宽度。
      *   如果是展开不触底状态下，赋值给显示内容组件宽度为100%。
      * */

      if(this.$refs.contControl){

        const {width,top} = this.$slots.main[0].elm.getBoundingClientRect();
        const contControl = this.$refs.contControl.style;

        if (this.showContStatus){

          if (this.$slots.main[0].elm.offsetHeight + top > window.innerHeight) {
            //浏览器大小改变时，重新获取内容宽度，并赋值。
            contControl.width = `${width + 40}PX`;

          } else {
            contControl.width = '100%';
          }

          //浏览器大小改变时，重新获取内容高度，并赋值。
          this.$refs.contMain.style.height = `${this.$slots.main[0].elm.offsetHeight + 30}PX`;

        } else {
          contControl.width = '100%';
        }

      }
    },

    removeScrollHandler(){
      window.removeEventListener('scroll', this.handleScroll,true);
      window.removeEventListener('resize', this.browserSize,true);
    },

    themeStyle(){
      this.themeNameStyle.left = '70';
      this.themeNameStyle.width = 'calc(100% - ';
      this.themeNameStyle.left = 70 + this.$refs.userName.clientWidth + 'px';
      this.themeNameStyle.width = this.themeNameStyle.width + (100+this.$refs.userName.clientWidth) + 'px)';
    }

  },
  mounted(){
    this.mainHeight = this.$slots.main[0].elm.offsetHeight;

    /*添加滚动条监听事件*/
    window.addEventListener('scroll', this.handleScroll,true);

    /*添加浏览器窗口大小监听事件*/
    window.addEventListener('resize',this.browserSize,true);

    //获取当前浏览器宽度
    this.windowWidth = window.innerWidth;

    this.themeStyle();

  },
  beforeDestroy() {
    this.removeScrollHandler();
  }

}
