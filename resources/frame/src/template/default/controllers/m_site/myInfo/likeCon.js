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
  methods:{
    myLikeList(initStatus =false){
     return this.appFetch({
        url:'notice',
        method:'get',
        data:{
          type:'2'
        }
      }).then(res=>{
        if(initStatus){
          this.likeList=[]
        }
        console.log(res)
        this.likeList = this.likeList.concat(res.readdata);
        this.loading = false;
        this.finished = res.data.length < this.pageLimit;
      }).catch((err)=>{
        if(this.loading && this.pageIndex !== 1){
          this.pageIndex--;
        }
        this.loading = false;
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
    }
  },
  created(){
    this.imgUrl = "../../../../../../../static/images/mytx.png"
    this.myLikeList()
  }
}
