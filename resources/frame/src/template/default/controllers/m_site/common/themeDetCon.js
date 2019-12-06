/**
 * 移动端主题详情控制器
 */
export default {
	data: function() {
	    return {
        // themeData:this.themeList.data,
        // themeIncluded:this.themeList.included,
        // replyTag:false
         id:1,
         checked:true,
         result:[],
         checkBoxres:[]
         
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
    },
    ischeckShow:{ //组件是否有选择按钮
      ischeckShow:false
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
    checkAll(){
      console.log(this.$refs)
      this.$refs.checkboxGroup.toggleAll(true);
    },
    signOutDele(){
      this.$refs.checkboxGroup.toggleAll();
    },

    deleteAllClick(){
      this.$emit('deleteAll',this.result);
    },


    //点击标题跳转到主题详情页
    jumpThemeDet:function(id){
      this.$router.push({ path:'details'+'/'+id});
    },
    //点击用户名称，跳转到用户主页
    jumpPerDet:function(id){
      this.$router.push({ path:'home-page'+'/'+id});
    },
    	//选中复选框
		// toggle(id) {
		// 	var listLen = this.userList.length;
		// 	if (listLen === 0) return;
		// 	var checkList = [];
		// 	for (let i = 0; i < listLen; i++) {
		// 		let checkid = this.userList[i].id();
		// 		if (checkid === id) {
		// 			this.userList[i].checkStatus = !this.userList[i].checkStatus;
		// 		}
		// 		if (this.userList[i].checkStatus) {
		// 			checkList.push(this.userList[i].username());
		// 		}
		// 	}
		// 	this.result = checkList;
		// },
	},
	beforeRouteLeave (to, from, next) {

	}
}
