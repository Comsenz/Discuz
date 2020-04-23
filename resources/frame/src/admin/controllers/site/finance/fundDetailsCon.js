/*
*  资金明细
* */

import Card from '../../../view/site/common/card/card';
import Page from '../../../view/site/common/page/page';
import webDb from 'webDbHelper';

export default {
  data:function () {
    return {
      tableData: [],
      pickerOptions: {
        shortcuts: [{
          text: '最近一周',
          onClick(picker) {
            const end = new Date();
            const start = new Date();
            start.setTime(start.getTime() - 3600 * 1000 * 24 * 7);
            picker.$emit('pick', [start, end]);
          }
        }, {
          text: '最近一个月',
          onClick(picker) {
            const end = new Date();
            const start = new Date();
            start.setTime(start.getTime() - 3600 * 1000 * 24 * 30);
            picker.$emit('pick', [start, end]);
          }
        }, {
          text: '最近三个月',
          onClick(picker) {
            const end = new Date();
            const start = new Date();
            start.setTime(start.getTime() - 3600 * 1000 * 24 * 90);
            picker.$emit('pick', [start, end]);
          }
        }]
      },
      userName:'',
      changeTime:['',''],
      changeDescription:'',

      total:0,                    //总数
      pageCount:0,                //总页数
      currentPaga:1               //第几页
    }
  },
  methods:{

    searchClick(){
      if (this.changeTime == null){
        this.changeTime = ['','']
      } else if(this.changeTime[0] !== '' && this.changeTime[1] !== ''){
        this.changeTime[0] = this.changeTime[0] + '-00-00-00';
        this.changeTime[1] = this.changeTime[1] + '-24-00-00';
      }
      this.currentPaga = 1;
      this.getFundingDetailsList();
    },

    handleCurrentChange(val){
      this.currentPaga = val;
      this.getFundingDetailsList();
    },

    /*
    * 格式化日期
    * */
    formatDate(data){
      return this.$dayjs(data).format('YYYY-MM-DD HH:mm')
    },


    /*
    * 接口请求
    * */
    getFundingDetailsList(){
      this.appFetch({
        url:'walletDetails',
        method:'get',
        data:{
          include:['user','userWallet'],
          'page[number]':this.currentPaga,
          'page[size]':10,
          'filter[username]' : this.userName,
          'filter[change_desc]' : this.changeDescription,
          'filter[start_time]' : this.changeTime[0],
          'filter[end_time]' : this.changeTime[1]
        }
      }).then(res=>{
        if (res.errors){
          this.$message.error(res.errors[0].code);
        }else {
          this.tableData = [];
          this.tableData = res.readdata;
          this.total = res.meta.total;
          this.pageCount = res.meta.pageCount;
        }
      }).catch(err=>{
      })
    },

    getCreated(state){
      if(state){
        this.currentPaga = 1;
      } else {
        this.currentPaga = Number(webDb.getLItem('currentPag'))||1;
      }
      this.getFundingDetailsList();
    }
  },
  created(){
    // this.currentPaga = Number(webDb.getLItem('currentPag'))||1;
    // this.getFundingDetailsList(Number(webDb.getLItem('currentPag'))||1);
  },
  beforeRouteEnter (to,from,next){
    next(vm => {
      if (to.name !== from.name && from.name !== null){
        vm.getCreated(true)
      }else {
        vm.getCreated(false)
      }
    })
  },
  components:{
    Card,
    Page
  }
}
