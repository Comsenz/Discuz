/**
 * 移动端主题详情控制器
 */
export default {
	data: function() {
	    return {
        // themeData:this.themeList.data,
        // themeIncluded:this.themeList.included,
        // replyTag:false
         id:1
    }
	},
	props: {
    themeList: { // 组件的list
      type: Array
      // default: () => {
      //   return [];
      // }
    },
    replyTag: { // 组件是否显示回复
       replyTag: false
    },
    isTopShow: { // 组件是否显示置顶按钮
      isTopShow: false
    },
    isMoreShow: { // 组件是否显示更多按钮
      isMoreShow: false
    }
  },
  watch:{
  },
	created(){

    // this.getCircle();
  },
	beforeDestroy () {

  },
	methods: {
    //点击标题跳转到主题详情页
    jumpThemeDet:function(id){
      this.$router.push({ path:'details'+'/'+id});
    },
    //点击用户名称，跳转到用户主页
    jumpPerDet:function(id){
      this.$router.push({ path:'home-page'+'/'+id});
    }
	},
	beforeRouteLeave (to, from, next) {

	}
}
