
import orderDetailsHeader from '../../../view/m_site/common/loginSignUpHeader/loginSignUpHeader'
import Panenl from '../../../view/m_site/common/panel';

export default {
  data:function () {
    return {
      orderList:[],
      type:{
        1:'注册',
        2:'打赏'
      },
      status:{
        0:'待付款',
        1:'已付款'
      },
      pageIndex: 1,
      pageLimit: 20,
      loading: false,
      finished: false,
      offset: 100,
      isLoading: false,

    }
  },

  components:{
    orderDetailsHeader,
    Panenl
  },
  created(){
    this.order()
  },
  methods:{
    order(initStatus = false){
      return this.appFetch({
        url:'orderList',
        method:'get',
        data:{
          include:'',
          'page[number]': this.pageIndex,
          'page[limit]': this.pageLimit
        }
      }).then((res)=>{
        if(initStatus){
          this.orderList = [];
        }
        this.orderList = this.orderList.concat(res.data);
        this.loading = false;
        this.finished = res.data.length < this.pageLimit;

      }).catch((err)=>{
        if(this.loading && this.pageIndex !== 1){
          this.pageIndex--;
        }
        this.loading = false;
      })
    },

    onLoad(){
      console.log('onLoadonLoadonLoad')
      this.loading = true;
      this.pageIndex++;
      this.order();
    },

    onRefresh(){
      this.pageIndex = 1;
      this.order(true).then((res)=>{
        this.$toast('刷新成功');
        this.finished = false;
        this.isLoading = false;
      }).catch((err)=>{
        this.$toast('刷新失败');
        this.isLoading = false;
      })
    }
  }
}
