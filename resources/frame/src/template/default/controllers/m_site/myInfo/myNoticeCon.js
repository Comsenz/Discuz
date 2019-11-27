/**
 * 我的通知
 */

import MyNoticeHeader from '../../../view/m_site/common/loginSignUpHeader/loginSignUpHeader'

export default {
  data:function () {
    return {
      num:9,
      numReply:1,
      numReward:1,
    }
  },
  // mounted(){
  //   this.notice()//我的通知里点赞我的
  //   this.noticeReply()//我的通知里回复我的
  //   this.noticeReward()//我的通知里打赏我的
  // },
  methods:{
    myJump(str){
      switch (str) {
        case 'reply':
          this.$router.push('/reply');
          break;
        case 'reward':
          this.$router.push('/reward');
          break;
        case 'like':
          this.$router.push('/like');
          break;
        default:
          this.$router.push('/');
      }
    },
    // notice(){
    //   this.appFetch({
    //     url:'notice',
    //     method:'get',
    //     data:{
    //       describe:'2' //我的通知页面里点赞我的
    //     },
    //   },(res)=>{
    //     console.log(res)
    //   }).then((res)=>{
    //     this.data = res.data;
    //     if(this.data.length > 0){
    //       this.num = res.data.length
    //     }else{
    //       this.num = 0
    //     }
    //     console.log(this.num)
    //   })
    // },

    // noticeReply(){
    //   this.appFetch({
    //     url:'notice',
    //     method:'get',
    //     data:{
    //       describe:'1' //我的通知页面里回复我的
    //     },
    //   },(res)=>{
    //     console.log(res)
    //   }).then((res)=>{
    //     this.data = res.data;
    //     if(this.data.length > 0){
    //       this.numReply = res.data.length
    //     }else{
    //       this.numReply = 0
    //     }
    //     console.log(this.numReply)
    //   })
    // },

    // noticeReward(){
    //   this.appFetch({
    //     url:'notice',
    //     method:'get',
    //     data:{
    //       describe:'3' //我的通知页面里打赏我的
    //     },
    //   },(res)=>{
    //     console.log(res)
    //   }).then((res)=>{
    //     this.data = res.data;
    //     if(this.data.length > 0){
    //       this.numReward = res.data.length
    //     }else{
    //       this.numReward = 0
    //     }
    //     console.log(this.numReward)
    //   })
    // }
  },

  components:{
    MyNoticeHeader
  },

}
