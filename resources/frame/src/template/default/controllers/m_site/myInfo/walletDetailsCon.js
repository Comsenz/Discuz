
import walletDetailsHeader from '../../../view/m_site/common/loginSignUpHeader/loginSignUpHeader'
import Panenl from '../../../view/m_site/common/panel';

export default {
  data:function () {
    return {
      walletDetailsList:[],
      type:{
        10:'提现冻结',
        11:'提现成功',
        12:'提现解冻',
        30:'注册收入',
        31:'打赏收入',
        32:'人工收入',
        50:'人工支出'
      },
      loading: false,  //是否处于加载状态
      finished: false, //是否已加载完所有数据
      isLoading: false, //是否处于下拉刷新状态
      pageIndex: 1,//页码
      pageLimit: 20,
      offset: 100, //滚动条与底部距离小于 offset 时触发load事件
    }
  },

  components:{
    walletDetailsHeader,
    Panenl
  },
  created(){
    this.walletDetails()
  },
  methods:{
    walletDetails(initStatus = false){
    return this.appFetch({
        url:'walletDetails',
        method:'get',
        data:{
          include:'',
          'page[number]': this.pageIndex,
          'page[limit]': this.pageLimit
        }
      }).then((res)=>{
        if (res.errors){
          this.$toast.fail(res.errors[0].code);
          throw new Error(res.error)
        }else{
        if(initStatus){
          this.walletDetailsList = [];
        }
        console.log(res,'2222222222222222')
        this.walletDetailsList = this.walletDetailsList.concat(res.data);
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
    onLoad(){    //上拉加载
      this.loading = true;
      this.pageIndex++;
      this.walletDetails();
  },
  onRefresh(){    //下拉刷新
      this.pageIndex = 1;
      this.walletDetails(true).then(()=>{
        this.$toast('刷新成功');
        this.isLoading = false;
        this.finished = false;
      }).catch((err)=>{
        this.$toast('刷新失败');
        this.isLoading = false;
      })
      
  }
  }
}
