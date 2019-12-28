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
         priview:[],
         showScreen:[],
         length:0

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
    this.forList();
    // this.getCircle();
  },
	beforeDestroy () {

  },
  watch:{
    //监听得到的数据
    themeList(newData,prevData){
      console.log(prevData);
      console.log(newData);
      this.themeList = newData;
      this.loadPriviewImgList();
    },
    deep:true
  },

	methods: {
    //循环数据新建数组，用于操作管理显示隐藏下拉菜单
    forList(){
      for(let k=0;k<this.themeList.length;k++){
        this.showScreen.push(false);
        this.length = this.themeList.length;
      }
    },

    //主题管理，点击更多显示下拉菜单
    bindScreen(index){
      var that = this;
      this.showScreen.forEach((item) => {  //循环已经把所有的状态值清空了
          item = false;
      })
       this.showScreen.splice(index,1,!this.showScreen[index]);
    },


    //管理操作
    themeOpera(postsId,clickType,clickStatus) {
      let attri = new Object();
       if(clickType == 2){
         console.log(clickStatus);
         //加精
         this.themeOpeRequest(postsId,attri,clickStatus);
        attri.isEssence = clickStatus;
       } else if(clickType == 3){
         //置顶
         // request = true;
        attri.isSticky = clickStatus;
        this.themeOpeRequest(postsId,attri,clickStatus);
       } else if(clickType == 4){
         //删除
        attri.isDeleted = true;
        this.themeOpeRequest(postsId,attri);
        // this.$router.push({
        //   path:'/circle',
        //   name:'circle'
        // })
       } else {
         // content = content
         // console.log(content);
         //跳转到发帖页
        this.$router.push({ path:'/edit-topic'+'/'+this.themeId});
       }
    },

    //跳转到回复页
    replyToJump:function(themeId,replyId,quoteCon) {
    	this.$router.push({
        path:'/reply-to-topic',
        name:'reply-to-topic',
        params: { themeId:themeId,replyQuote: quoteCon,replyId:replyId }
       })
    },

    //主题操作接口请求
    themeOpeRequest(themeId,attri,clickStatus){
        // console.log(attri);
        this.appFetch({
          url:'threads',
          method:'patch',
          splice:'/'+themeId,
          data:{
            "data": {
              "type": "threads",
              "attributes": attri
            },
            // "relationships": {
            //     "category": {
            //         "data": {
            //             "type": "categories",
            //             "id": cateId
            //         }
            //     }
            // }
          }
        }).then((res)=>{
          console.log(res);
          console.log('888');
          this.$emit('changeStatus', true);
        })
        // this.$emit('changeStatus', true);
    },

    //点赞
    replyOpera(postId,type,isLike,status){
      // console.log(isLike);
      let attri = new Object();
      attri.isLiked = status;
      let posts = 'posts/'+postId;
      this.appFetch({
        url:posts,
        method:'patch',
        data:{
          "data": {
            "type": "posts",
            "attributes": attri,
          }
        }
      }).then((res)=>{
        this.$message('修改成功');
        // this.detailsLoad();
        this.$emit('changeStatus', true);
      })
    },


    loadPriviewImgList(){
      var themeListLen = this.themeListResult.length;

      if(this.themeListResult =='' || this.themeListResult == null){
        return false;
      } else {
        for (let h = 0; h < themeListLen; h++) {
          // 图片地址
          let src = 'https://2020.comsenz-service.com/api/attachments/';
          let imageList = [];
          if(this.themeListResult[h].firstPost.images){
            for (let i = 0; i < this.themeListResult[h].firstPost.images.length; i++) {
              imageList.push(src + this.themeListResult[h].firstPost.images[i]._data.uuid);
            }
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
