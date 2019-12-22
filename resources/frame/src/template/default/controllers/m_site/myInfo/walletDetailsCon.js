
import walletDetailsHeader from '../../../view/m_site/common/loginSignUpHeader/loginSignUpHeader'
import Panenl from '../../../view/m_site/common/panel';

export default {
  data:function () {
    return {
      walletDetailsList:{

      },
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
    walletDetails(){
    return  this.appFetch({
        url:'walletDetails',
        method:'get',
        data:{
          include:'',
          'page[number]': this.pageIndex,
          'page[limit]': 20
        }
      }).then((res)=>{
        this.walletDetailsList = res.data
        this.pageIndex++
      })
    },
    onLoad(){    //上拉加载
      this.appFetch({
        url:'walletDetails',
        method:'get',
        data:{
          include:'',
          'page[number]': this.pageIndex,
          'page[limit]': 20
        }
      }).then(res=>{
        console.log(res.readdata)
        this.loading = false;
        if(res.data.length > 0){
          this.walletDetailsList = this.walletDetailsList.concat(res.data);
          this.pageIndex++;
          this.finished = false; //数据全部加载完成
        }else{
          this.finished = true
        }
      })
  },
  onRefresh(){    //下拉刷新
    setTimeout(()=>{
      this.pageIndex = 1;
      this.walletDetails().then(()=>{
        this.$toast('刷新成功');
        this.isLoading = false;
        this.finished = false;
      })
      
    },200)
  }
  }
}
