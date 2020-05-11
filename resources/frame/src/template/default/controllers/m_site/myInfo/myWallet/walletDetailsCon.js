
import walletDetailsHeader from '../../../../view/m_site/common/loginSignUpHeader/loginSignUpHeader'
import Panenl from '../../../../view/m_site/common/panel';
import webDb from "../../../../../../helpers/webDbHelper";

export default {
  data:function () {
    return {
      walletDetailsList:[],
      type:{
        10:'提现冻结',
        11:'提现成功',
        12:'提现解冻',
        30:'注册收入',
        31:'打赏了你的主题',
        32:'人工收入',
        50:'人工支出',
        41:'打赏了主题',
        60:'付费查看了你的主题',
        61:'付费查看了主题',
        71:'站点续费支出',
      },
      loading: false,  //是否处于加载状态
      finished: false, //是否已加载完所有数据
      isLoading: false, //是否处于下拉刷新状态
      pageIndex: 1,//页码
      pageLimit: 20,
      offset: 100, //滚动条与底部距离小于 offset 时触发load事件
      userId:''
    }
  },

  components:{
    walletDetailsHeader,
    Panenl
  },
  created(){
    this.userId = webDb.getLItem('tokenId');
    this.walletDetails()
  },
  methods:{
    walletDetails(initStatus = false){
    return this.appFetch({
        url:'walletDetails',
        method:'get',
        data:{
          include:'user,order.user,order.thread,order.thread.firstPost',
          'filter[user]':this.userId,
          'page[number]': this.pageIndex,
          'page[limit]': this.pageLimit
        }
      }).then((res)=>{
        if (res.errors) {
          this.$toast.fail(res.errors[0].code);
          throw new Error(res.error)
        } else {
          if (initStatus) {
            this.walletDetailsList = [];
          }      
          res.readdata.map(item=>{
            switch(item._data.change_type){
              case 10: // 提现冻结
              case 11: // 提现成功
              case 12: // 提现解冻
              case 50: // 人工支出
              case 71: // 站点续费支出
                // title 不变，显示黑色
                item.title = this.type[item._data.change_type];
                item.status = false;
                break;
              case 30: // 注册收入
              case 32: // 人工收入
                // title 不变，显示红色，添加 '+' 号
                item.title = this.type[item._data.change_type];
                item.status = true;
                item._data.change_available_amount = "+" + item._data.change_available_amount;
                break;
              case 31: // 打赏收入
              case 60: // 付费主题收入
                // title 拼接，显示红色，添加 '+' 号
                var orderUser = item.order ? (item.order.user ? item.order.user._data : null) : null;
                var orderThread = item.order ? (item.order.thread ? item.order.thread._data : null) : null;
                item.title = orderUser
                ? `<a href='home-page/${orderUser.id}'>${orderUser.username}</a> `
                : '该用户被删除 ';
                item.title += this.type[item._data.change_type];
                item.title += orderThread
                ? `<a href='details/${orderThread.id}'>“${orderThread.title}”</a>`
                : '“该主题被删除”';
                item.status = true;
                item._data.change_available_amount = "+" + item._data.change_available_amount;
                break;
              case 41: // 打赏支出
              case 61: // 付费主题支出
                // title 拼接，显示黑色
                var orderThread = item.order ? (item.order.thread ? item.order.thread._data : null) : null;
                item.title = this.type[item._data.change_type];
                item.title += orderThread
                ? `<a href='details/${orderThread.id}'>“${orderThread.title}”</a>`
                : '“该主题被删除”';
                item.status = false;
                break;
              default: // 未知变更类型    
                item.title = 'unknown change type';
                item.status = false;
            }
          });
          this.walletDetailsList = this.walletDetailsList.concat(res.readdata);
          this.loading = false;
          this.finished = res.readdata.length < this.pageLimit;
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
