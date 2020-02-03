/**
 * 点赞我的
 */

import LikeHeader from '../../../view/m_site/common/loginSignUpHeader/loginSignUpHeader'
import ContHeader from '../../../view/m_site/common/cont/contHeaderView'
import ContMain from '../../../view/m_site/common/cont/contMainView'
import ContFooter from '../../../view/m_site/common/cont/contFooterView'


export default {
  data:function () {
    return {
      likeList:[],
      stateTitle:'点赞了我',
      pageIndex: 1,
      pageLimit: 20,
      loading: false,
      finished: false,
      offset: 100,
      isLoading: false,
    }
  },
  components:{
    LikeHeader,
    ContHeader,
    ContMain,
    ContFooter
  },
  created(){
    this.imgUrl = "../../../../../../../static/images/mytx.png"
    this.myLikeList()
  },
  methods:{
    myLikeList(initStatus =false){
     return this.appFetch({
        url:'notice',
        method:'get',
        data:{
          'page[number]': this.pageIndex,
          'page[limit]': this.pageLimit,
          'filter[type]': 'liked'
        }
      }).then(res=>{
        if (res.errors){
          this.$toast.fail(res.errors[0].code);
          throw new Error(res.error)
        }else{
        if(initStatus){
          this.likeList=[]
        }
        console.log(res)
        // console.log(res.readdata)
        this.likeList = this.likeList.concat(res.readdata);
        // console.log(this.likeList,'点赞我的')
        this.loading = false;
        this.finished = res.data.length < this.pageLimit;
      }
      }).catch((err)=>{
        if(this.loading && this.pageIndex !== 1){
          this.pageIndex--;
        }
        this.loading = false;
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
        if (res.errors){
          this.$toast.fail(res.errors[0].code);
          // throw new Error(res.error)
        }else{
          this.$toast.success('删除成功');
          this.pageIndex = 1;
          this.myLikeList(true)
        }
      })
    },
    onLoad(){    //上拉加载
      this.loading = true;
      this.pageIndex++;
      this.myLikeList();
  
    },
    onRefresh(){
        this.pageIndex = 1
        this.myLikeList(true).then((res)=>{
          this.$toast('刷新成功');
          this.isLoading = false;
          this.finished = false;
        }).catch((err)=>{
          this.$toast('刷新失败');
          this.isLoading = false;
        })
    },
    // jumpPerDet:function(id){
    //   this.$router.push({ path:'/home-page'+'/'+id});
    // },
  },

}
