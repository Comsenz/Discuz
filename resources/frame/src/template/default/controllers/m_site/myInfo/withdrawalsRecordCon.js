
import WithdrawHeader from '../../../view/m_site/common/loginSignUpHeader/loginSignUpHeader'
import Panenl from '../../../view/m_site/common/panel';
import webDb from '../../../../../helpers/webDbHelper';

export default {
  data:function () {
    return {
      withdrawalsList:[],
      cashStatusObj:{
        1:'待审核',
        2:'审核通过',
        3:'审核不通过',
        4:'待打款',
        5:'已打款',
        6:'打款失败'
      },
      loading: false,  //是否处于加载状态
      finished: false, //是否已加载完所有数据
      isLoading: false, //是否处于下拉刷新状态
      pageIndex: 1,//页码
      offset: 100, //滚动条与底部距离小于 offset 时触发load事件
      immediateCheck:false ,//是否在初始化时立即执行滚动位置检查
      pageLimit:20,
      userId:''
    }
  },

  components:{
    WithdrawHeader,
    Panenl
  },
  created(){
    this.userId = webDb.getLItem('tokenId');
    this.reflect();
  },
  methods:{
    async reflect(initStatus = false){
      this.loading = true;
      try{
        const response = await this.appFetch({
          url:'reflect',
          method:'get',
          data:{
            include:'',
            'filter[user]':this.userId,
            'page[number]': this.pageIndex,
            'page[limit]': this.pageLimit
          }
        })
        if (response.errors){
          this.$toast.fail(response.errors[0].code);
          throw new Error(response.error)
        }else{
        if(initStatus){
          this.withdrawalsList = [];
        }

        this.finished = response.data.length < this.pageLimit;
        this.withdrawalsList = this.withdrawalsList.concat(response.data);
      }
      } catch(err){

        if(this.loading && this.pageIndex !== 1){
          this.pageIndex --;
        }

      } finally {
        this.loading = false;
      }
    },
    onLoad(){    //上拉加载
      this.loading = true;
      this.pageIndex++;
      this.reflect()
    },
    async onRefresh(){    //下拉刷新
      try{
        this.pageIndex = 1;
        await this.reflect(true)
        this.$toast('刷新成功');
        this.isLoading = false;
      } catch(err){
        this.$toast('刷新失败');
      }
    }
  }
}
