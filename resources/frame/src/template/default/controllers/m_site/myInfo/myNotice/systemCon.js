/**
 * 系统通知
 */
import SystemHeader from '../../../../view/m_site/common/loginSignUpHeader/loginSignUpHeader';


export default {
  data:function () {
    return {
      systemResList:[],
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
    SystemHeader
  },
  created(){
    this.systemList();
  },
  methods:{
    systemList(initStatus=false){
     return this.appFetch({
        url:'notice',
        method:'get',
        data:{
          'page[number]': this.pageIndex,
          'page[limit]': this.pageLimit,
          'filter[type]': 'system',
        }
      }).then(res=>{
        if (res.errors){
          this.$toast.fail(res.errors[0].code);
          throw new Error(res.error)
        } else {
          if(initStatus){
            this.systemResList = []
          }
          this.systemResList =this.systemResList.concat(res.readdata);
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
      this.systemList();
    },
    onRefresh(){
        this.pageIndex = 1
        this.systemList(true).then(()=>{
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
