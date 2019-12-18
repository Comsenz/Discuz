/**
 * 打赏我的
 */

//问题：打赏我的字体，应该跟发布的主题内容一致吧。直接打赏的是主题，并不是引用回复这种。

import RewardHeader from '../../../view/m_site/common/loginSignUpHeader/loginSignUpHeader'
import ContHeader from '../../../view/m_site/common/cont/contHeaderView'
import ContMain from '../../../view/m_site/common/cont/contMainView'
import ContFooter from '../../../view/m_site/common/cont/contFooterView'


export default {
  data:function () {
    return {
      rewardList:[],
      loading: false,  //是否处于加载状态
      finished: false, //是否已加载完所有数据
      isLoading: false, //是否处于下拉刷新状态
    
    }
  },
  components:{
    RewardHeader,
    ContHeader,
    ContMain,
    ContFooter
  },
  created(){
    this.imgUrl = '../../../../../../../static/images/mytx.png';
    this.myRewardList()
  },
  methods:{
    myRewardList(){
     return this.appFetch({
        url:'notice',
        method:'get',
        data:{
          type:'3'
        }
      }).then(res=>{
        console.log(res)
        this.rewardList = res.readdata;
        
      })
    },
    onLoad(){    //上拉加载
      this.appFetch({
        url:'notice',
        method:'get',
        data:{
          type:'3'
        }
      }).then(res=>{
        this.loading = false;
        if(res.readdata.length > 0){
          this.rewardList = this.rewardList.concat(res.readdata);
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
        this.myRewardList().then(()=>{
          this.$toast('刷新成功');
          this.isLoading = false;
          this.finished = true;
        })
        
      },200)
    }
  },

}
