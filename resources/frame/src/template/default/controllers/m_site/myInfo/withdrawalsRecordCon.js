
import WithdrawHeader from '../../../view/m_site/common/loginSignUpHeader/loginSignUpHeader'
import Panenl from '../../../view/m_site/common/panel';

export default {
  data:function () {
    return {
      withdrawalsList:[
        // {
        //   cash_status:"审核通过，打款中",
        //   cash_apply_amount:"-1809",
        //   cash_sn:'',
        //   created_at:'',
        // },
       
      ],
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
      onload:false,
    }
  },

  components:{
    WithdrawHeader,
    Panenl
  },
  mounted(){
    this.reflect()
  },
  methods:{
    reflect(){
      console.log(this.onload)
     return this.appFetch({
        url:'reflect',
        method:'get',
        data:{
          include:'',
          'page[number]': this.pageIndex,
          'page[limit]': this.pageLimit
        }
      }).then((res)=>{
        this.withdrawalsList = res.data;
        this.pageIndex++;
        if(this.onload){
          this.loading = false;
          if(this.withdrawalsList.length >0){
            this.withdrawalsList = this.withdrawalsList.concat(res.data);
            this.pageIndex++;
            this.finished = false; //数据全部加载完成
          }else{
            console.log('最后');
            console.log(res.data);
            console.log('123');
            // this.withdrawalsList = this.withdrawalsList.concat(res.data);
            this.finished = true
            console.log(this.withdrawalsList,'没有更多啦啦啦啦啦啦啦')
          }
        }
        // if(this.withdrawalsList.length<this.pageLimit){
        //   this.pageIndex--
        // }
        console.log(this.withdrawalsList)
      })
    },
    onLoad(){    //上拉加载
      this.onload = true
      this.reflect()
      console.log(11111111111111111111111111111111)
      // if(this.withdrawalsList !=''){
      //   console.log(999999999999999)
      //   this.reflect();
      //   this.loading = false;
      //   if(this.withdrawalsList.length >0){
      //     this.withdrawalsList = this.withdrawalsList.concat(res.data);
      //     this.pageIndex++;
      //     this.finished = false; //数据全部加载完成
      //   }else{
      //     this.finished = true
      //   }
      // }
        
        // if(res.data.length >0){
        //   this.withdrawalsList = this.withdrawalsList.concat(res.data);
        //   this.pageIndex++;
        //   this.finished = false; //数据全部加载完成
        // }else{
        //   this.finished = true
        // }
      
     
  },
  onRefresh(){    //下拉刷新
    setTimeout(()=>{
      this.pageIndex = 1;
      this.reflect().then(()=>{
        this.$toast('刷新成功');
        this.isLoading = false;
        this.finished = false;
      })
      
    },200)
  }
  }
  }
