/**
 * 移动端主题详情控制器
 */
export default {
	data: function() {
	    return {
        // themeData:this.themeList.data,
        // themeIncluded:this.themeList.included,
        // replyTag:false
    }
	},
	props: {
    themeList: { // 组件的list
      type: Object
      // default: () => {
      //   return [];
      // }
    },
    replyTag: { // 组件是否显示回复
       replyTag: false
    }
    // isTopSgow: { // 组件是否显示置顶按钮
    //   isTopSgow: false
    // },
    // isMoreShow: { // 组件是否显示更多按钮
    //   isMoreShow: false
    // }
  },
	created(){


  },
	beforeDestroy () {

    },
	mounted: function() {
		// this.getCircle();
	},
	methods: {



	},

	mounted: function() {

	},
	beforeRouteLeave (to, from, next) {

	}
}
