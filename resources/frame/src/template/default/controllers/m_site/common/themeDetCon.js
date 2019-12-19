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
         checkBoxres:[],
         imageShow: false,
         index: 1,
         // firstpostImageList: [
           // 'https://img.yzcdn.cn/2.jpg',
           // 'https://img.yzcdn.cn/2.jpg'
         // ],
         themeListResult:[],
         firstpostImageListResult:[],
         priview:[]

    }
	},
	props: {
    themeList: { // 组件的list
      type: Array
      // default: () => {
      //   return [];
      // }
    },
    // firstpostImageList: { // list里的图片
    //   type:Array
    // },
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
    },
  },
	created(){
    this.loadPriviewImgList();
    // this.getCircle();
  },
	beforeDestroy () {

  },
  watch:{
    //监听得到的数据
    themeList(newData,prevData){
      this.themeListResult = newData;
      this.loadPriviewImgList();
    }
  },
	methods: {
    loadPriviewImgList(){
      var themeListLen = this.themeList.length;

      if(this.themeList =='' || this.themeList == null){
        return false;
      } else {
        for (let h = 0; h < themeListLen; h++) {
          // 图片地址
          let src = 'https://2020.comsenz-service.com/api/attachments/';
          let imageList = [];
          for (let i = 0; i < this.themeListResult[h].firstPost.images.length; i++) {
            imageList.push(src + this.themeListResult[h].firstPost.images[i]._data.uuid);
          }
          this.themeListResult[h].firstPost.imageList = imageList;
        }
      }
    },

    //主题详情图片放大轮播
    imageSwiper(index){
      this.loadPriviewImgList()
      this.imageShow = true;
      // this.priview = this.firstpostImageListResult[index];
      console.log(this.priview);
    },
    //主题详情图片放大轮播index值监听
    onChange(index) {
      this.index = index+1;
    },
    checkAll(){
      console.log(this.$refs);
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
