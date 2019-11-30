/**
 * 回复我的
 */



export default {
  data:function () {
    return {
      replyList:[]
    }
  },
  created(){
    this.imgUrl = "../../../../../../../static/images/mytx.png";
    this.loadTheme();
  },
  methods:{
    loadTheme(){
      const params = {
        type:'1'
      };
      // params.include = 'user,firstPost,lastThreePosts,lastThreePosts.user,firstPost.likedUsers,rewardedUsers';
      this.apiStore.find('notice', {type:1}).then(data => {
        // console.log(data[0].user()username());
        this.replyList = data;
        console.log(data)
      });
    },
    // myReply(){
    //   this.apiStore.find('notice', {type:1}).then(res => {
    //     this.replyList = res;
    //     console.log(this.replyList)
    //   });
    //   // this.appFetch({
    //   //   url:'notice',
    //   //   method:'get',
    //   //   data:{
    //   //     type:'1'
    //   //   }
    //   // }).then((res)=>{
    //   //   console.log(res);
    //   //   // this.replyList = res.data
    //   //   // console.log(res.data[0].attributes.data.user_name)
    //   // })
    // },
    deleteReply(index){
      this.replyList.splice(index,1)
    }
  }
}
