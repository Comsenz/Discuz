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
      loading: false,  //是否处于加载状态
      finished: false, //是否已加载完所有数据
      isLoading: false, //是否处于下拉刷新状态
    }
  },
  components:{
    LikeHeader,
    ContHeader,
    ContMain,
    ContFooter
  },
  methods:{
    myLikeList(){
     return this.appFetch({
        url:'notice',
        method:'get',
        data:{
          type:'2'
        }
      }).then(res=>{
        console.log(res)
        this.likeList = res.readdata;
      })
    },
    onLoad(){    //上拉加载
      this.appFetch({
        url:'notice',
        method:'get',
        data:{
          type:'2'
        }
      }).then(res=>{
        this.loading = false;
        if(res.readdata.length > 0){
          this.likeList = this.likeList.concat(res.readdata);
          this.pageIndex++;
          this.finished = false; //数据全部加载完成
        }else{
          this.finished = true
        }

      })
    },
    onRefresh(){
      setTimeout(()=>{
        this.pageIndex = 1
        this.myLikeList().then(()=>{
          this.$toast('刷新成功');
          this.isLoading = false;
          this.finished = true;
        })
        
      },200)
    }
  },
  created(){
    this.imgUrl = "../../../../../../../static/images/mytx.png"
    this.myLikeList()
  }
}
