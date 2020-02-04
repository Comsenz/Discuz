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
      stateTitle:'回复了我',
      isLoading: false, //是否处于下拉刷新状态
      loading: false,  //是否处于加载状态
      finished: false, //是否已加载完所有数据
      pageIndex: 1,
      pageLimit: 20,
      offset: 100, //滚动条与底部距离小于 offset 时触发load事件

    }
  },
  components:{
    ReplyHeader,
    ContHeader,
    ContMain,
    ContFooter
  },
  created(){
    this.imgUrl = "../../../../../../../static/images/noavatar.gif"
    this.myReplyList()
  },
  methods:{
    myReplyList(initStatus = false){
      return this.appFetch({
        url:'notice',
        method:'get',
        data:{
          'page[number]': this.pageIndex,
          'page[limit]': this.pageLimit,
          'filter[type]': 'replied'
        }
      }).then(res=>{
        if (res.errors){
          this.$toast.fail(res.errors[0].code);
          throw new Error(res.error)
        }else{
        if(initStatus){
          this.replyList = []
        }
        console.log(res,'000000')
        this.replyList = this.replyList.concat(res.readdata);
        this.loading = false;
        this.finished = res.data.length < this.pageLimit;
      }
      }).catch((err)=>{
        if(this.loading && this.pageIndex !== 1){
            this.pageIndex--
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
          this.myReplyList(true)
        }
      })
    },
    onRefresh(){           //下拉刷新
        this.pageIndex = 1;
        this.myReplyList(true).then(()=>{
          this.$toast('刷新成功');
          this.isLoading = false;
          this.finished = false;
        }).catch((err)=>{
          this.$toast('刷新失败');
          this.isLoading = false;
        })
    },
    onLoad(){
      console.log('onLoadonLoadonLoad')
      this.loading = true;
      this.pageIndex++;
      this.myReplyList();
    },
  },

}
