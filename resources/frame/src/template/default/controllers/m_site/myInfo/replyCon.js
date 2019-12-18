/**
 * 回复我的
 */

import ReplyHeader from '../../../view/m_site/common/loginSignUpHeader/loginSignUpHeader'
import ContHeader from '../../../view/m_site/common/cont/contHeaderView'
import ContMain from '../../../view/m_site/common/cont/contMainView'
import ContFooter from '../../../view/m_site/common/cont/contFooterView'


export default {
  data:function () {
    return {
      replyList:[],
      isLoading: false, //是否处于下拉刷新状态
      loading: false,  //是否处于加载状态
      finished: false, //是否已加载完所有数据
     
    }
  },
  components:{
    ReplyHeader,
    ContHeader,
    ContMain,
    ContFooter
  },
  created(){
    this.imgUrl = "../../../../../../../static/images/mytx.png",
    this.myReplyList()
  },
  methods:{
    myReplyList(){
      return this.appFetch({
        url:'notice',
        method:'get',
        data:{
          type:'1'
        }
      }).then(res=>{
        console.log(res)
        this.replyList = res.readdata;
      })
    },
    deleteReply(replyId){    //删除回复
      console.log(replyId,'00000')
      // let deleteNotification = 'deleteNotification/'+replyId;
      this.appFetch({
        url:'deleteNotification',
        method:'delete',
        splice:'/'+replyId,
        data:{

        }
      }).then(res=>{
        this.myReplyList()
      })
    },
    onRefresh(){           //下拉刷新
      setTimeout(()=>{
        this.myReplyList().then(()=>{
          this.$toast('刷新成功');
          this.isLoading = false;
          this.finished = true;
        }) 
      },200)
    },
    onLoad(){    //上拉加载
      this.appFetch({
        url:'collection',
        method:'get',
        data:{
          include:['user', 'firstPost', 'lastThreePosts', 'lastThreePosts.user', 'firstPost.likedUsers', 'rewardedUsers'],
        }
      }).then(res=>{
        // this.pageSize = res.meta.threadCount;
        // this.pageIndex = res.meta.pageCount;
        // this.collectionList = res.readdata;
        // 加载状态结束
        this.loading = false;
        if(res.readdata === ''){
          this.finished = false; //数据全部加载完成
        }else{
          this.finished = true
        }

      console.log(this.finished,'00000000000000000000')

      })
      // setTimeout(()=>{
        
      // this.loading = false;
      //     // 数据全部加载完成
      //     if (this.collectionList.length >= 40) {
      //       this.finished = true;
      //     }
      // },200)
    },
  },

}
