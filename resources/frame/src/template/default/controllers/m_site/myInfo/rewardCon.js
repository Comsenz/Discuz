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
      stateTitle:'打赏了我',
      pageIndex: 1,
      pageLimit: 20,
      loading: false,
      finished: false,
      offset: 100,
      isLoading: false,
    
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
    myRewardList(initStatus=false){
     return this.appFetch({
        url:'notice',
        method:'get',
        data:{
          type:'3',
          'page[number]': this.pageIndex,
          'page[limit]': this.pageLimit
        }
      }).then(res=>{
        console.log(res)
        if(initStatus){
          this.rewardList = []
        }
        this.rewardList =this.rewardList.concat(res.readdata);
        this.loading = false;
        this.finished = res.data.length < this.pageLimit;s
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
      this.myRewardList();
    },
    onRefresh(){
        this.pageIndex = 1
        this.myRewardList(true).then(()=>{
          this.$toast('刷新成功');
          this.isLoading = false;
          this.finished = false;
        }).catch((err)=>{
          this.$toast('刷新失败');
          this.isLoading = false;
        })
    }
  },

}
