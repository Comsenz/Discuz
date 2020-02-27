/**
 * 移动端主题组件控制器
 */
import appCommonH from '../../../../../helpers/commonHelper';
import browserDb from '../../../../../helpers/webDbHelper';
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
         length:0,
         indexlist:-1,
         menuStatus:false,
         isWeixin: false,
         isPhone: false,
         viewportWidth:'',
         currentUserName: '',
         userId: ''

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
    this.userId = browserDb.getLItem('tokenId');
    this.currentUserName = browserDb.getLItem('foregroundUser');
    this.viewportWidth = window.innerWidth;
    this.isWeixin = appCommonH.isWeixin().isWeixin;
    this.isPhone = appCommonH.isWeixin().isPhone;
    this.loadPriviewImgList();
    this.forList();

    document.addEventListener('click',e => {
        var screen = this.$refs.screenDiv;
        if(document.contains(e.target)){
          //这句是说如果我们点击到了calss为screen以外的区域
          this.indexlist = -1;
        } else {
        }
    })
  },
  watch:{
    //监听得到的数据
    themeList(newData,prevData){
      this.themeList = newData;
      this.themeListResult = newData;
      this.loadPriviewImgList();
      this.$forceUpdate()
    },
    deep:true
  },
  // mounted (){
  //       let _this = this;
  //       document.addEventListener('click', function (e) {
  // 　　　　// 下面这句代码是获取 点击的区域是否包含你的菜单，如果包含，说明点击的是菜单以外，不包含则为菜单以内
  //       let flag = e.target.contains(document.getElementsByClassName('screen'))
  //       if(!flag) return
  //       _this.indexlist = -1;

  //       })
  // },
  methods: {
    //点赞和打赏数组处理（用户名之间用逗号分隔）
    userArr(data){
      let datas = [];
      data.forEach((item)=>{

        datas.push('<a  href="/home-page/'+item._data.id+'">'+ item._data.username + '</a>')
      });
      return datas.join(',')
    },

    //循环数据新建数组，用于操作管理显示隐藏下拉菜单
    forList(){
      // var screenLen = this.themeList.length;
      // for(let k=0;k < screenLen;k++){
      //   this.showScreen.push(false);
      //   // this.length = screenLen;
      // }
    },

    //主题管理，点击更多显示下拉菜单
    bindScreen(index,e){
      if(index==this.indexlist){
        this.indexlist=-1
      }else{
        this.indexlist=index;
      }
    },


    //管理操作
    // themeOpera(postsId,clickType,clickStatus) {
    //   let attri = new Object();
    //    if(clickType == 2){
    //      //加精
    //      this.themeOpeRequest(postsId,attri,clickStatus);
    //     attri.isEssence = clickStatus;
    //    } else if(clickType == 3){
    //      //置顶
    //      // request = true;
    //     attri.isSticky = clickStatus;
    //     this.themeOpeRequest(postsId,attri,clickStatus);
    //    } else if(clickType == 4){
    //      //删除
    //     attri.isDeleted = true;
    //     this.themeOpeRequest(postsId,attri);
    //     // this.$router.push({
    //     //   path:'/circle',
    //     //   name:'circle'
    //     // })
    //    } else {
    //      // content = content
    //      //跳转到发帖页
    //     this.$router.push({ path:'/edit-topic'+'/'+this.themeId});
    //    }
    // },


    themeOpera(themeId,clickType,clickStatus,itemIndex) {
      let attri = new Object();
       if(clickType == 3){
         //加精
         if (clickStatus) {
           attri.isEssence = false;
         } else {
           attri.isEssence = true;
         }
         this.themeOpeRequest(themeId, attri, '3', itemIndex);
       } else if(clickType == 4){
         //置顶
         if (clickStatus) {
           attri.isSticky = false;
         } else {
           attri.isSticky = true;
         }
        this.themeOpeRequest(themeId,attri,'4', itemIndex);
       } else if(clickType == 5){
         //删除
        attri.isDeleted = true;
        this.themeOpeRequest(themeId,attri,'5', itemIndex);
       } else if(clickType == 6){
         //跳转到发帖页
        this.$router.push({ path:'/edit-topic'+'/'+themeId});
       } else if(clickType == 7){
         //回复
         this.$router.push({
           path:'/reply-to-topic'+'/'+themeId+'/0',
         });
         // browserDb.setLItem('replyQuote', quoteCon);
       }
    },

    //跳转到回复页
    // replyToJump:function(themeId,replyId,quoteCon) {
    // 	this.$router.push({
    //     path:'/reply-to-topic',
    //     name:'reply-to-topic',
    //     params: { themeId:themeId,replyQuote: quoteCon,replyId:replyId }
    //    })
    // },

    //主题操作接口请求
    themeOpeRequest(themeId,attri,clickType, itemIndex){
        this.appFetch({
          url:'threads',
          method:'patch',
          splice:'/'+themeId,
          data:{
            "data": {
              "type": "threads",
              "attributes": attri
            },
          }
        }).then((res)=>{
          if (res.errors){
            this.$toast.fail(res.errors[0].code);
            throw new Error(res.error)
          } else {

            if(clickType == '3'){
              //加精
              this.essenceStatus = res.readdata._data.isEssence;
              this.themeList[itemIndex]._data.isEssence = this.essenceStatus;
            } else if(clickType == '4'){
              //置顶
              this.stickyStatus = res.readdata._data.isSticky;
              this.themeList[itemIndex]._data.isSticky = this.stickyStatus;

            } else if(clickType == '5'){
              //删除
              this.deletedStatus = res.readdata._data.isDeleted;
              this.themeList.splice(itemIndex,1);
              this.$toast.success('删除成功');
            }
          }
        })
    },

    //点赞
    replyOpera(firstPostId,clickType,clickStatus,itemIndex){
      // return false;
      let attri = new Object();
      if (clickStatus) {
        attri.isLiked = false;
      } else {
        attri.isLiked = true;
      }
      // attri.isLiked = clickStatus;
      // let posts = 'posts/'+postId;
      this.appFetch({
        url:'posts',
        method:'patch',
        splice:'/'+firstPostId,
        data:{
          "data": {
            "type": "posts",
            "attributes": attri,
          }
        }
      }).then((res)=>{
        if (res.errors){
          this.$toast.fail(res.errors[0].code);
          throw new Error(res.error)
        } else {
          if(clickStatus){
            this.likedStatus = res.readdata._data.isLiked;
            this.themeList[itemIndex].firstPost._data.isLiked = this.likedStatus;
            this.themeList[itemIndex].firstPost.likedUsers.map((value, key, likedUsers) => {
              value._data.id === this.userId && likedUsers.splice(key,1);
            });
          } else {
            //点赞
            this.likedStatus = res.readdata._data.isLiked;
            this.themeList[itemIndex].firstPost._data.isLiked = this.likedStatus;
            this.themeList[itemIndex].firstPost.likedUsers.unshift({_data:{username:this.currentUserName,id:this.userId}})
          }
          // if(clickType == 2){

          // }
        }
      })
    },


    loadPriviewImgList(){
      if(this.themeListResult =='' || this.themeListResult == null){
        return false;
      } else {
        var themeListLen = this.themeListResult.length;
        for (let h = 0; h < themeListLen; h++) {
          // 图片地址
          // let src = '/api/attachments/';
          let imageList = [];
          if(this.themeListResult[h].firstPost.images){
            for (let i = 0; i < this.themeListResult[h].firstPost.images.length; i++) {
              imageList.push(this.themeListResult[h].firstPost.images[i]._data.thumbUrl);
              // imageList.push(src + this.themeListResult[h].firstPost.images[i]._data.uuid);
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
    },
    //主题详情图片放大轮播index值监听
    onChange(index) {
      this.index = index+1;
    },
    checkAll(){
      this.$refs.checkboxGroup.toggleAll(true);
    },
    signOutDele(){
      this.$refs.checkboxGroup.toggleAll();
    },

    deleteAllClick(){
      this.$emit('deleteAll',this.result);
    },


    //点击标题跳转到主题详情页
    jumpThemeDet:function(id,canViewPosts){
      if(canViewPosts){
        this.$router.push({ path:'/details'+'/'+id});
      } else {
        this.$toast.fail('没有权限，请联系站点管理员');
      }

    },
    //点击用户名称，跳转到用户主页
    jumpPerDet:function(id){
      this.$router.push({ path:'/home-page'+'/'+id});
    },
    
  },
  mounted: function() {
    document.addEventListener('click', this.disappear, false);
  },
  destroyed: function() {
    document.addEventListener('click', this.disappear, false);
  },
  beforeRouteLeave (to, from, next) {
    next()
  }
}
