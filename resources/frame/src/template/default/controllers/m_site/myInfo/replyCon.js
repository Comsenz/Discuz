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
      this.appFetch({
        url:'notice',
        method:'get',
        data:{
          // include: ['user', 'firstPost', 'lastThreePosts', 'lastThreePosts.user', 'firstPost.likedUsers', 'rewardedUsers'],
          type:1
        }
      }).then(res=>{
        this.replyList = res.readdata;
        console.log(res)
        console.log(this.replyList)
      })
      // const params = {
      //   type:'1'
      // };
      // // params.include = 'user,firstPost,lastThreePosts,lastThreePosts.user,firstPost.likedUsers,rewardedUsers';
      // this.apiStore.find('notice', {type:1}).then(data => {
      //   // console.log(data[0].user()username());
      //   this.replyList = data;
      //   console.log(data)
      // });
    },
    deleteReply(index){
      this.replyList.splice(index,1)
    }
  }
}
