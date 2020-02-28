/*
* 分页控制器
* 分页组件只是封装了简单的配置，具体需要组件提供更多功能，可根据el-pagination进行拓展配置
* */

import webDb from 'webDbHelper';

export default {
  data:function () {
    return {
      currentPags:this.currentPage
    }
  },
  props:{
    total: Number,

    pageSize: {
      type: Number,
      default: 10
    },

    currentPage: {
      type: Number,
      default: 1
    },
  },
  methods:{

    handleCurrentChange(val){
      webDb.setLItem('currentPag',val);
      this.$emit('current-change', val);
    },
    /*createdFn(){
      /!*
      * 页面刷新后，获取保存的页数，并返回给父级
      * *!/
      this.$parent.$emit('created',Number(webDb.getLItem('currentPag'))||1);
    },*/

  },

  beforeUpdate() {
    // this.currentPags = Number(webDb.getLItem('currentPag'))||1;
    webDb.setLItem('currentPag',this.currentPags);
  },

  beforeDestroy() {
    webDb.setLItem('currentPag',1);
    this.currentPags = 1;
  },

  /*beforeRouteLeave(to,form,next){
    next();
  },*/

  created(){
    /*
    * 页面刷新后，获取保存的页数，并修改当前页数
    * */
    this.currentPags = Number(webDb.getLItem('currentPag'))||1;
  },

  watch: {
    currentPage:{
      immediate: true,
      handler (val) {
        // this.currentPags = val;
      }

    }
  }
}
